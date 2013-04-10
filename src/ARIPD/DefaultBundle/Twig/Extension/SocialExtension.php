<?php
namespace ARIPD\DefaultBundle\Twig\Extension;

use Twig_Extension;

class SocialExtension extends Twig_Extension {

	protected $container;

	/**
	 * Constructor.
	 *
	 * @param ContainerInterface $container
	 */
	public function __construct($container)
	{
		$this->container = $container;
	}

	public function getName()
	{
		return 'aripd_default_social_extension';
	}

	public function getFunctions()
	{
		return array(
				'socialButtons' => new \Twig_Function_Method($this, 'getSocialButtons' ,array('is_safe' => array('html'))),
				'facebookButton' => new \Twig_Function_Method($this, 'getFacebookLikeButton' ,array('is_safe' => array('html'))),
				'twitterButton' => new \Twig_Function_Method($this, 'getTwitterButton' ,array('is_safe' => array('html'))),
				'googlePlusButton' => new \Twig_Function_Method($this, 'getGooglePlusButton' ,array('is_safe' => array('html'))),
		);
	}

	public function getSocialButtons($parameters = array())
	{
		// no parameters were defined, keeps default values
		if (!array_key_exists('facebook', $parameters)){
			$render_parameters['facebook'] = array();
			// parameters are defined, overrides default values
		}else if(is_array($parameters['facebook'])){
			$render_parameters['facebook'] = $parameters['facebook'];
			// the button is not displayed
		}else{
			$render_parameters['facebook'] = false;
		}

		if (!array_key_exists('twitter', $parameters)){
			$render_parameters['twitter'] = array();
		}else if(is_array($parameters['twitter'])){
			$render_parameters['twitter'] = $parameters['twitter'];
		}else{
			$render_parameters['twitter'] = false;
		}

		if (!array_key_exists('googleplus', $parameters)){
			$render_parameters['googleplus'] = array();
		}else if(is_array($parameters['googleplus'])){
			$render_parameters['googleplus'] = $parameters['googleplus'];
		}else{
			$render_parameters['googleplus'] = false;
		}

		// get the helper service and display the template
		return $this->container->get('aripd.helper.social')->socialButtons($render_parameters);
	}

	//https://developers.facebook.com/docs/reference/plugins/like/
	public function getFacebookLikeButton($parameters = array())
	{
		// default values, you can override the values by setting them
		$parameters = $parameters + array(
				'url' => null,
				'locale' => 'en_US',
				'send' => false,
				'width' => 300,
				'showFaces' => false,
				'layout' => 'button_count',
		);

		return $this->container->get('aripd.helper.social')->facebookButton($parameters);
	}

	public function getTwitterButton($parameters = array())
	{
		$parameters = $parameters + array(
				'url' => null,
				'locale' => 'en',
				'message' => 'I want to share that page with you',
				'text' => 'Tweet',
				'via' => 'The Team',
				'tag' => 'ttot',
		);


		return $this->container->get('aripd.helper.social')->twitterButton($parameters);
	}

	public function getGooglePlusButton($parameters = array())
	{
		$parameters = $parameters + array(
				'url' => null,
				'locale' => 'en',
				'size' => 'medium',
				'annotation' => 'bubble',
				'width' => '300',
		);

		return $this->container->get('aripd.helper.social')->googlePlusButton($parameters);
	}
}