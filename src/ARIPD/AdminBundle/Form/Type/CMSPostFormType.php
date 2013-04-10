<?php
namespace ARIPD\AdminBundle\Form\Type;
use ARIPD\SurveyBundle\Entity\Survey;
use ARIPD\UserBundle\Entity\User;
use ARIPD\CMSBundle\Entity\Topic;
use ARIPD\CMSBundle\Entity\Post;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CMSPostFormType extends AbstractType {

	private $locale;

	public function __construct($locale) {
		$this->locale = $locale;
	}

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$locale = $this->locale;

		$builder
				->add('publishedAt', 'datetime',
						array('date_widget' => 'single_text',
								'time_widget' => 'single_text',
								'label' => 'Published At',));
		
		$builder->add('approved', 'checkbox', array('required' => false, 'label' => 'label.approved'));
		$builder->add('name', 'text', array('label' => 'Name',));
		$builder
				->add('description', 'text',
						array('label' => 'Description',));
		$builder
				->add('content', 'textarea', array('label' => 'Content',));
		$builder
				->add('video', 'text',
						array('required' => false, 'label' => 'Video'));
		$builder
				->add('topic', 'entity',
						array('class' => get_class(new Topic()),
								'property' => 'name',
								'query_builder' => function (
										EntityRepository $er) use ($locale) {
									return $er->createQueryBuilder('t')
											->leftJoin('t.iso639', 'i')
											->where('i.a2 = ?1')
											->setParameter(1, $locale)
											->andWhere('t.hidden = ?2')
											->setParameter(2, false);
								}, 'required' => true,
								'label' => 'Topic',));

		$builder
				->add('user', 'entity',
						array('class' => get_class(new User()),
								'property' => 'fullname',
								'query_builder' => function (
										EntityRepository $er) {
									return $er->createQueryBuilder('u')
											->select(array('u', 'g'))
											->leftJoin('u.groups', 'g')
											->where('g.writer = ?1')
											->setParameter(1, true);
								}, 'required' => true, 'label' => 'User',));

		$builder
				->add('survey', 'entity',
						array('class' => get_class(new Survey()),
								'property' => 'name',
								'empty_value' => 'No survey',
								'required' => false, 'label' => 'Survey',));

		$builder
				->add('tags', 'collection',
						array('type' => new CMSPostTagFormType(),
								'allow_add' => true, 'allow_delete' => true,
								'by_reference' => false,
								'label' => 'Tag',));

	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array(
				'data_class' => get_class(new Post()),
				'translation_domain' => 'ARIPDCMSBundle',
		));
	}

	public function getName() {
		return 'aripdadmin_cmspostformtype';
	}
	
}
