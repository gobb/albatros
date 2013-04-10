<?php
namespace ARIPD\AdminBundle\Form\Type;

use ARIPD\CRMBundle\Entity\Taggroup;

use ARIPD\CRMBundle\Entity\Tagkey;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CRMTagkeyType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('name')->add('sorting')
				->add('taggroup', 'entity',
						array('class' => get_class(new Taggroup()),
								'property' => 'name',));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array(
				'data_class' => get_class(new Tagkey()),
		));
	}

	public function getName() {
		return 'aripd_crmbundle_tagkeytype';
	}
}
