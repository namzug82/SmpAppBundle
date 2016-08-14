<?php
namespace AppBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Model\UsersMaterialsLinker;

class MaterialController extends Controller
{
	const SHORT_CACHE_LIFETIME = 600;
	const MEDIUM_CACHE_LIFETIME = 3600;
	const LONG_CACHE_LIFETIME = 86400;

	/**
     * @Route("/materials/", name="materials")
     * @Method("GET")
     */
	public function indexAction(Request $request)
	{
		$allMaterials = $this->getAllMaterials();
		$pagination = $this->getPaginatedResults($request, $allMaterials);

		$response = new Response();
	    $response->setPublic();
	    $response->setMaxAge(self::SHORT_CACHE_LIFETIME);
	    $response->headers->addCacheControlDirective('must-revalidate', true);
	    
    	// parameters to template
    	return $this->render(
    		'AppBundle:Frontend:materials.html.twig', array(
    			'pagination' => $pagination
    	), $response);
	}

	/**
     * @Route("/material/search/", name="material_search")
     * @Method("GET")
     */
	public function searchAction(Request $request)
	{
		$result = $this->get('material_sphinx_repo')->retrieve($request->query->get('term'));

    	if ($result["total_found"] == 0) {
			return $this->redirect($this->generateUrl('materials'));
		}

		$pagination = $this->getPaginatedResults($request, $result['matches']);

		$response = new Response();
	    $response->setPublic();
	    $response->setMaxAge(self::MEDIUM_CACHE_LIFETIME);
	    $response->setEtag(md5($request->query->get('term')));

    	return $this->render('AppBundle:Frontend:material_search.html.twig', array(
				'result' => $result['matches'],
				'pagination' => $pagination
		), $response);
	}

	/**
     * @Route("/material/{slug}/", name="material_show")
     * @Method("GET")
     */
	public function showAction($slug)
	{
		$material = $this->getDoctrine()
	        ->getRepository('AppBundle:Material')
	        ->findOneBySlug($slug);

        if (!$material) {
	        return $this->redirect($this->generateUrl('materials'));
	    }

	    $user= $this->get('security.context')->getToken()->getUser();
	    if ($user != "anon.") {
	    	$usersMaterialsLinker = $this->get('users_materials_linker');
	    	$usersMaterialsLinker->link($user, $material);
	    }

	    $response = new Response();
	    $response->setPublic();
	    $response->setMaxAge(self::LONG_CACHE_LIFETIME);
	    $response->setEtag(md5($slug));
	        
		return $this->render(
			'AppBundle:Frontend:material.html.twig', array(
				'material' => $material	
		), $response);
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
}
