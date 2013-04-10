<?php
namespace ARIPD\AdminBundle\Listener;

use Symfony\Component\Finder\Finder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class ThemeListener {

	protected $container;

	public function __construct(ContainerInterface $container) {
		$this->container = $container;
	}

	public function onKernelRequest(GetResponseEvent $event) {
		$request = $event->getRequest();

		if ($request->attributes->has('_theme') === false) {
			// if you use annotation with doctrine, you'll need to register them before that line
			$config = $this->container->get('doctrine.orm.entity_manager')
					->getRepository('ARIPDAdminBundle:Config')
					->findOneByName('theme_active');
			
			// you probably will want to set _theme in request context, not in the $request object
			$themes = $this->getThemes();
			$request->attributes->set('_themes', $themes);
			$request->attributes->set('_theme', $config->getValue());
			//$this->container->get('aripd_theme.active_theme')->setName($config->getValue());
		}
		//$this->router->setContext($context);

	}

	private function getThemes() {
		$themes = new Finder();
		$themes->directories()->in($this->themesDirectory())->sortByName()->depth('== 0');
		foreach($themes as $theme) {
			$arr[] = $theme->getRelativePathname();
		}
		return $arr;
	}
	
	private function themesDirectory() {
		return $this->container->getParameter('kernel.root_dir') . '/Resources/themes/';
	}
	
}
