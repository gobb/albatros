<?php
namespace ARIPD\UserBundle\Controller;

use ARIPD\UserBundle\Entity\File;
use ARIPD\AdminBundle\Util\ARIPDString;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * @Route("/file")
 * @PreAuthorize("hasRole('ROLE_USER')")
 */
class FileController extends Controller {

	/**
	 * @Route("/upload/{type}", name="aripd_user_file_upload")
	 * @Method("POST")
	 * @Template()
	 * 
	 * @param string $type
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function uploadAction($type) {
		$user = $this->container->get('security.context')->getToken()->getUser();

		$uploadDir = '/uploads/' . $user->getId() . '/';
		$dir = '/home/cem/git/albatros/web' . $uploadDir;

		$fs = new Filesystem();
		if (!$fs->exists($dir)) {
			$fs->mkdir($dir, 0777);
		}

		switch ($type) {
			case 'image':
				$valid = array('image/png', 'image/jpg', 'image/gif', 'image/jpeg', 'image/pjpeg');
				break;
			case 'file':
				$valid = array('application/pdf', 'application/x-pdf', 'application/x-bzpdf', 'application/x-gzpdf', 'text/plain');
				break;
		}

		if (in_array(strtolower($_FILES['file']['type']), $valid)) {

			$filename = $_FILES['file']['name'];
			//$filename = md5(date('YmdHis')) . '.' . ARIPDString::getExtension($_FILES['file']['name']);
			$file = $dir . $filename;

			$finder = new Finder();
			if ($finder->files()->in($dir)->name($filename)->count() > 0) {
				$fs->copy($_FILES['file']['tmp_name'], $file, true); //override
			} else {
				$fs->copy($_FILES['file']['tmp_name'], $file);

				$entity = new File();
				$entity->setUser($user);
				$entity->setFilename($filename);
				$em = $this->getDoctrine()->getManager();
				$em->persist($entity);
				$em->flush();
			}

			switch ($type) {
				case 'image':
					$array = array('filelink' => $uploadDir . $filename);
					break;
				case 'file':
					$array = array('filelink' => $uploadDir . $filename, 'filename' => $filename);
					break;
			}
			
			return new Response(stripslashes(json_encode($array)));
		}
	}

}
