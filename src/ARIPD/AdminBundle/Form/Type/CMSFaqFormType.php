<?php
namespace ARIPD\AdminBundle\Form\Type;
use ARIPD\CMSBundle\Entity\Faq;
use ARIPD\AdminBundle\Entity\Iso639;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CMSFaqFormType extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('iso639', 'entity', array(
				'class' => get_class(new Iso639()),
				'property' => 'name',
				'query_builder' => function (EntityRepository $er) {
						return $er->createQueryBuilder('i');//->where('i.active = ?1')->setParameter(1, true);
				},
				'required' => true,
			)
		);
		$builder->add('name', 'text', array('label' => 'Name',));
		$builder->add('content', 'textarea', array('label' => 'Content',));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
				array(
						'data_class' => get_class(new Faq()),
						'translation_domain' => 'ARIPDCMSBundle',
				)
		);
	}
	
	public function getName() {
		return 'aripdadmin_cmsfaqformtype';
	}
	
}
