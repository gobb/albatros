<?php
namespace ARIPD\UserBundle\Form\Type;

use Doctrine\ORM\Query;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;
use Doctrine\ORM\EntityRepository;

class RegistrationFormType extends BaseType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		//$builder
		//->add('firstname', 'text', array('required'=>true,))
		//->add('lastname', 'text', array('required'=>true,));

		parent::buildForm($builder, $options);

		$builder->add('captcha', 'captcha', array('label' => 'Security Code', 'attr' => array('class' => 'span1',)));
		
		// add your custom field
		//$builder->add('groups');
		//$builder->add('groups', 'collection', array('type' => new GroupType()));
		/*
		$builder->add('groups', 'entity', array(
		    'class' => 'ARIPD\UserBundle\Entity\Group',
		    'property' => 'name',
		    'query_builder' => function(EntityRepository $er) {
		      return $er->createQueryBuilder('g')->where('g.registrable = ?1')->setParameter(1, true);
		    },
		    'expanded' => true,
		    'multiple' => true,
		    'label' => 'Group',
		  )
		);
		 */

	}

	public function getName() {
		return 'aripduser_registrationformtype';
	}
}
