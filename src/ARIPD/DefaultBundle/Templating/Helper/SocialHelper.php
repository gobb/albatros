<?php
namespace ARIPD\DefaultBundle\Templating\Helper;
use Symfony\Component\Templating\Helper\Helper;
use Symfony\Component\Templating\EngineInterface;

class SocialHelper extends Helper {
	protected $templating;

	public function __construct(EngineInterface $templating) {
		$this->templating = $templating;
	}

	public function socialButtons($parameters) {
		return $this->templating
				->render('ARIPDDefaultBundle:Helper:socialButtons.html.twig',
						$parameters);
	}

	public function facebookButton($parameters) {
		return $this->templating
				->render('ARIPDDefaultBundle:Helper:facebookButton.html.twig',
						$parameters);
	}

	public function twitterButton($parameters) {
		return $this->templating
				->render('ARIPDDefaultBundle:Helper:twitterButton.html.twig',
						$parameters);
	}

	public function googlePlusButton($parameters) {
		return $this->templating
				->render(
						'ARIPDDefaultBundle:Helper:googlePlusButton.html.twig',
						$parameters);
	}

	public function getName() {
		return 'aripd_default_social_helper';
	}
}
