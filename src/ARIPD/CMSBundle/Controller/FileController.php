<?php
namespace ARIPD\CMSBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * This is the class that manages ARIPDCMSBundle:File
 *
 * @Route("/file")
 */
class FileController extends Controller {

	/**
	 * Finds and displays an entity
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/download", requirements={"id" = "\d+"}, name="aripd_cms_file_download")
	 * @Template()
	 */
	public function downloadAction($id) {
		$user = $this->container->get('security.context')->getToken()
				->getUser();

		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDCMSBundle:File')
				->createQueryBuilder('f')->leftJoin('f.post', 'p')
				->where('f.id = ?1')->setParameter(1, $id)
				->andWhere('p.user = ?2')->setParameter(2, $user->getId())
				->getQuery()->getOneOrNullResult();

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$mime = $em->getRepository('ARIPDDefaultBundle:Mime')
				->findOneByExtension($entity->getExtension());

		$headers = array('Content-Type' => $mime->getType(),
				'Content-Disposition' => 'attachment; filename="'
						. $entity->getPath() . '"');

		$filename = $entity->getUploadRootDir() . '/' . $entity->getPath();

		return new Response(file_get_contents($filename), 200, $headers);
	}

}
