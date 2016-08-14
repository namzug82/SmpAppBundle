<?php
namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\HttpCache\HttpCache;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AdminCacheController extends Controller
{
    /**
     * @Route("/admin/clear_cache" , name="admin_clear_cache")
     * @Template()
     */
    public function clearCacheAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('url')
            ->getForm();
     
        if ($request->isMethod("POST")) {
            $form->bind($request);
     
            if ($form->isValid()) {
                $url = $form->get('url')->getData();
     
                $httpKernel = $this->container->get('cache');
     
                if ($httpKernel instanceof HttpCache) {
                    if ($httpKernel->getStore()->purge($url)) {
                        $this->get('session')->setFlash('success', sprintf('Cache for %s successfully removed', $url));
                    } else {
                        $this->get('session')->setFlash('error', sprintf('Cache for %s not found', $url));
                    }
                }
     
                return $this->redirect($this->generateUrl('admin_clear_cache'));
            }
        }
     
        return $this->render('AppBundle:Admin:admin_clear_cache.html.twig', array(
            'admin_cache_form' => $form->createView(),
        ));
    }
}