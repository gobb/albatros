<?php
namespace ARIPD\AdminBundle\Twig\Extension;

use Symfony\Component\Finder\Finder;

use Symfony\Component\DomCrawler\Crawler;

use ARIPD\AdminBundle\Util\ARIPDVideo;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Gregwar\ImageBundle\GregwarImageBundle;

use Gregwar\ImageBundle\DependencyInjection\GregwarImageExtension;

use Ornicar\GravatarBundle\GravatarApi;

use ARIPD\AdminBundle\Util\ARIPDString;

use Twig_Extension;
use Twig_Filter_Method;
use Twig_Filter_Function;
use Twig_Function_Function;
use Twig_Function_Method;

use NumberFormatter;
use Locale;


class VisualExtension extends Twig_Extension {
	
	protected $container;
	protected $generator;
	
	private $localGenerator = false;
	
	private function uploadDirectory() {
		return $this->container->get('kernel')->getRootDir() . '/../web/uploads/_site/';
	}
	
	public function __construct(ContainerInterface $container, UrlGeneratorInterface $generator) {
		$this->container = $container;
		$this->generator = $generator;
	}
	
	public function getFunctions() {
		return array(
				'lipsum'							=> new Twig_Function_Method($this, 'generateLipsum', array('is_safe' => array('html'))),
				'amazonImage'					=> new Twig_Function_Method($this, 'generateAmazonImage', array('is_safe' => array('html'))),
				'profileImage'				=> new Twig_Function_Method($this, 'generateProfileImage', array('is_safe' => array('html'))),
				'postImage'						=> new Twig_Function_Method($this, 'generatePostImage', array('is_safe' => array('html'))),
				'entityImage'					=> new Twig_Function_Method($this, 'generateEntityImage', array('is_safe' => array('html'))),
				'generateImage'				=> new Twig_Function_Method($this, 'generateImage', array('is_safe' => array('html'))),
				'crmIndividualImage'	=> new Twig_Function_Method($this, 'generateCRMIndividualImage', array('is_safe' => array('html'))),
				'grabImage'						=> new Twig_Function_Method($this, 'grabImage', array('is_safe' => array('html'))),
		);
	}

	public function getFilters() {
		return array(
				'convert'								=> new Twig_Filter_Method($this, 'convertFilter'),
				'convertViaCode'				=> new Twig_Filter_Method($this, 'convertViaCodeFilter'),
				'convertFormat'					=> new Twig_Filter_Method($this, 'convertFormatFilter'),
				'convertViaCodeFormat'	=> new Twig_Filter_Method($this, 'convertViaCodeFormatFilter'),
				'fileExtension'					=> new Twig_Filter_Method($this, 'getFileExtension'),
				'highlight'							=> new Twig_Filter_Method($this, 'highlight', array('is_safe' => array('html'))),
				'videoEmbed'						=> new Twig_Filter_Method($this, 'generateVideoEmbed', array('is_safe' => array('html'))),
		);
	}

	public function getName() {
		return 'visual_extension';
	}
	
	/**
	 * Highlights a sentence with given expression
	 * 
	 * @param string $sentence
	 * @param string $expr
	 * @param string $css
	 * @return string
	 */
	public function highlight($sentence, $expr, $css=null) {
		if ($css == null) {
			$style = 'color: red;';
		}
		return preg_replace('/(' . $expr . ')/', '<span style="'.$style.'" class="'.$css.'">\1</span>', $sentence);
	}

	/**
	 * Converts given amount
	 * 
	 * @param number $number
	 * @param \ARIPD\AdminBundle\Entity\Iso4217 $iso4217From
	 * @param \ARIPD\AdminBundle\Entity\Iso4217 $iso4217To
	 * @return string
	 */
	function convertFilter($number, \ARIPD\AdminBundle\Entity\Iso4217 $iso4217From, \ARIPD\AdminBundle\Entity\Iso4217 $iso4217To) {
		return $number * $iso4217From->getRate() / $iso4217To->getRate();
	}
	
	/**
	 * Converts given amount
	 * 
	 * @param number $number
	 * @param string $codeFrom
	 * @param string $codeTo
	 * @return number
	 */
	function convertViaCodeFilter($number, $codeFrom, $codeTo) {
		$iso4217From = $this->container->get('doctrine')->getManager()->getRepository('ARIPDAdminBundle:Iso4217')->findOneByCode($codeFrom);
		$iso4217To = $this->container->get('doctrine')->getManager()->getRepository('ARIPDAdminBundle:Iso4217')->findOneByCode($codeTo);
		
		return $number * $iso4217From->getRate() / $iso4217To->getRate();
	}
	
