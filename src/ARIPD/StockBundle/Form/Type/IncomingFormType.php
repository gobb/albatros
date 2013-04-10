<?php
namespace ARIPD\StockBundle\Form\Type;
use ARIPD\StockBundle\Form\DataTransformer\ModelToCodeTransformer;
use ARIPD\StockBundle\Entity\Incoming;
use ARIPD\StoreBundle\Entity\Model;
use Doctrine\ORM\EntityRepository;
use ARIPD\AdminBundle\Entity\Iso4217;
use ARIPD\SCMBundle\Entity\Supplier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IncomingFormType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options) {

		// this assumes that the entity manager was passed in as an option
		$entityManager = $options['em'];
		$transformer = new ModelToCodeTransformer($entityManager);

		$builder
				->add('invoicedAt', 'date',
						array('label' => 'Invoiced At', 'required' => true,));
		$builder
				->add('supplier', 'entity',
						array('class' => get_class(new Supplier()),
								'property' => 'name', 'required' => true,
								'label' => 'Supplier',));
		/*
		$builder
		    ->add('model', 'entity',
		        array('class' => get_class(new Model()),
		            'property' => 'code', 'required' => true,
		            'label' => 'Model',));
		 */
		// add a normal text field, but add your transformer to it
		$builder
				->add(
						$builder->create('model', 'text')
								->addModelTransformer($transformer));

		$builder
				->add('quantity', 'number',
						array('label' => 'Quantity', 'required' => true,));
		$builder
				->add('price', 'money',
						array('label' => 'Price', 'required' => true,));
		$builder
				->add('iso4217', 'entity',
						array('class' => get_class(new Iso4217()),
								'property' => 'name',
								'query_builder' => function (
										EntityRepository $er) {
									return $er->createQueryBuilder('i');//->where('i.active = ?1')->setParameter(1, true);
								}, 'required' => true,
								'label' => 'label.iso4217'));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {

		$resolver
				->setDefaults(
						array('data_class' => get_class(new Incoming()),
								'translation_domain' => 'ARIPDStockBundle',));

		$resolver->setRequired(array('em',));

		$resolver
				->setAllowedTypes(
						array(
								'em' => 'Doctrine\Common\Persistence\ObjectManager',));

	}

	public function getName() {
		return 'aripd_stockbundle_incomingformtype';
	}

}
