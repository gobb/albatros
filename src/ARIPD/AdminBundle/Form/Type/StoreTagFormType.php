<?php
namespace ARIPD\AdminBundle\Form\Type;
use ARIPD\StoreBundle\Entity\Tag;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class StoreTagFormType extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('name', 'text', array('label' => ' ', 'attr'=>array('placeholder'=>'Tag',),));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array(
				'data_class' => get_class(new Tag()),
				'translation_domain' => 'ARIPDStoreBundle',
		));
	}

	public function getName() {
		return 'aripdadmin_storetagformtype';
	}
}
