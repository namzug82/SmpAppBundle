<?php
namespace SmpBundle\Controller\Frontend;

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
			return $this->get('templating')->render('SmpBundle:Frontend:'.$page.'.html.twig');
		} catch (\Exception $e) {
		    return $this->render('Exception/error404.html.twig');
		}
	}
}
