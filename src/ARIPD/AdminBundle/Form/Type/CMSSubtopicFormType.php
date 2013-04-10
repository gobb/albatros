<?php
namespace ARIPD\AdminBundle\Form\Type;
use ARIPD\CMSBundle\Entity\Subtopic;
use ARIPD\CMSBundle\Entity\Topic;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CMSSubtopicFormType extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		
		$builder->add('topic', 'entity', array(
				'class' => get_class(new Topic()),
				'property' => 'name',
				'label' => 'Topic',
			)
		);
		$builder->add('name', 'text', array('label' => 'Name',));
		$builder->add('sorting', 'integer', array('label' => 'Sorting',));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
				array(
						'data_class' => get_class(new Subtopic()),
						'translation_domain' => 'ARIPDCMSBundle',
				)
		);
	}
	
	public function getName() {
		return 'aripdadmin_cmssubtopicformtype';
	}
	
}
