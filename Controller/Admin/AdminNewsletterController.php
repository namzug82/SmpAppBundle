<?php
namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Form\Admin\AdminNewsletterType;
use AppBundle\Entity\Newsletter;


class AdminNewsletterController extends Controller
{

	/**
     * @Route("/admin/newsletter/", name="admin_newsletter")
     */
	public function indexAction(Request $request)
	{   
        $recipients = $this->get('target_user_retriever')->retrieveUserEmailsInterestedByMaterial($request->query->get('term'));

        $newsletter = new Newsletter();
        $newsletter->setRecipients($recipients);
        $form = $this->createForm(new AdminNewsletterType(), $newsletter);

        if ($request->getMethod() == 'POST') {
            $form->submit($request);
            if ($form->isValid()) {
                $message = \Swift_Message::newInstance()
                    ->setContentType("text/html")
                    ->setSubject($newsletter->getSubject())
                    ->setFrom('gabriel.strato@gmail.com')
                    ->setTo('gabriel.strato@gmail.com')
                    ->setBody($newsletter->getContent())
                ;
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($newsletter);
                $em->flush();
                $this->get('mailer')->send($message);
                return $this->redirect($this->generateUrl('admin_newsletter'));
            }
        }

        return $this->render('AppBundle:Admin:admin_newsletter.html.twig', array(
                'admin_newsletter_form' => $form->createView(),
        ));
	}
}