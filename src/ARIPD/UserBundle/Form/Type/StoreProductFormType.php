<?php
namespace ARIPD\UserBundle\Form\Type;
use ARIPD\StoreBundle\Entity\Product;
use ARIPD\StoreBundle\Entity\Category;
use ARIPD\StoreBundle\Entity\Brand;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class StoreProductFormType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('code', 'text', array('label' => 'Code'))
				->add('name', 'text', array('label' => 'Name'))
				->add('price', 'money', array('label' => 'Price'))
				->add('description', 'textarea',
						array('label' => 'Description'))
				->add('video', 'text', array('label' => 'Video'))
				->add('brand', 'entity',
						array('class' => get_class(new Brand()),
								'property' => 'name',
								'empty_value' => 'Choose an option',
								'required' => true, 'label' => 'Brand',))
				->add('defaultcategory', 'entity',
						array('class' => get_class(new Category()),
								'property' => 'name',
								'empty_value' => 'Choose an option',
								'required' => true,
								'label' => 'Category',));
		$builder
				->add('categories', 'entity',
						array('class' => get_class(new Category()),
								'property' => 'name',
								'query_builder' => function (EntityRepository $er) {
									return $er->createQueryBuilder('c');//->where('g.registrable = ?1')->setParameter(1, true);
								}, 'expanded' => false, 'multiple' => true,
								'label' => 'Categories',));
		$builder
				->add('tags', 'collection',
						array('type' => new StoreProductTagFormType(),
								'allow_add' => true, 'allow_delete' => true,
								'by_reference' => false,));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
				array(
						'data_class' => get_class(new Product()),
						'translation_domain' => 'ARIPDStoreBundle',
				)
		);
	}
	
	public function getName() {
		return 'aripduser_storeproductformtype';
	}
	
}
