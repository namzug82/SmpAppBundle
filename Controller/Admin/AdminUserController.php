<?php
namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\User;
use AppBundle\Form\Admin\AdminUserType;

class AdminUserController extends Controller
{
	/**
     * @Route("/admin/users/", name="show_users")
     * @Method("GET")
     */
	public function showAction(Request $request)
	{
		$users = $this->getDoctrine()
	        ->getRepository('AppBundle:User')
	        ->findAll();

	    $paginator  = $this->get('knp_paginator');
    	$pagination = $paginator->paginate(
        	$users,  /*query NOT result */
        	$request->query->getInt('page', 1)/*page number*/,
        	10/*limit per page*/
    	);

	    return $this->render(
    		'AppBundle:Admin:admin_users.html.twig', array(
    			'pagination' => $pagination
    		)
    	);
	}

	/**
     * @Route("/admin/user/search/", name="admin_user_search")
     * @Method("GET")
     */
	public function searchAction(Request $request)
	{
		$result = $this->get('user_sphinx_repo')->retrieve($request->query->get('term'));

    	if ($result["total_found"] == 0) {
			return $this->redirect($this->generateUrl('show_users'));
		}

		$pagination = $this->getPaginatedResults($request, $result['matches']);

    	return $this->render('AppBundle:Admin:admin_user_search.html.twig', array(
				'result' => $result['matches'],
				'pagination' => $pagination
		));
	}

	/**
	 * @Route("/admin/user/new/", name="admin_user_new")
	 */
	public function newAction()
	{
		$request = $this->getRequest();

		$user = new User();
		$form = $this->createForm(new AdminUserType(), $user);

		if ($request->getMethod() == 'POST') {
			$form->submit($request);
			if ($form->isValid()) {
				$encoder = $this->get('security.encoder_factory')
								->getEncoder($user);
				$user->setSalt(md5(time()));
				$encryptedPassword = $encoder->encodePassword(
					$user->getPassword(),
					$user->getSalt()
				);
				$user->setPassword($encryptedPassword);

				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($user);
				$em->flush();
				return $this->redirect($this->generateUrl('show_users'));
			}
		}

		return $this->render('AppBundle:Admin:admin_user_new.html.twig', array(
			'admin_user_form' => $form->createView()
		));
	}

	/**
	 * @Route("/admin/user/{id}/edit", name="admin_user_edit")
	 */
	public function editAction(Request $request, $id)
	{
		$user = $this->getDoctrine()
	        ->getRepository('AppBundle:User')
	        ->find($id);

	    if (!$user) {
	        throw $this->createNotFoundException(
	            'No user found for id '.$id
	        );
	    }

		$form = $this->createForm(new AdminUserType(), $user);

		if ($request->getMethod() == 'POST') {
			$originalPassword = $form->getData()->getPassword();
			$form->handleRequest($request);

			if ($form->isValid()) {
				if (null == $user->getPassword()) {
					$user->setPassword($originalPassword);
				}
				else {
					$encoder = $this->get('security.encoder_factory')
								->getEncoder($user);
					$user->setSalt(md5(time()));
					$encryptedPassword = $encoder->encodePassword(
						$user->getPassword(),
						$user->getSalt()
					);
					$user->setPassword($encryptedPassword);	
				}
				
				$em = $this->getDoctrine()->getEntityManager();
				$em->flush();

				$this->addFlash('info',
					'Los datos del usuario se han actualizado correctamente'
				);
				return $this->redirect($this->generateUrl('show_users'));
			}
		}
		return $this->render('AppBundle:Admin:admin_user_edit.html.twig', array(
			'user'				=>	$user,
			'admin_user_form'	=>	$form->createView()
		));
	}

	/**
	 * @Route("/admin/user/{id}/delete", name="admin_user_delete")
	 * @Method({"GET", "DELETE"})
	 */
	public function deleteAction($id)
	{
		$userToRemove = $this->getDoctrine()
	        ->getRepository('AppBundle:User')
	        ->find($id);
	    $userOfSession = $this->get('security.context')->getToken()->getUser();

	    if (!$userToRemove) {
	        throw $this->createNotFoundException(
	            'No user found for id '.$id
	        );
	    }

	    $em = $this->getDoctrine()->getEntityManager();
		$em->remove($userToRemove);
		$em->flush();

		if ($id == $userOfSession->getId()) {
			$this->get('security.context')->setToken(null);
			$this->get('request')->getSession()->invalidate();
		}

		return $this->redirect($this->generateUrl('show_users'));
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
}
