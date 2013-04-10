<?php
namespace ARIPD\StoreBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * This is the class that manages ARIPDStoreBundle:Category
 *
 * @Route("/category")
 */
class CategoryController extends Controller {

	/**
	 * Finds and displays an entity
	 * 
	 * @param number $id
	 * 
	 * @Route("/{id}/{slug}/show", requirements={"id" = "\d+"}, name="aripd_store_category_show")
	 * @Template()
	 */
	public function showAction($id, $slug) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDStoreBundle:Category')->find($id);

		return $this
				->render('::ARIPDStoreBundle/Category/show.html.twig',
						compact('entity'));
	}

	public function jsonAction($id) {
		$serializer = $this->container->get('serializer');

		$em = $this->getDoctrine()->getManager();
		$category = $em->getRepository('ARIPDStoreBundle:Category')->find($id);

		return new Response($serializer->serialize($category, 'json'), 200,
				array('Content-Type' => 'application/json'));
	}

	/**
	 * Lists entities
	 * 
	 * @Route("/list", name="aripd_store_category_list")
	 * @Template()
	 */
	public function listAction() {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDStoreBundle:Category')
				->findBy(array('parent' => null));

		return $this
				->render('::ARIPDStoreBundle/Category/list.html.twig',
						compact('entities'));
	}

}
