<?php
namespace ARIPD\AdminBundle\Form\Type;

use ARIPD\CMSBundle\Entity\Subtopic;
use ARIPD\AdminBundle\Entity\Iso639;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ShoppingPaymentgrouptypeFormType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('code', 'text', array('label' => 'Code',));
		$builder->add('name', 'text', array('label' => 'Name',));
		$builder->add('file', 'file', array('label' => 'label.file'));
	}

	public function getName() {
		return 'aripdadmin_shoppingpaymentgrouptypeformtype';
	}
}
