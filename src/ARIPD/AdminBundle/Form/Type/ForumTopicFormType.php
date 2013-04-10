<?php
namespace ARIPD\AdminBundle\Form\Type;
use ARIPD\AdminBundle\Entity\Iso639;
use ARIPD\ForumBundle\Entity\Topic;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ForumTopicFormType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
				->add('parent', 'entity',
						array('class' => get_class(new Topic()),
								'property' => 'name',
								'empty_value' => 'Choose an option',
								'required' => false, 'label' => 'Parent',))
				->add('iso639', 'entity',
						array('class' => get_class(new Iso639()),
								'property' => 'name',
								'label' => 'label.iso639'))
				->add('name', 'text', array('label' => 'Name'))
				->add('sorting', 'integer', array('label' => 'Sorting'));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
				array(
						'data_class' => get_class(new Topic()),
						'translation_domain' => 'ARIPDForumBundle',
				)
		);
	}
	
	public function getName() {
		return 'aripdadmin_forumtopicformtype';
	}
}
