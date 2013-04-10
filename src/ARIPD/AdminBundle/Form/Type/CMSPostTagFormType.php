<?php
namespace ARIPD\AdminBundle\Form\Type;
use ARIPD\CMSBundle\Entity\Tag;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CMSPostTagFormType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('name');
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array(
				'data_class' => get_class(new Tag()),
		));
	}

	public function getName() {
		return 'aripdadmin_cmsposttagformtype';
	}
}
