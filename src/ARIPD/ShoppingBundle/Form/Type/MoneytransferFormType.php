<?php
namespace ARIPD\ShoppingBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class MoneytransferFormType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
	}

	public function getName() {
		return 'aripdshopping_moneytransferformtype';
	}
}
