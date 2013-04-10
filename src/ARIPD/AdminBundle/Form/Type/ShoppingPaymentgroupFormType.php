<?php
namespace ARIPD\AdminBundle\Form\Type;
use ARIPD\ShoppingBundle\Entity\Paymentgroup;
use ARIPD\AdminBundle\Entity\Iso4217;
use ARIPD\ShoppingBundle\Entity\Paymentgrouptype;
use ARIPD\CMSBundle\Entity\Subtopic;
use ARIPD\AdminBundle\Entity\Iso639;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ShoppingPaymentgroupFormType extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('paymentgrouptype', 'entity', array(
				'class' => get_class(new Paymentgrouptype()),
				'property' => 'name',
				'required' => true,
		));
		$builder->add('name', 'text', array('label' => 'Name', 'required' => true,));
		$builder->add('iso4217', 'entity', array(
				'class' => get_class(new Iso4217()),
				'property' => 'name',
				'query_builder' => function (EntityRepository $er) {
						return $er->createQueryBuilder('i');//->where('i.active = ?1')->setParameter(1, true);
				},
				'required' => true,
			)
		);
		$builder->add('content', 'textarea', array('label' => 'Content', 'required' => false,));
		$builder->add('gate1', 'text', array('label' => 'label.gate1', 'required' => false,));
		$builder->add('gate2', 'text', array('label' => 'label.gate2', 'required' => false,));
		$builder->add('mid', 'text', array('label' => 'label.mid', 'required' => false,));
		$builder->add('tid', 'text', array('label' => 'label.tid', 'required' => false,));
		$builder->add('posid', 'text', array('label' => 'label.posid', 'required' => false,));
		$builder->add('clientid', 'text', array('label' => 'label.clientid', 'required' => false,));
		$builder->add('storetype', 'text', array('label' => 'label.storetype', 'required' => false,));
		$builder->add('storekey', 'text', array('label' => 'label.storekey', 'required' => false,));
		$builder->add('file', 'file', array('label' => 'label.file', 'required' => false,));
		$builder->add('active', 'checkbox', array('label' => 'label.active', 'required' => false,));
		$builder->add('transportations', 'entity', array(
				'class' => 'ARIPD\ShoppingBundle\Entity\Transportation',
				'property' => 'name',
				'query_builder' => function (EntityRepository $er) {
						return $er->createQueryBuilder('t')/*->where('g.approver = ?1')->setParameter(1, true)*/;
				},
				'expanded' => false,
				'multiple' => true,
				'required' => true,
				)
		);
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
				array(
						'data_class' => get_class(new Paymentgroup()),
						'translation_domain' => 'ARIPDShoppingBundle',
				)
		);
	}
	
	public function getName() {
		return 'aripdadmin_shoppingpaymentgroupformtype';
	}
	
}
