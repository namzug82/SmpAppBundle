<?php
namespace AppBundle\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class AdminMaterialType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('name')
			->add('slug', 'text', array(
			    'required'    => false,
			    'empty_data'  => null
				))
			->add('symbol')
			->add('description', CKEditorType::class, array(
			    'config' => array(
			        'uiColor' => '#ffffff'
			    )))
			->add('image_1', FileType::class, array(
				'label' => 'Imagen Principal (500x500px)',
				'required'    => false,
			    'empty_data'  => null
				))
			->add('image_2', FileType::class, array(
				'label' => 'Imagen Secundaria (242x200px)',
				'required'    => false,
			    'empty_data'  => null
			));
	}

	public function getName()
	{
		return 'smp_appbundle_newmaterialtype';
	}
}
