<?php
namespace ARIPD\AdminBundle\Form\Type;
use ARIPD\BlogBundle\Entity\Post;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BlogPostFormType extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('name', 'text', array('label' => 'Name',));
		$builder
				->add('content', 'textarea', array('label' => 'Content',));
		$builder
				->add('tags', 'collection',
						array('type' => new BlogPostTagFormType(),
								'allow_add' => true, 'allow_delete' => true,
								'by_reference' => false,));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(
				array(
						'data_class' => get_class(new Post()),
						'translation_domain' => 'ARIPDBlogBundle',
				)
		);
	}
	
	public function getName() {
		return 'aripdadmin_blogpostformtype';
	}
	
}
