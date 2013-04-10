<?php
namespace ARIPD\AdminBundle\Form\Type;
use Doctrine\ORM\EntityRepository;
use ARIPD\AdminBundle\Entity\Iso4217;
use ARIPD\ShoppingBundle\Entity\Voucher;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ShoppingVoucherFormType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('name', 'text', array('label' => 'Name',));
		$builder->add('code', 'text', array('label' => 'Code',));
		$builder
				->add('impactRate', 'percent',
						array('label' => 'Impact Rate', 'required' => true,));
		$builder
				->add('impactPrice', 'money',
						array('label' => 'Impact Price', 'required' => true,
								'currency' => 'TRL',));
		$builder
				->add('iso4217', 'entity',
						array('class' => get_class(new Iso4217()),
								'property' => 'name',
								'query_builder' => function (
										EntityRepository $er) {
									return $er->createQueryBuilder('i');//->where('i.active = ?1')->setParameter(1, true);
								}, 'required' => true,));
		$builder
				->add('startingAt', 'datetime',
						array('date_widget' => 'single_text',
								'time_widget' => 'single_text',
								'label' => 'Starting At',));
		$builder
				->add('endingAt', 'datetime',
						array('date_widget' => 'single_text',
								'time_widget' => 'single_text',
								'label' => 'Ending At',));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver
				->setDefaults(
						array('data_class' => get_class(new Voucher()),
								'translation_domain' => 'ARIPDShoppingBundle',));
	}

	public function getName() {
		return 'aripdadmin_shoppingvoucherformtype';
	}

}
