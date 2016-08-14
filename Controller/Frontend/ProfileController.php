<?php
namespace AppBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\User;
use AppBundle\Form\Frontend\UserType;

class ProfileController extends Controller
{
	/**
	 * @Route("/profile/", name="profile")
	 */
	public function indexAction(Request $request)
	{
		$user = $this->get('security.context')->getToken()->getUser();
		$form = $this->createForm(new UserType(), $user);

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
				$em->persist($user);
				$em->flush();

				$this->addFlash('info',
					'Los datos de tu perfil se han actualizado correctamente'
				);
				return $this->redirect($this->generateUrl('home'));
			}
		}
		return $this->render(
			'AppBundle:Frontend:profile.html.twig',	array(
				'user_profile_form' => $form->createView())
		);
	}
}
