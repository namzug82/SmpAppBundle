<?php
namespace AppBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
	const CACHE_LIFETIME = 1;

	/**
	 * @Route("/", name="home")
	 */
	public function indexAction(Request $request)
	{
		$authenticationUtils = $this->get('security.authentication_utils');

	    // get the login error if there is one
	    $error = $authenticationUtils->getLastAuthenticationError();

	    $materials = $this->getDoctrine()
	        ->getRepository('AppBundle:Material')
	        ->findAll();

	    $response = new Response();
	    $response->setPublic();
	    $response->setMaxAge(self::CACHE_LIFETIME);
	    $response->headers->addCacheControlDirective('must-revalidate', true);

	    return $this->render(
	        'AppBundle:Frontend:home.html.twig', array(
	            'materials' 		=> $materials,
	            'error'         => $error,
	    ), $response);
	}

	/**
	 * @Route("/panel/", name="panel")
	 */
	public function panelAction(Request $request)
	{
		$authenticationUtils = $this->get('security.authentication_utils');

	    // get the login error if there is one
	    $error = $authenticationUtils->getLastAuthenticationError();

	    return $this->render(
	        'AppBundle:Frontend:panel.html.twig', array(
	            'error'         => $error,
	    ));
	}
}
