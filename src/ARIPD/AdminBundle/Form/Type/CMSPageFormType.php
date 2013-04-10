<?php
namespace ARIPD\AdminBundle\Form\Type;
use ARIPD\CMSBundle\Entity\Page;
use ARIPD\AdminBundle\Entity\Iso639;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CMSPageFormType extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('iso639', 'entity', array(
				'class' => get_class(new Iso639()),
				'property' => 'name',
				'query_builder' => function (EntityRepository $er) {
					//return $er->createQueryBuilder('g')->where('g.registrable = ?1')->setParameter(1, true);
					return $er->createQueryBuilder('i');
				},
				'required' => true,
			)
		);
		$builder
				->add('parent', 'entity',
						array('class' => get_class(new Page()),
								'empty_value' => 'Choose an option',
								'required' => false, 'label' => 'Parent',));
		$builder->add('name', 'text', array('label' => 'Name'));
		$builder
				->add('description', 'text',
						array('label' => 'Description'));
		$builder->add('content', 'textarea', array('label' => 'Content'));
		$builder->add('sorting', 'integer', array('label' => 'Sorting'));
		$builder->add('initial');
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
				array(
						'data_class' => get_class(new Page()),
						'translation_domain' => 'ARIPDCMSBundle',
				)
		);
	}
	
	public function getName() {
		return 'aripdadmin_cmspageformtype';
	}
	
}
