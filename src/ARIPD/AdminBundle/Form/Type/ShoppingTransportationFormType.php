<?php
namespace ARIPD\AdminBundle\Form\Type;
use ARIPD\ShoppingBundle\Entity\Transportation;
use ARIPD\AdminBundle\Entity\Iso4217;
use ARIPD\ShoppingBundle\Entity\Transportationtype;
use ARIPD\CMSBundle\Entity\Subtopic;
use ARIPD\AdminBundle\Entity\Iso639;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ShoppingTransportationFormType extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('name', 'text', array('label' => 'Name', 'required' => true,));
		$builder->add('description', 'textarea');
		$builder->add('impactPrice', 'number', array('label' => 'Impact Price', 'required' => true,));
		$builder->add('iso4217', 'entity', array(
				'class' => get_class(new Iso4217()),
				'property' => 'name',
				'query_builder' => function (EntityRepository $er) {
						return $er->createQueryBuilder('i');//->where('i.active = ?1')->setParameter(1, true);
				},
				'required' => true,
			)
		);
		$builder->add('file', 'file', array('label' => 'label.file'));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
				array(
						'data_class' => get_class(new Transportation()),
						'translation_domain' => 'ARIPDShoppingBundle',
				)
		);
	}
	
	public function getName() {
		return 'aripdadmin_shoppingtransportationformtype';
	}
	
}
