<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel {
	public function registerBundles() {
		$bundles = array(new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
				new Symfony\Bundle\SecurityBundle\SecurityBundle(),
				new Symfony\Bundle\TwigBundle\TwigBundle(),
				new Symfony\Bundle\MonologBundle\MonologBundle(),
				new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
				new Symfony\Bundle\AsseticBundle\AsseticBundle(),
				new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
				new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
				new JMS\AopBundle\JMSAopBundle(),
				new JMS\DiExtraBundle\JMSDiExtraBundle($this),
				new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
				new JMS\SerializerBundle\JMSSerializerBundle(),
				new JMS\I18nRoutingBundle\JMSI18nRoutingBundle(),
				new JMS\TranslationBundle\JMSTranslationBundle(),
				new ARIPD\DefaultBundle\ARIPDDefaultBundle(),
				new ARIPD\AdminBundle\ARIPDAdminBundle(),
				new ARIPD\UserBundle\ARIPDUserBundle(),
				new ARIPD\MobileBundle\ARIPDMobileBundle(),
				new ARIPD\CMSBundle\ARIPDCMSBundle(),
				new ARIPD\BlogBundle\ARIPDBlogBundle(),
				new ARIPD\ForumBundle\ARIPDForumBundle(),
				new ARIPD\SurveyBundle\ARIPDSurveyBundle(),
				new ARIPD\ShoppingBundle\ARIPDShoppingBundle(),
				new ARIPD\StockBundle\ARIPDStockBundle(),
				new ARIPD\StoreBundle\ARIPDStoreBundle(),
				new ARIPD\CRMBundle\ARIPDCRMBundle(),
				new ARIPD\AdsBundle\ARIPDAdsBundle(),
				new ARIPD\DMSBundle\ARIPDDMSBundle(),
				new ARIPD\ConfigBundle\ARIPDConfigBundle(),
				new ARIPD\SCMBundle\ARIPDSCMBundle(),
				new ARIPD\IntranetBundle\ARIPDIntranetBundle(),
				new Liip\ThemeBundle\LiipThemeBundle(),
				new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
				new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
				new FOS\UserBundle\FOSUserBundle(),
				new FOS\RestBundle\FOSRestBundle(),
				new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
				new FOS\FacebookBundle\FOSFacebookBundle(),
				new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
				new Gregwar\ImageBundle\GregwarImageBundle(),
				new Gregwar\CaptchaBundle\GregwarCaptchaBundle(),
				new SaadTazi\GChartBundle\SaadTaziGChartBundle(),
				new LanKit\DatatablesBundle\LanKitDatatablesBundle(),
				new BCC\CronManagerBundle\BCCCronManagerBundle(),);

		if (in_array($this->getEnvironment(), array('dev', 'test'))) {
			$bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
			$bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
			$bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
		}

		return $bundles;
	}

	public function registerContainerConfiguration(LoaderInterface $loader) {
		$loader->load(__DIR__ . '/config/config_' . $this->getEnvironment() . '.yml');
	}
}
