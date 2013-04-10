<?php
namespace ARIPD\AdminBundle\Form\Type;
use ARIPD\SurveyBundle\Entity\Question;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SurveyQuestionType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('name', 'text', array('label' => 'label.question'));
		$builder
				->add('answers', 'collection',
						array('type' => new SurveyAnswerType(),
								'allow_add' => true, 'allow_delete' => true,
								'by_reference' => false,
								'label' => 'label.answer',));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver
				->setDefaults(
						array('data_class' => get_class(new Question()),));
	}

	public function getName() {
		return 'aripdadmin_surveyquestiontype';
	}
}
