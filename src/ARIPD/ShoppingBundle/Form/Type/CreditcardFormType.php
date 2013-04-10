<?php
namespace ARIPD\ShoppingBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CreditcardFormType extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('ccno', 'text', array('label' => 'Credit Card Number',));
		$builder->add('cvc', 'number', array('label' => 'Security Number',));
		$builder->add('expdateYear', 'choice',
				array(
						'required' => true,
						'choices' => $this->buildYearChoices(),
				)
		);
		$builder->add('expdateMonth', 'choice',
				array(
						'required' => true,
						'choices' => $this->buildMonthChoices(),
				)
		);
		$builder->add('amount', 'number', array('label' => 'Total Amount',));
	}

	public function getName() {
		return 'aripdshopping_creditcardformtype';
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
				array(
						'translation_domain' => 'ARIPDShoppingBundle',
				)
		);
	}
	
	private function buildYearChoices() {
		$distance = 5;
		$yearsBefore = date('Y', mktime(0, 0, 0, date("m"), date("d"), date("Y")));
		$yearsAfter = date('Y', mktime(0, 0, 0, date("m"), date("d"), date("Y") + $distance));
		return array_combine(range($yearsBefore, $yearsAfter), range($yearsBefore, $yearsAfter));
	}
	
	private function buildMonthChoices() {
		return array(
				'01'=>'01',
				'02'=>'02',
				'03'=>'03',
				'04'=>'04',
				'05'=>'05',
				'06'=>'06',
				'07'=>'07',
				'08'=>'08',
				'09'=>'09',
				'10'=>'10',
				'11'=>'11',
				'12'=>'12',
		);
	}
	
}
