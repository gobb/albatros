<?php
namespace ARIPD\ConfigBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig_Extension;
use Twig_Function_Method;

class ConfigVariableExtension extends Twig_Extension {
	
	protected $container;
	
	public function __construct(ContainerInterface $container) {
		$this->container = $container;
	}
	
	public function getFunctions() {
		return array(
				'configVariable' => new Twig_Function_Method($this, 'getConfigVariable'),
		);
	}

	public function getName() {
		return 'aripd_config_variable';
	}
	
	function getConfigVariable($key) {
		return $this->container->get('aripd_config')->get($key);
	}
	
}