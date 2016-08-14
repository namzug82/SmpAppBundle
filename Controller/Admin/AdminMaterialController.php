<?php
namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\Material;
use AppBundle\Form\Admin\AdminMaterialType;
use Aws\S3\S3Client;

class AdminMaterialController extends Controller
{

	/**
     * @Route("/admin/materials/", name="admin_materials")
     * @Method("GET")
     */
	public function indexAction(Request $request)
	{
		$allMaterials = $this->getAllMaterials();

		$pagination = $this->getPaginatedResults($request, $allMaterials);
	    
    	// parameters to template
    	return $this->render(
    		'AppBundle:Admin:admin_materials.html.twig', array(
    			'pagination' => $pagination
    		)
    	);
	}

	/**
     * @Route("/admin/material/search/", name="admin_material_search")
     * @Method("GET")
     */
	public function searchAction(Request $request)
	{
    	$result = $this->get('material_sphinx_repo')->retrieve($request->query->get('term'));

    	if ($result["total_found"] == 0) {
			return $this->redirect($this->generateUrl('admin_materials'));
		}

		$pagination = $this->getPaginatedResults($request, $result['matches']);

    	return $this->render('AppBundle:Admin:admin_material_search.html.twig', array(
				'result' => $result['matches'],
				'pagination' => $pagination
		));
	}

	/**
	 * @Route("/admin/material/new/", name="admin_material_new")
	 */
	public function newAction(Request $request)
	{
		$request = $this->getRequest();

		$material = new Material();
		$form = $this->createForm(new AdminMaterialType(), $material);

		if ($request->getMethod() == 'POST') {
			$form->submit($request);
			if ($form->isValid()) {
				$image1 = $material->getImage1();
				$filePath1 = $this->uploadImage($image1);
				$material->setImage1($filePath1);
				// $this->removeLocalMaterialImageFile($image1->getClientOriginalName());

				$image2 = $material->getImage2();
				$filePath2 = $this->uploadImage($image2);
				$material->setImage2($filePath2);
				// $this->removeLocalMaterialImageFile($image2->getClientOriginalName());

				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($material);
				$em->flush();
				return $this->redirect($this->generateUrl('admin_materials'));
			}
		}

		return $this->render('AppBundle:Admin:admin_material_new.html.twig', array(
				'admin_material_form' => $form->createView(),
		));
	}

	/**
	 * @Route("/admin/material/{id}/edit", name="admin_material_edit")
	 */
	public function editAction(Request $request, $id)
	{
		$material = $this->getDoctrine()
	        ->getRepository('AppBundle:Material')
	        ->find($id);

	    if (!$material) {
	        throw $this->createNotFoundException(
	            'No material found for id '.$id
	        );
	    }

	    //Get the file
	    $image1 = $material->getImage1();		
	    $material->setImage1(
    		$this->getMaterialImageFile($image1)
		);

		$image2 = $material->getImage2();		
	    $material->setImage2(
    		$this->getMaterialImageFile($image2)
		);

		$form = $this->createForm(new AdminMaterialType(), $material);

		if ($request->getMethod() == 'POST') {
			$form->handleRequest($request);

			if ($form->isValid()) {
				
				$image1 = $material->getImage1();
				$filePath1 = $this->uploadImage($image1);
				$material->setImage1($filePath1);
				// $this->removeLocalMaterialImageFile($image1->getClientOriginalName());

				$image2 = $material->getImage2();
				$filePath2 = $this->uploadImage($image2);
				$material->setImage2($filePath2);
				// $this->removeLocalMaterialImageFile($image2->getClientOriginalName());
				
				$em = $this->getDoctrine()->getEntityManager();
				$em->flush();

				$this->addFlash('info',
					'Los datos del material se han actualizado correctamente'
				);
				return $this->redirect($this->generateUrl('admin_materials'));
			}
		}
		return $this->render('AppBundle:Admin:admin_material_edit.html.twig', array(
			'material'				=>	$material,
			'admin_material_form'	=>	$form->createView()
		));
	}

	/**
	 * @Route("/admin/material/{id}/delete", name="admin_material_delete")
	 * @Method({"GET", "DELETE"})
	 */
	public function deleteAction($id)
	{
		$material = $this->getDoctrine()
	        ->getRepository('AppBundle:Material')
	        ->find($id);

	    if (!$material) {
	        throw $this->createNotFoundException(
	            'No material found for id '.$id
	        );
	    }

	    $em = $this->getDoctrine()->getEntityManager();
		$em->remove($material);
		$em->flush();

		return $this->redirect($this->generateUrl('admin_materials'));
	}

	private function getAllMaterials()
	{
		return $this->getDoctrine()
	        ->getRepository('AppBundle:Material')
	        ->findAll();
	}

	private function getPaginatedResults($request, $results)
	{
		$paginator  = $this->get('knp_paginator');
    	return $paginator->paginate(
        	$results,  /*query NOT result */
        	$request->query->getInt('page', 1)/*page number*/,
        	10/*limit per page*/
    	);
	}

	private function uploadImage($image)
	{
		if (null == $image) {
			return;
		}

		$fileName =  $image->getClientOriginalName();
				
	    $image->move(
	        $this->container->getParameter('images_directory'),
	        $fileName
	    );
				
		$baseUrl = $this->get('kernel')->getRootDir() . '/../web' . $this->getRequest()->getBasePath() . "/uploads/images/" . $fileName;
		$s3 = $this->get('s3_client');

	   	return $s3->upload($image, $baseUrl);  	
	}

	private function getMaterialImageFile($image)
	{
		if (null == $image) {
			return;
		}

		//Get the file
	    $filePath = $this->container->getParameter('images_directory') . "/image_tmp";
	    $file = fopen($filePath, "w+");
		$content = file_get_contents($image);
		file_put_contents($filePath, $content);
		fclose($file);
	   
    	return new File($filePath);
	}

	private function removeLocalMaterialImageFile($fileName)
	{
		$fs = new Filesystem();
		$fs->remove(array('symlink', $this->get('kernel')->getRootDir() . '/../web' . $this->getRequest()->getBasePath() . "/uploads/images/", $fileName));
	}
}
