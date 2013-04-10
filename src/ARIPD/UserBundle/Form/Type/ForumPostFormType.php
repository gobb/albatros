<?php
namespace ARIPD\UserBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ForumPostFormType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
				->add('content', 'textarea',
						array('label' => 'Content',
								'attr' => array('class' => 'redactor')));
	}

	public function getName() {
		return 'aripduser_forumpostformtype';
	}
}
