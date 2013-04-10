<?php
namespace ARIPD\UserBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CMSCommentFormType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
				->add('content', 'textarea');
	}

	public function getName() {
		return 'aripduser_cmscommentformtype';
	}
}