	/**
	 * Converts and formats given amount
	 * 
	 * @param number $number
	 * @param \ARIPD\AdminBundle\Entity\Iso4217 $iso4217From
	 * @param \ARIPD\AdminBundle\Entity\Iso4217 $iso4217To
	 * @return string
	 */
	function convertFormatFilter($number, \ARIPD\AdminBundle\Entity\Iso4217 $iso4217From, \ARIPD\AdminBundle\Entity\Iso4217 $iso4217To) {
		$converted = $this->convertFilter($number, $iso4217From, $iso4217To);
		
		$formatter = NumberFormatter::create(
				Locale::getDefault(),
				NumberFormatter::CURRENCY
		);
		
		return $formatter->formatCurrency($converted, $iso4217To->getCode());
	}
	
	/**
	 * Converts and formats given amount
	 * 
	 * @param number $number
	 * @param string $codeFrom
	 * @param string $codeTo
	 * @return string
	 */
	function convertViaCodeFormatFilter($number, $codeFrom, $codeTo) {
		$converted = $this->convertViaCodeFilter($number, $codeFrom, $codeTo);
		
		$formatter = NumberFormatter::create(
				Locale::getDefault(),
				NumberFormatter::CURRENCY
		);
		
		return $formatter->formatCurrency($converted, $codeTo);
	}
	
	function getFileExtension($filename) {
		return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
	}
	
	function generateLipsum($paragraph=1) {
		return ARIPDString::generateLoremIpsum($paragraph);
	}
	
	function generateVideoEmbed($url, $width, $height) {
		$video = new ARIPDVideo($url);
		return $video->video_embed($width, $height);
	}
	
	/**
	 * 
	 * @param \ARIPD\UserBundle\Entity\User $entity
	 * @param number $width
	 * @param number $height
	 * @return string
	 */
	function generateProfileImage($entity, $width=0, $height=0) {
		//$gravatar = new GravatarApi();
		if ( $entity->getDefaultimage() ) {
			$alt = $entity->getFullname();
			$src = $this->container->get('image.handling')->open($entity->getDefaultimage()->getWebPath())->resize($width, $height);
		}
		//elseif ($gravatar->exists($entity->getEmail())) {
			//$alt = $entity->getFullname();
			//$src = $gravatar->getUrl($entity->getEmail(), $width);
		//}
		else {
			$alt = $entity->getFullname();
			if ($this->localGenerator) {
				$src = $this->generator->generate('aripd_default_image_index', array('width'=>$width, 'height'=>$height, 'text'=>$alt));
			}
			else {
				$src = sprintf('http://placehold.it/%sx%s&text=%s', $width, $height, $alt);
			}
		}
		return sprintf('<img alt="%s" src="%s" />', $alt, $src);
	}
	
	/**
	 * TODO width ve height options altında olmalı
	 * 
	 * If available it returns defaultimage of given entity having relationship with images, otherwise returns an empty image from placehold.it.
	 * Sizes are set via parameters if provided. If not orginal sizes are calculated and used.
	 * 
	 * @param \ARIPD\CMSBundle\Entity\Post $entity
	 * @param number $width
	 * @param number $height
	 * @param array $options
	 * @return string
	 */
	function generatePostImage($entity, $width=0, $height=0, $options=null) {
		$title = $entity->getName();
		if ( $entity->getDefaultimage() ) {
			$alt = $entity->getDefaultimage()->getName();
			$image = $this->container->get('image.handling')->open($entity->getDefaultimage()->getWebPath());
			$thumb = $this->container->get('image.handling')->open($entity->getDefaultimage()->getWebPath());
			if ($width==0 && $height==0) {
				$width = $image->width();
				$height = $image->height();
			}
			$src = $image->resize($width, $height);
			$thumb = $thumb->resize($this->container->get('aripd_config')->get('store_post_thumb_width'), $this->container->get('aripd_config')->get('store_post_thumb_height'));
		}
		else {
			$alt = $entity->getName();
			if ($width==0 && $height==0) {
				$width = $this->container->get('aripd_config')->get('store_post_width');
				$height = $this->container->get('aripd_config')->get('store_post_height');
			}
			$thumb_w = $this->container->get('aripd_config')->get('store_post_thumb_width');
			$thumb_h = $this->container->get('aripd_config')->get('store_post_thumb_height');
			if ($this->localGenerator) {
				$src = $this->generator->generate('aripd_default_image_index', array('width'=>$width, 'height'=>$height, 'text'=>$alt));
				$thumb = $this->generator->generate('aripd_default_image_index', array('width'=>$thumb_w, 'height'=>$thumb_h, 'text'=>$alt));
			}
			else {
				$src = sprintf('http://placehold.it/%sx%s&text=%s', $width, $height, $alt);
				$thumb = sprintf('http://placehold.it/%sx%s&text=%s', $thumb_w, $thumb_h, $alt);
			}
		}
		
		$class = ($options['class'])?$options['class']:null;
		
		// nivoslider için
		//<img src="http://wbpreview.com/previews/WB0N22916/assets/img/slide_05.jpg" data-thumb="http://wbpreview.com/previews/WB0N22916/assets/img/slide_05.jpg" alt="" title="Açıklama" />
		return sprintf('<img class="%s" src="%s" data-thumb="%s" alt="%s" title="%s" />', $class, $src, $thumb, $alt, $title);
	}
		
