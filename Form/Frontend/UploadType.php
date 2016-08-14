<?php
namespace AppBundle\Form\Frontend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use AppBundle\Form\Frontend\UploadType;

class UploadType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('filename', 'text', array( 'label' => 'File Name :'));
        $builder->add('attachment', 'file');
	}

	public function getName()
	{
		return 'smp_appbundle_uploadtype';
	}
}
