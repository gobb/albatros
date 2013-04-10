<?php
namespace ARIPD\AdminBundle\Form\Type;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use ARIPD\UserBundle\Entity\Purchaseorder;

use ARIPD\ShoppingBundle\Entity\Payment;
use ARIPD\ShoppingBundle\Entity\Transportation;
use ARIPD\ShoppingBundle\Entity\Purchaseorderstatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserPurchaseorderFormType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		
		/**
		 * Bu da çalışıyor
		 */
		//$builder->add('user', new UserUserFormType());
		
		/*
		$builder->add('user', 'collection',
						array(
								'type' => new UserUserFormType(),
								'allow_add' => true,
								'allow_delete' => false,
								'by_reference' => false,
						)
		);
		*/
		$builder->add('status', 'entity',
						array(
								'class' => get_class(new Purchaseorderstatus()),
								'property' => 'name',
								'multiple' => false,
								'expanded' => false,
						)
		);
		$builder->add('payment', 'entity',
						array(
								'class' => get_class(new Payment()),
								'property' => 'fullname',
								'multiple' => false,
								'expanded' => false,
						)
		);
		$builder->add('transportation', 'entity',
						array(
								'class' => get_class(new Transportation()),
								'property' => 'name',
								'multiple' => false,
								'expanded' => false,
						)
		);
		$builder->add('outgoings', 'collection',
						array(
								'type' => new StockOutgoingFormType(),
								'allow_add' => true,
								'by_reference' => false,
						)
		);
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array(
				'data_class' => get_class(new Purchaseorder()),
		));
	}
	
	public function getName() {
		return 'aripd_admin_userpurchaseorderformtype';
	}
}
