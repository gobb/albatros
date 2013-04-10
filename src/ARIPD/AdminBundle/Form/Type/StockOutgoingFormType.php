<?php
namespace ARIPD\AdminBundle\Form\Type;
use Doctrine\ORM\EntityRepository;

use ARIPD\AdminBundle\Entity\Iso4217;

use ARIPD\StoreBundle\Entity\Model;

use ARIPD\StockBundle\Entity\Outgoing;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class StockOutgoingFormType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('model', 'entity',
						array(
								'class' => get_class(new Model()),
								'property' => 'code',
								'multiple' => false,
								'expanded' => false,
						)
		);
		$builder->add('quantity');
		$builder->add('price');
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
		$resolver->setDefaults(array(
				'data_class' => get_class(new Outgoing()),
		));
	}

	public function getName() {
		return 'aripdadmin_stockoutgoingformtype';
	}
}
