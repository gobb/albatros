<?php
namespace ARIPD\StoreBundle\Form;
use ARIPD\StoreBundle\Entity\Attributekey;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AttributevalueType extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('code');
		$builder->add('name');
		$builder->add('attributekey', 'entity', array(
				'class' => get_class(new Attributekey()),
				'property' => 'name',
				'required' => true,
			)
		);
	}

	public function getName() {
		return 'aripd_storebundle_attributevaluetype';
	}
	
}
