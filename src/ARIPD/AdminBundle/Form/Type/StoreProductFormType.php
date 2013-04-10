<?php
namespace ARIPD\AdminBundle\Form\Type;
use ARIPD\StoreBundle\Entity\Attributekey;
use ARIPD\UserBundle\Entity\User;
use ARIPD\StoreBundle\Entity\Product;
use ARIPD\StoreBundle\Entity\Category;
use ARIPD\StoreBundle\Entity\Model;
use ARIPD\StoreBundle\Entity\Brand;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class StoreProductFormType extends AbstractType {

	private $locale;
	private $product;

	public function __construct($locale,
			\ARIPD\StoreBundle\Entity\Product $product) {
		$this->locale = $locale;
		$this->product = $product;
	}

	public function buildForm(FormBuilderInterface $builder, array $options) {

		$locale = $this->locale;
		$product = $this->product;

		$builder
				->add('approved', 'checkbox',
						array('required' => false, 'label' => 'Is it approved?',));
		$builder
				->add('vitrine', 'checkbox',
						array('required' => false,
								'label' => 'Do you want to exhibit in the showcase?',));
		$builder
				->add('brandnew', 'checkbox',
						array('required' => false,
								'label' => 'Is it a new product?',));

		$builder
				->add('publishedAt', 'datetime',
						array('date_widget' => 'single_text',
								'time_widget' => 'single_text',
								'label' => 'Published At',));

		/*$builder
		    ->add('user', 'entity',
		        array('class' => get_class(new User()),
		            'property' => 'fullname',
		            'query_builder' => function (
		                EntityRepository $er) {
		              return $er->createQueryBuilder('u')
		                  ->select(array('u', 'g'))
		                  ->leftJoin('u.groups', 'g')
		                  ->where('g.writer = ?1')
		                  ->setParameter(1, true);
		            }, 'required' => false, 'label' => 'User',));*/

		$builder->add('code');
		$builder->add('name');
		$builder->add('description', 'textarea', array('required' => false,));
		$builder->add('content', 'textarea', array('label' => 'Content'));
		$builder->add('video', 'textarea', array('required' => false,));
		$builder
				->add('brand', 'entity',
						array('class' => get_class(new Brand()),
								'property' => 'name',
								'empty_value' => 'Choose an option',
								'required' => true, 'label' => 'Brand',));
		$builder
				->add('defaultcategory', 'entity',
						array('class' => get_class(new Category()),
								'property' => 'name',
								'query_builder' => function (
										EntityRepository $er) use ($locale) {
									return $er->createQueryBuilder('c')
											->leftJoin('c.iso639', 'i')
											->where('i.a2 = ?1')
											->setParameter(1, $locale);
								}, 'empty_value' => 'Choose an option',
								'required' => true,
								'label' => 'Default Category',));
		$builder
				->add('categories', 'entity',
						array('class' => get_class(new Category()),
								'property' => 'name',
								'query_builder' => function (
										EntityRepository $er) use ($locale) {
									return $er->createQueryBuilder('c')
											->leftJoin('c.iso639', 'i')
											->where('i.a2 = ?1')
											->setParameter(1, $locale);
								}, 'expanded' => false, 'multiple' => true,
								'required' => true, 'label' => 'Categories',));
		$builder
				->add('attributekeys', 'entity',
						array('class' => get_class(new Attributekey()),
								'property' => 'name', 'expanded' => false,
								'multiple' => true,
								'label' => 'Attribute names',));
		$builder
				->add('tags', 'collection',
						array('label' => ' ', 'type' => new StoreTagFormType(),
								'allow_add' => true, 'allow_delete' => true,
								'by_reference' => false,));
		$builder
				->add('myRecommends', 'entity',
						array('class' => get_class(new Product()),
								'property' => 'name', 'expanded' => false,
								'multiple' => true, 'required' => false,
								'label' => 'Recommended products with this product',));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver
				->setDefaults(
						array('data_class' => get_class(new Product()),
								'translation_domain' => 'ARIPDStoreBundle',));
	}

	public function getName() {
		return 'aripdadmin_storeproductformtype';
	}

}
