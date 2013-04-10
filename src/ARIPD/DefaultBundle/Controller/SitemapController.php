<?php
namespace ARIPD\DefaultBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use Symfony\Component\Routing\RequestContext;

/**
 * This is the class that manages ARIPDDefaultBundle:Sitemap
 *
 * @Route("/sitemap")
 */
class SitemapController extends Controller {
	
	private function uploadDir() {
		return 'uploads/files/';
	}
	
	private function uploadDirectory() {
		return $this->container->get('kernel')->getRootDir() . '/../web/' . $this->uploadDir();
	}
	
	/**
	 * @Route("/sitemapindex.{_format}", requirements={"_format" = "atom|html|xml"}, defaults={"_format" = "xml"}, name="aripd_default_sitemapindex_index")
	 * @Template()
	 */
	public function sitemapindexAction() {
		$request = $this->getRequest();
		$scheme = "http";
		if ($request->server->get('HTTPS') == "on") {
			$scheme = "https";
		}
		
		$date = new \DateTime();
		
		$sitemaps = array(
				'page'		=> $this->sitemapPageAction(),
				'post'		=> $this->sitemapPostAction(),
				'product'	=> $this->sitemapProductAction(),
		);
		
		$urls = array();
		$i=0;
		foreach ($sitemaps as $key=>$sitemap) {
			$filename = "sitemap_$key.xml.gz";
			$gz = gzopen($this->uploadDirectory() . $filename, "w9");
			gzwrite($gz, $sitemap->getContent());
			gzclose($gz);
			
			$urls[$i]['loc']			= sprintf('%s://%s/%s%s', $scheme, $request->server->get('HTTP_HOST'), $this->uploadDir(), $filename);
			$urls[$i]['lastmod']	= $date->format('c');
			$i++;
		}
		
		return compact('urls');
	}
	
	/**
	 * @Route("/sitemap.page.{_format}", requirements={"_format" = "atom|html|xml"}, defaults={"_format" = "xml"}, name="aripd_default_sitemappage_index")
	 * @Template()
	 */
	public function sitemapPageAction() {
		$em = $this->getDoctrine()->getManager();
		
		$urls = array();
		$date = new \DateTime();
		
		// add some urls homepage
		$urls[] = array(
				'loc'					=> $this->get('router')->generate('aripd_default_index', array(), true),
				'lastmod'			=> $date->format('c'),
				'changefreq'	=> 'daily',
				'priority'		=> '1.0',
		);
		
		$pages = $em->getRepository('ARIPDCMSBundle:Page')->findAll();
		foreach ($pages as $page) {
			$urls[] = array(
					'loc'					=> $this->get('router')->generate('aripd_cms_page_show', array('id' => $page->getId(), 'slug' => $page->getSlug()), true),
					'lastmod'			=> $page->getUpdatedAt()->format('c'),
					'changefreq'	=> 'weekly',
					'priority'		=> '0.8'
			);
		}
		
		$format = $this->getRequest()->getRequestFormat();

		return $this->container->get('templating')->renderResponse('ARIPDDefaultBundle:Sitemap:sitemap.'.$format.'.twig', compact('urls'));
	}
	
	/**
	 * @Route("/sitemap.post.{_format}", requirements={"_format" = "atom|html|xml"}, defaults={"_format" = "xml"}, name="aripd_default_sitemappost_index")
	 * @Template()
	 */
	public function sitemapPostAction() {
		$em = $this->getDoctrine()->getManager();
		
		$urls = array();
		$date = new \DateTime();
		
		// add some urls homepage
		$urls[] = array(
				'loc'					=> $this->get('router')->generate('aripd_default_index', array(), true),
				'lastmod'			=> $date->format('c'),
				'changefreq'	=> 'daily',
				'priority'		=> '1.0',
		);
		
		$posts = $this->get('aripd_cms.post_service')->getPosts();
		foreach ($posts as $post) {
			$urls[] = array(
					'loc'					=> $this->get('router')->generate('aripd_cms_post_show', array('id' => $post->getId(), 'slug' => $post->getSlug()), true),
					'lastmod'			=> $post->getUpdatedAt()->format('c'),
					'changefreq'	=> 'weekly',
					'priority'		=> '0.8'
			);
		}
		
		$format = $this->getRequest()->getRequestFormat();

		return $this->container->get('templating')->renderResponse('ARIPDDefaultBundle:Sitemap:sitemap.'.$format.'.twig', compact('urls'));
	}
	
	/**
	 * @Route("/sitemap.product.{_format}", requirements={"_format" = "atom|html|xml"}, defaults={"_format" = "xml"}, name="aripd_default_sitemapproduct_index")
	 * @Template()
	 */
	public function sitemapProductAction() {
		$em = $this->getDoctrine()->getManager();
		
		$urls = array();
		$date = new \DateTime();
		
		// add some urls homepage
		$urls[] = array(
				'loc'					=> $this->get('router')->generate('aripd_default_index', array(), true),
				'lastmod'			=> $date->format('c'),
				'changefreq'	=> 'daily',
				'priority'		=> '1.0',
		);
		
		$products = $em->getRepository('ARIPDStoreBundle:Product')->findAll();
		foreach ($products as $product) {
			$urls[] = array(
					'loc'					=> $this->get('router')->generate('aripd_store_model_show', array('id' => $product->getModel()->getId(), 'code' => $product->getModel()->getCode(), 'slug' => $product->getSlug()), true),
					'lastmod'			=> $product->getUpdatedAt()->format('c'),
					'changefreq'	=> 'weekly',
					'priority'		=> '0.8'
			);
		}
	
		$format = $this->getRequest()->getRequestFormat();

		return $this->container->get('templating')->renderResponse('ARIPDDefaultBundle:Sitemap:sitemap.'.$format.'.twig', compact('urls'));
	}
	
}