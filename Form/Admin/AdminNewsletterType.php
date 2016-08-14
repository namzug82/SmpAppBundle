<?php
namespace AppBundle\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class AdminNewsletterType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('recipients', CollectionType::class, array(
		    // each entry in the array will be an "email" field
		    'entry_type'   => EmailType::class,
		    // these options are passed to each "email" type
		    'entry_options'  => array(
		        'attr'      => array('class' => 'email-box')
		    ),
		))
		->add('subject')
		->add('content', CKEditorType::class, array(
			'config' => array(
			    'uiColor' => '#ffffff'
		)));
	}

	public function getName()
	{
		return 'smp_appbundle_newslettertype';
	}
}
