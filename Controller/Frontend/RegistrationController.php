<?php
namespace AppBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\User;
use AppBundle\Form\Frontend\UserType;

class RegistrationController extends Controller
{
	/**
	 * @Route("/registration/", name="registration")
	 */
	public function indexAction(Request $request)
	{
		$user = new User();
		$user->setRole('ROLE_USER');
		$userRegistrationForm = $this->createForm(new UserType(), $user);

		if ($request->getMethod() == 'POST') {
			$userRegistrationForm->submit($request);
			
			if ($userRegistrationForm->isValid()) {
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
				return $this->redirect($this->generateUrl('home'));
			}
		}

		return $this->render(
			'AppBundle:Frontend:registration.html.twig', array(
				'user_registration_form' => $userRegistrationForm->createView()
			)
		);
	}
}
