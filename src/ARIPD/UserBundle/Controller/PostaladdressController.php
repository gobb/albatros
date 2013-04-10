<?php
namespace ARIPD\UserBundle\Controller;
use ARIPD\UserBundle\Form\Type\PostaladdressFormType;
use ARIPD\UserBundle\Entity\Postaladdress;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * @Route("/postaladdress")
 * @PreAuthorize("hasRole('ROLE_USER')")
 */
class PostaladdressController extends Controller {
	
	/**
	 * @Route("/index", name="aripd_user_postaladdress_index")
	 * @Template()
	 */
	public function indexAction() {
		$user = $this->get("security.context")->getToken()->getUser();

		$em = $this->getDoctrine()->getManager();
		$entities = $em->getRepository('ARIPDUserBundle:Postaladdress')
				->findBy(array('user' => $user->getId()));
		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDUserBundle/Postaladdress/index.html.twig',
						compact('entities'));
	}

	/**
	 * @Route("/{id}/show", requirements={"id" = "\d+"}, name="aripd_user_postaladdress_show")
	 * @Template()
	 */
	public function showAction($id) {
		$user = $this->get("security.context")->getToken()->getUser();

		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDUserBundle:Postaladdress')
				->findOneBy(array('user' => $user->getId(), 'id' => $id));

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$deleteForm = $this->createDeleteForm($id);

		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDUserBundle/Postaladdress/show.html.twig',
						array('entity' => $entity,
								'delete_form' => $deleteForm->createView(),));
	}

	/**
	 * @Route("/new", name="aripd_user_postaladdress_new")
	 * @Template()
	 */
	public function newAction() {
		$entity = new Postaladdress();
		$form = $this->createForm(new PostaladdressFormType(), $entity);

		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDUserBundle/Postaladdress/new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * @Route("/create", name="aripd_user_postaladdress_create")
	 * @Method("POST")
	 * @Template()
	 */
	public function createAction() {
		$entity = new Postaladdress();
		$request = $this->getRequest();
		$form = $this->createForm(new PostaladdressFormType(), $entity);
		$form->bind($request);

		if ($form->isValid()) {
			$user = $this->get("security.context")->getToken()->getUser();
			$entity->setUser($user);

			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this
									->generateUrl(
											'aripd_user_postaladdress_show',
											array('id' => $entity->getId())));
		}

		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDUserBundle/Postaladdress/new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="aripd_user_postaladdress_edit")
	 * @Template()
	 */
	public function editAction($id) {
		$user = $this->get("security.context")->getToken()->getUser();

		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ARIPDUserBundle:Postaladdress')
				->findOneBy(array('user' => $user->getId(), 'id' => $id));

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$map = $this->get('ivory_google_map.map');
		$map->setLanguage($this->getRequest()->getLocale());
		$map->setCenter($entity->getLatitude(), $entity->getLongitude(), true);
		$map->setMapOption('zoom', 5);
		$map->setStylesheetOption('width', '100%');
		$map->setStylesheetOption('height', '300px');

		$marker = $this->get('ivory_google_map.marker');
		$marker->setPosition($entity->getLatitude(), $entity->getLongitude(), true);
		$map->addMarker($marker);

		$editForm = $this->createForm(new PostaladdressFormType(), $entity);
		$deleteForm = $this->createDeleteForm($id);

		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDUserBundle/Postaladdress/edit.html.twig',
						array('map' => $map, 'entity' => $entity,
								'edit_form' => $editForm->createView(),
								'delete_form' => $deleteForm->createView(),));
	}

	/**
	 * @Route("/{id}/update", requirements={"id" = "\d+"}, name="aripd_user_postaladdress_update")
	 * @Method("POST")
	 * @Template()
	 */
	public function updateAction($id) {
		$user = $this->get("security.context")->getToken()->getUser();

		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDUserBundle:Postaladdress')
				->findOneBy(array('user' => $user->getId(), 'id' => $id));

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$editForm = $this->createForm(new PostaladdressFormType(), $entity);
		$deleteForm = $this->createDeleteForm($id);

		$request = $this->getRequest();

		$editForm->bind($request);

		if ($editForm->isValid()) {
			$user = $this->get("security.context")->getToken()->getUser();
			$entity->setUser($user);

			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this
									->generateUrl(
											'aripd_user_postaladdress_edit',
											array('id' => $id)));
		}

		return $this->container->get('templating')
				->renderResponse(
						'::ARIPDUserBundle/Postaladdress/edit.html.twig',
						array('entity' => $entity,
								'edit_form' => $editForm->createView(),
								'delete_form' => $deleteForm->createView(),));
	}

	/**
	 * @Route("/{id}/delete", requirements={"id" = "\d+"}, name="aripd_user_postaladdress_delete")
	 * @Method("POST")
	 * @Template()
	 */
	public function deleteAction($id) {
		$form = $this->createDeleteForm($id);
		$request = $this->getRequest();

		$form->bind($request);

		if ($form->isValid()) {
			$user = $this->get("security.context")->getToken()->getUser();
			$em = $this->getDoctrine()->getManager();
			$entity = $em->getRepository('ARIPDUserBundle:Postaladdress')
					->findOneBy(array('user' => $user->getId(), 'id' => $id));

			if (!$entity) {
				throw $this->createNotFoundException('Unable to find');
			}

			$em->remove($entity);
			$em->flush();
		}

		return $this
				->redirect($this->generateUrl('aripd_user_postaladdress_index'));
	}

	private function createDeleteForm($id) {
		return $this->createFormBuilder(array('id' => $id))
				->add('id', 'hidden')->getForm();
	}
}
