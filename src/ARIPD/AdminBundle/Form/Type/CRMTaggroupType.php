<?php
namespace ARIPD\AdminBundle\Form\Type;

use ARIPD\CRMBundle\Entity\Taggroup;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CRMTaggroupType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('name')->add('sorting');
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array(
				'data_class' => get_class(new Taggroup()),
		));
	}

	public function getName() {
		return 'aripd_crmbundle_taggrouptype';
	}
}
