<?php
namespace ARIPD\StoreBundle\Controller;

use ARIPD\StoreBundle\Entity\Model;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * This is the class that manages ARIPDStoreBundle:Model
 * 
 * @Route("/model")
 */
class ModelController extends Controller {

	/**
	 * Finds and displays an entity
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/{code}/{slug}/show", requirements={"id" = "\d+"}, name="aripd_store_model_show")
	 * @Template()
	 */
	public function showAction($id, $code, $slug) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDStoreBundle:Model')->findOneBy(array('id' => $id, 'code' => $code));

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$this->container->get('session')->remove('aripdads_tags');
		$this->container->get('session')->set('aripdads_tags', $entity->getProduct()->getTags());
		$this->container->get('aripdstore.product_service')->saveHit($entity->getProduct());

		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDStoreBundle/Model/'
								. $entity->getProduct()->getDefaultcategory()
										->getTemplateProduct(),
						compact('entity'));
	}

}
