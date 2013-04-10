<?php
namespace ARIPD\AdminBundle\Controller;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * This is the class that manages theming
 * 
 * @Route("/appearance/theme")
 * @PreAuthorize("hasRole('ROLE_ADMIN')")
 */
class AppearanceThemeController extends Controller {

	/**
	 * @param string $theme
	 * @return \Symfony\Component\HttpFoundation\Response
	 * 
	 * @Route("/filemanager", name="aripd_admin_appearance_theme_filemanager")
	 * @Template()
	 */
	public function filemanagerAction($theme) {
		$directories = new Finder();
		$directories->directories()->in($this->themeDirectory($theme))
				->sortByName();

		return compact('directories');
	}

	private function themesDirectory() {
		return $this->get('kernel')->getRootDir() . DIRECTORY_SEPARATOR
				. 'Resources' . DIRECTORY_SEPARATOR . 'themes'
				. DIRECTORY_SEPARATOR;
	}

	private function themeDirectory($theme) {
		return $this->themesDirectory() . $theme . DIRECTORY_SEPARATOR;
	}

	private function viewsDirectory($theme) {
		return $this->themeDirectory($theme) . DIRECTORY_SEPARATOR . 'views'
				. DIRECTORY_SEPARATOR;
	}

	private function assetsDirectory($theme) {
		return $this->themeDirectory($theme) . DIRECTORY_SEPARATOR . 'assets'
				. DIRECTORY_SEPARATOR;
	}

	private function getThemes() {
		$themes = new Finder();
		return $themes->directories()->in($this->themesDirectory())
				->sortByName()->depth('== 0');
	}

	/**
	 * @Route("/index", name="aripd_admin_appearance_theme_index")
	 * @Template()
	 */
	public function indexAction() {
		$theme_active = $this->container->getParameter('theme_active');

		$themes = $this->getThemes();

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDAdminBundle:AppearanceTheme:index.html.twig',
						compact('themes', 'theme_active'));
	}

	/**
	 * Switches theme
	 * 
	 * @param string $theme
	 * @return \ARIPD\AdminBundle\Controller\Response
	 * 
	 * @Route("/{theme}/switch", name="aripd_admin_appearance_theme_switch")
	 * @Template()
	 */
	public function switchAction($theme) {
		$activeTheme = $this->container->get('aripd_theme.active_theme');
		//echo $activeTheme->getName();exit;
		$activeTheme->setName($theme);
		return new Response($activeTheme->getName());
	}

	/**
	 * Shows theme
	 * 
	 * @param string $theme
	 * 
	 * @Route("/{theme}/show", name="aripd_admin_appearance_theme_show")
	 * @Template()
	 */
	public function showAction($theme) {

		$request = $this->getRequest();
		//echo $request->get('file');exit;
		$test = explode(DIRECTORY_SEPARATOR, $request->get('file'));
		array_pop($test);
		//print_r($test);exit;
		//echo implode(DIRECTORY_SEPARATOR, $test);exit;

		$files = new Finder();
		$files->files()->name('/\.twig|.css|.js$/')
				->in(
						$this->themeDirectory($theme)
								. implode(DIRECTORY_SEPARATOR, $test))
				->sortByName()->depth('== 0');
		foreach ($files as $file) {
			//echo $file->getRelativePathname() . PHP_EOL;
		}
		//echo '<p>' . PHP_EOL . $request->get('file');exit;

		$themes = $this->getThemes();

		$views = new Finder();
		$views->files()->name('/\.twig|.css|.js$/')
				->in($this->viewsDirectory($theme))->sortByName()
				->depth('== 0');
		$files = new Finder();
		$files->files()->in($this->themeDirectory($theme))->sortByName();
		$theme_active['name'] = $theme;
		$theme_active['views'] = $views;
		$theme_active['files'] = $files;

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDAdminBundle:AppearanceTheme:show.html.twig',
						compact('themes', 'theme_active'));
	}

	/**
	 * Writes a theme
	 * 
	 * @param string $theme
	 * @param string $file
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/{theme}/{file}/write", name="aripd_admin_appearance_theme_write")
	 * @Method("POST")
	 * @Template()
	 */
	public function writeAction($theme, $file) {

		if (substr(sprintf('%o', fileperms($this->viewsDirectory($theme))), -4)
				!= "0777") {
			$fs = new Filesystem();
			$fs->chmod($this->viewsDirectory($theme), 0777, true); //recursively
		}

		$fh = fopen($this->viewsDirectory($theme) . $file, 'w')
				or die("can't open file");
		$request = $this->getRequest();
		$content = $request->request->get('content');
		fwrite($fh, $content);
		fclose($fh);

		$this->get('session')->getFlashBag()
				->add('global-notice',
						$this->get('translator')
								->trans('flash.theme.file.update.ok'));

		return $this
				->redirect(
						$this
								->generateUrl(
										'aripd_admin_appearance_theme_show',
										compact('theme', 'file')));
	}

	/**
	 * Deletes a theme
	 * 
	 * @param string $theme
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/{theme}/delete", name="aripd_admin_appearance_theme_delete")
	 * @Template()
	 */
	public function deleteAction($theme) {
		if (count($this->getThemes()) == 1) {

			$this->get('session')->getFlashBag()
					->add('global-notice',
							$this->get('translator')
									->trans('flash.theme.delete.nok'));

		} else {

			$fs = new Filesystem();
			$fs->remove(array($this->viewsDirectory($theme)));

			$this->get('session')->getFlashBag()
					->add('global-notice',
							$this->get('translator')
									->trans('flash.theme.delete.ok'));
		}

		return $this
				->redirect(
						$this
								->generateUrl(
										'aripd_admin_appearance_theme_index'));

	}

}
