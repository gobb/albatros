<?php
namespace ARIPD\AdminBundle\Form\Type;
use ARIPD\SurveyBundle\Entity\Survey;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SurveySurveyType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('name', 'text', array('label' => 'Name'));
		$builder->add('description', 'textarea', array('label' => 'Description'));
		$builder
				->add('startingAt', 'datetime',
						array('date_widget' => 'single_text',
								'time_widget' => 'single_text',
								'label' => 'Starting At',));
		$builder
				->add('endingAt', 'datetime',
						array('date_widget' => 'single_text',
								'time_widget' => 'single_text',
								'label' => 'Ending At',));
		$builder->add('questions', 'collection', array(
				'type' => new SurveyQuestionType(),
				'allow_add' => true,
				'allow_delete' => true,
				'by_reference' => false,
				'label' => 'Questions',
			)
		);
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
				array(
						'data_class' => get_class(new Survey()),
						'translation_domain' => 'ARIPDSurveyBundle',
				)
		);
	}
	
	public function getName() {
		return 'aripdadmin_surveysurveytype';
	}
	
}
