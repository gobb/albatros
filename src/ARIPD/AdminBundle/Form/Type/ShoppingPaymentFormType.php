<?php
namespace ARIPD\AdminBundle\Form\Type;
use ARIPD\ShoppingBundle\Entity\Payment;
use ARIPD\AdminBundle\Entity\Iso4217;
use ARIPD\ShoppingBundle\Entity\Paymenttype;
use ARIPD\CMSBundle\Entity\Subtopic;
use ARIPD\AdminBundle\Entity\Iso639;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ShoppingPaymentFormType extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('name', 'text', array('label' => 'Name', 'required' => true,));
		$builder->add('period', 'number', array('label' => 'Period', 'required' => true,));
		$builder->add('parameter', 'text', array('label' => 'Parameter', 'required' => true,));
		$builder->add('impactRate', 'percent', array('type' => 'fractional', 'precision' => 2, 'label' => 'Impact Rate', 'required' => false,));
		$builder->add('impactPrice', 'number', array('label' => 'Impact Price', 'required' => false,));
		$builder->add('iso4217', 'entity', array(
				'class' => get_class(new Iso4217()),
				'property' => 'name',
				'query_builder' => function (EntityRepository $er) {
						return $er->createQueryBuilder('i');//->where('i.active = ?1')->setParameter(1, true);
				},
				'required' => false,
			)
		);
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
				array(
						'data_class' => get_class(new Payment()),
						'translation_domain' => 'ARIPDShoppingBundle',
				)
		);
	}
	
	public function getName() {
		return 'aripdadmin_shoppingpaymentformtype';
	}
	
}
