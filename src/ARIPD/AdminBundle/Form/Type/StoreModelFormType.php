<?php
namespace ARIPD\AdminBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use ARIPD\StoreBundle\Entity\Model;

use ARIPD\AdminBundle\Entity\Iso4217;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class StoreModelFormType extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('code', 'text', array('label' => 'Code'));
		$builder->add('price', 'money', array('label' => 'Price'));
		$builder->add('iso4217', 'entity', array(
				'class' => get_class(new Iso4217()),
				'property' => 'name',
				'query_builder' => function (EntityRepository $er) {
						return $er->createQueryBuilder('i');//->where('i.active = ?1')->setParameter(1, true);
				},
				'required' => true,
			)
		);
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
				array(
						'data_class' => get_class(new Model()),
						'translation_domain' => 'ARIPDStoreBundle',
				)
		);
	}
	
	public function getName() {
		return 'aripdadmin_storemodelformtype';
	}
	
}
