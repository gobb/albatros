<?php
namespace ARIPD\UserBundle\Form\Type;
use ARIPD\ForumBundle\Entity\Post;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ForumThreadFormType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('name', 'text', array('label' => 'Name'));
		$builder->add('posts', 'collection', array(
				'type' => new ForumPostFormType(),
				'allow_add' => true,
				'prototype' => true,
				'prototype_name' => 1,
				'by_reference' => false,
				'options' => array('data_class' => get_class(new Post())),
			)
		);
	}

	public function getName() {
		return 'aripduser_forumthreadformtype';
	}
}
