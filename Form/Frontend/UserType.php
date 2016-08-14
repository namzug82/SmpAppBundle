<?php
namespace AppBundle\Form\Frontend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('name')
			->add('surname')
			->add('email', 'email')
			->add('password', 'repeated', array(
			'type' => 'password',
			'invalid_message' => 'Las dos contraseñas deben coincidir',
			'options' => array('label' => 'Contraseña')
			))
			->add('address')
			->add('allows_email', 'checkbox', array('required' => false))
			->add('birthdate', 'birthday')
			->add('dni')
		;
	}

	public function getName()
	{
		return 'smp_appbundle_usertype';
	}
}
