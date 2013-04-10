<?php
namespace ARIPD\AdminBundle\Controller;
use Symfony\Component\Filesystem\Exception\IOException;

use Symfony\Component\Filesystem\Filesystem;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Finder\Finder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * @Route("/backup/database")
 * @PreAuthorize("hasRole('ROLE_ADMIN') or hasRole('ROLE_EDITOR')")
 */
class BackupDatabaseController extends Controller {

	private function backupDirectory() {
		return $this->get('kernel')->getRootDir() . DIRECTORY_SEPARATOR
				. '../web/uploads/backup/database' . DIRECTORY_SEPARATOR;
	}

	/**
	 * @Route("/index", name="aripd_admin_backup_database_index")
	 * @Template()
	 */
	public function indexAction() {
		$fs = new Filesystem();
		if (false == $fs->exists($this->backupDirectory())) {
			$fs->mkdir($this->backupDirectory(), 0777);
		}

		$files = new Finder();
		$files->files()->name('/\.sql$/')->in($this->backupDirectory())
				->sortByName()->depth('== 0');

		return $this
				->render('ARIPDAdminBundle:Backup:database.html.twig',
						compact('files'));
	}

	/**
	 * @Route("/{filename}/delete", name="aripd_admin_backup_database_delete")
	 * @Template()
	 */
	public function deleteAction($filename) {
		$fs = new Filesystem();
		try {
			$fs->remove(array($this->backupDirectory() . $filename));
			$this->get('session')->getFlashBag()
					->add('global-notice',
							$this->get('translator')
									->trans('flash.backup.delete.ok'));
		} catch (IOException $e) {
			$this->get('session')->getFlashBag()
					->add('global-notice',
							$this->get('translator')
									->trans('flash.backup.delete.nok'));
		}

		return $this
				->redirect(
						$this->generateUrl('aripd_admin_backup_database_index'));
	}

	/**
	 * @Route("/deleteall", name="aripd_admin_backup_database_deleteall")
	 * @Template()
	 */
	public function deleteallAction() {
		$fs = new Filesystem();
		try {
			$fs->remove(array($this->backupDirectory() . '.'));
			$this->get('session')
					->getFlashBag()->add('global-notice',
							$this->get('translator')
									->trans('flash.backup.delete.ok'));
		} catch (IOException $e) {
			$this->get('session')
					->getFlashBag()->add('global-notice',
							$this->get('translator')
									->trans('flash.backup.delete.nok'));
		}

		return $this
				->redirect(
						$this->generateUrl('aripd_admin_backup_database_index'));
	}

	/**
	 * @Route("/{filename}/download", name="aripd_admin_backup_database_download")
	 * @Template()
	 */
	public function downloadAction($filename) {
		$headers = array('Content-Type' => 'text/plain',
				'Content-Disposition' => 'attachment; filename="' . $filename
						. '"');

		$file = $this->backupDirectory() . $filename;

		return new Response(file_get_contents($file), 200, $headers);
	}

	/**
	 * @Route("/start", name="aripd_admin_backup_database_start")
	 * @Method("POST")
	 * @Template()
	 */
	public function startAction() {
		$request = $this->getRequest();
		$interval = $request->request->get('interval');

		//mysqldump -u albatros -p1q2w3e4r albatros > /home/cem/git/albatros/web/uploads/backup/albatros.sql
		$komut = sprintf('mysqldump -u %s -p%s %s > %s.sql',
				$this->container->getParameter('database.user'),
				$this->container->getParameter('database.password'),
				$this->container->getParameter('database.dbname'),
				$this->backupDirectory()
						. $this->container->getParameter('database.dbname')
						. '_' . date("YmdHis"));
		//echo $komut;exit;

		//echo exec('ls -la');exit;
		if ($interval == 'immediately') {
			echo exec($komut);
		}

		$this->get('session')
				->getFlashBag()->add('global-notice',
						$this->get('translator')->trans('flash.backup.ok'));

		return $this
				->redirect(
						$this->generateUrl('aripd_admin_backup_database_index'));

	}

}
