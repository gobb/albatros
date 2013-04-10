<?php
namespace ARIPD\UserBundle\Form\Type;
use ARIPD\CMSBundle\Entity\Subtopic;
use ARIPD\CMSBundle\Entity\Topic;
use ARIPD\CMSBundle\Entity\Post;
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

		$builder->add('name', 'text', array('label' => 'Name',));
		$builder
				->add('description', 'text',
						array('label' => 'description',));
		$builder
				->add('content', 'textarea', array('label' => 'Content',));
		$builder
				->add('video', 'text',
						array('required' => false, 'label' => 'Video',));
		
		
		/* TODO topic seçmesine gerek yok.
		 * subtopic->getTopic() ile entity içinden halledilir.
		 * Topic ve subtopic seçimini editöre bırakalım
		 */
		
		/*
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
				->add('subtopic', 'entity',
						array('class' => get_class(new Subtopic()),
								'property' => 'name',
								'query_builder' => function (
										EntityRepository $er) use ($locale) {
									return $er->createQueryBuilder('s')
											->orderBy('s.sorting')
											->addOrderBy('s.name');
								}, 'required' => true,
								'label' => 'label.subtopic',));
		*/
		$builder
				->add('tags', 'collection',
						array('type' => new CMSPostTagFormType(),
								'allow_add' => true, 'allow_delete' => true,
								'by_reference' => false,));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array(
				'data_class' => get_class(new Post()),
				'translation_domain' => 'ARIPDCMSBundle',
		));
	}

	public function getName() {
		return 'aripduser_cmspostformtype';
	}
}
