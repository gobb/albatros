<?php
namespace ARIPD\UserBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class BlogCommentFormType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('content', 'textarea');
	}

	public function getName() {
		return 'aripduser_blogcommentformtype';
	}
}
