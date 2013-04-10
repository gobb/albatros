<?php
namespace ARIPD\AdminBundle\Form\Type;
use ARIPD\StoreBundle\Entity\Category;
use ARIPD\AdminBundle\Entity\Iso639;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class StoreCategoryFormType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('iso639', 'entity', array(
				'class' => get_class(new Iso639()),
				'property' => 'name',
				'query_builder' => function (EntityRepository $er) {
						return $er->createQueryBuilder('i')->where('i.active = ?1')->setParameter(1, true);
				},
				'required' => true,
			)
		);
		$builder
				->add('parent', 'entity',
						array('class' => get_class(new Category()),
								'property' => 'name',
								'empty_value' => 'Choose an option',
								'required' => false, 'label' => 'Parent',))
				->add('name', 'text', array('label' => 'Name'))
				->add('description', 'text', array('label' => 'Description','required' => false,))
				->add('content', 'textarea', array('label' => 'Content','required' => false,))
				->add('sorting', 'integer', array('label' => 'Sorting', 'required' => false,))
				->add('url');
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
				array(
						'data_class' => get_class(new Category()),
						'translation_domain' => 'ARIPDStoreBundle',
				)
		);
	}
	
	public function getName() {
		return 'aripdadmin_storecategoryformtype';
	}
	
}
