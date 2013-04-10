<?php
namespace ARIPD\AdminBundle\Form\Type;
use ARIPD\CMSBundle\Entity\Topic;
use ARIPD\CMSBundle\Entity\Subtopic;
use ARIPD\AdminBundle\Entity\Iso639;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CMSTopicFormType extends AbstractType {
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
		$builder->add('sorting', 'integer', array('label' => 'Sorting',));
		$builder->add('hidden', 'checkbox', array('required' => false, 'label' => 'label.hidden',));
		$builder->add('template_topic', 'text', array('required' => false, 'label' => 'Topic Template File',));
		$builder->add('template_post', 'text', array('required' => false, 'label' => 'Post Template File',));
		$builder->add('groups', 'entity', array(
				'class' => 'ARIPD\UserBundle\Entity\Group',
				'property' => 'name',
				'query_builder' => function (EntityRepository $er) {
						return $er->createQueryBuilder('g')->where('g.approver = ?1')->setParameter(1, true);
				},
				'expanded' => false,
				'multiple' => true,
				'label' => 'Approver',
			)
		);
		$builder->add('subtopics', 'entity', array(
				'class' => get_class(new Subtopic()),
				'property' => 'name',
				'expanded' => false,
				'multiple' => true,
				'label' => 'Subtopics',
		));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
				array(
						'data_class' => get_class(new Topic()),
						'translation_domain' => 'ARIPDCMSBundle',
				)
		);
	}
	
	public function getName() {
		return 'aripdadmin_cmstopicformtype';
	}
	
}
