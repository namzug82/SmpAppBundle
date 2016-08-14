<?php
namespace AppBundle\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AdminUserType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
		->add('role', ChoiceType::class, array(
    		'choices'  => array(
        		'ROLE_ADMIN' => 'Administrador',
        		'ROLE_USER' => 'Usuario',
    		)))
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
		return 'smp_appbundle_newusertype';
	}
}
