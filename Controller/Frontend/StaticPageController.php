<?php
namespace AppBundle\Controller\Frontend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StaticPageController extends Controller
{
	/**
     * @Route("/{page}/", name="frontend_static_page")
     */
	public function indexAction($page)
	{
    	try {
			return $this->get('templating')->render('AppBundle:Frontend:'.$page.'.html.twig');
		} catch (\Exception $e) {
		    return $this->render('Exception/error404.html.twig');
		}
	}
}
