<?php
namespace SmpBundle\Form\Frontend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use SmpBundle\Form\Frontend\UploadType;

class UploadType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('filename', 'text', array( 'label' => 'File Name :'));
        $builder->add('attachment', 'file');
	}

	public function getName()
	{
		return 'smp_SmpBundle_uploadtype';
	}
}
