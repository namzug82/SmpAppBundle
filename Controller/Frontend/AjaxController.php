<?php
namespace AppBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AjaxController extends Controller
{
	const CACHE_LIFETIME = 600;

	/**
	 * @Route("/materials/array/", name="ajax_get_all_materials")
	 */
	public function indexAction(Request $request)
	{		
		$arrayOfAllMaterials = $this->getDoctrine()
	        ->getRepository('AppBundle:Material')
	        ->retrieveArrayAllMaterials();

		$data = array("code" => 100, "success" => true, "materials" => $arrayOfAllMaterials);

		$response = new JsonResponse($data);
    	$response->setPublic();
	    $response->setMaxAge(self::CACHE_LIFETIME);
	    $response->headers->addCacheControlDirective('must-revalidate', true);
	    
    	return $response;
	}
}