	/**
	 * Returns image of given entity if available, otherwise returns an empty image from placehold.it.
	 * 
	 * @param \ARIPD\AdsBundle\Entity\Advertisement|\ARIPD\CMSBundle\Entity\Topic|\ARIPD\ShoppingBundle\Entity\Paymentgroup|\ARIPD\ShoppingBundle\Entity\Transportation|\ARIPD\StoreBundle\Entity\Banner|\ARIPD\StoreBundle\Entity\Brand|\ARIPD\StoreBundle\Entity\Category $entity
	 * @param number $width
	 * @param number $height
	 * @return string
	 */
	function generateEntityImage($entity, $width=0, $height=0) {
		$finder = new Finder();
		if ( $entity->getPath() && $finder->files()->in($this->uploadDirectory())->name($entity->getPath())->count() > 0 ) {
			$alt = $entity->getName();
			$src = $this->container->get('image.handling')->open($entity->getWebPath());
			if ($width != 0 && $height != 0) $src->resize($width, $height);
		}
		else {
			$alt = $entity->getName();
			if ($this->localGenerator) {
				$src = $this->generator->generate('aripd_default_image_index', array('width'=>$width, 'height'=>$height, 'text'=>$alt));
			}
			else {
				$src = sprintf('http://placehold.it/%sx%s&text=%s', $width, $height, $alt);
			}
		}
		return sprintf('<img alt="%s" src="%s" />', $alt, $src);
	}
	
	function generateImage($width=0, $height=0, $alt=null) {
		if ($this->localGenerator) {
			$src = $this->generator->generate('aripd_default_image_index', array('width'=>$width, 'height'=>$height, 'text'=>$alt));
		}
		else {
			$src = sprintf('http://placehold.it/%sx%s&text=%s', $width, $height, $alt);
		}
		return sprintf('<img alt="%s" src="%s" />', $alt, $src);
	}
	
	function generateCRMIndividualImage($entity, $width=0, $height=0) {
		if ( $entity->getDefaultimage() ) {
			$alt = $entity->getFullname();
			$src = $this->container->get('image.handling')->open($entity->getDefaultimage()->getWebPath())->resize($width, $height);
		}
		else {
			$alt = $entity->getFullname();
			if ($this->localGenerator) {
				$src = $this->generator->generate('aripd_default_image_index', array('width'=>$width, 'height'=>$height, 'text'=>$alt));
			}
			else {
				$src = sprintf('http://placehold.it/%sx%s&text=%s', $width, $height, $alt);
			}
		}
		return sprintf('<img alt="%s" src="%s" />', $alt, $src);
	}
	
	/**
	 * Grabs the first image from a text content if available, otherwise returns an empty image from placehold.it.
	 * 
	 * @param object $entity
	 * @param number $width
	 * @param number $height
	 * @return string
	 */
	function grabImage($entity, $width=0, $height=0) {
		$crawler = new Crawler($entity->getContent());
		$elements = $crawler->filterXPath('//img');
		if ( $elements->count() > 0 ) {
			$alt = $entity->getName();
			$src = $elements->first()->attr('src');
		}
		else {
			$alt = $entity->getName();
			if ($this->localGenerator) {
				$src = $this->generator->generate('aripd_default_image_index', array('width'=>$width, 'height'=>$height, 'text'=>$alt));
			}
			else {
				$src = sprintf('http://placehold.it/%sx%s&text=%s', $width, $height, $alt);
			}
		}
		return sprintf('<img alt="%s" src="%s" />', $alt, $src);
	}
	
}