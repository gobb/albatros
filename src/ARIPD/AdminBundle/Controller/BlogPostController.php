<?php
namespace ARIPD\AdminBundle\Controller;
use ARIPD\AdminBundle\Form\Type\BlogPostFormType;
use ARIPD\BlogBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * This is the class that manages ARIPDBlogBundle:Post
 * 
 * @Route("/blog/post")
 * @PreAuthorize("hasRole('ROLE_EDITOR')")
 */
class BlogPostController extends Controller {
	
	/**
	 * Lists entities
	 * 
	 * @Route("/index", name="aripd_admin_blog_post_index")
	 * @Template()
	 */
	public function indexAction() {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('ARIPDBlogBundle:Post')
				->createQueryBuilder('p')->orderBy('p.updatedAt', 'DESC')
				->getQuery()->getResult();

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator
				->paginate($entities,
						$this->get('request')->query->get('page', 1), 5);

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:BlogPost:index.html.twig',
						compact('pagination'));

	}

	/**
	 * @Route("/{id}/approval", requirements={"id" = "\d+"}, name="aripd_admin_blog_post_approval")
	 * @Template()
	 */
	public function approvalAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDBlogBundle:Post')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$entity->setApproved($entity->getApproved() ^ 1);
		$em->persist($entity);
		$em->flush();

		return $this
				->redirect(
						$this
								->generateUrl('aripd_admin_blog_post_show',
										array(
												'id' => $entity->getId(),)));
	}

	/**
	 * @Route("/{id}/show", requirements={"id" = "\d+"}, name="aripd_admin_blog_post_show")
	 * @Template()
	 */
	public function showAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDBlogBundle:Post')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$deleteForm = $this->createDeleteForm($id);

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:BlogPost:show.html.twig',
						array('entity' => $entity,
								'delete_form' => $deleteForm->createView(),));
	}

	/**
	 * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="aripd_admin_blog_post_edit")
	 * @Template()
	 */
	public function editAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDBlogBundle:Post')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$editForm = $this->createForm(new BlogPostFormType(), $entity);
		$deleteForm = $this->createDeleteForm($id);

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:BlogPost:edit.html.twig',
						array('entity' => $entity,
								'edit_form' => $editForm->createView(),
								'delete_form' => $deleteForm->createView(),));
	}

	/**
	 * @Route("/{id}/update", requirements={"id" = "\d+"}, name="aripd_admin_blog_post_update")
	 * @Method("POST")
	 * @Template()
	 */
	public function updateAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDBlogBundle:Post')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		// Create an array of the current Tag objects in the database
		$originalTags = array();
		foreach ($entity->getTags() as $tag)
			$originalTags[] = $tag;

		$editForm = $this->createForm(new BlogPostFormType(), $entity);
		$deleteForm = $this->createDeleteForm($id);

		$request = $this->getRequest();

		$editForm->bind($request);

		if ($editForm->isValid()) {

			// filter $originalTags to contain tags no longer present
			foreach ($entity->getTags() as $tag) {
				foreach ($originalTags as $key => $toDel) {
					if ($toDel->getId() === $tag->getId()) {
						unset($originalTags[$key]);
					}
				}
			}

			// remove the relationship between the tag and the entity
			foreach ($originalTags as $tag) {
				// remove the entity from the Tag
				//$tag->getPosts()->removeElement($entity);

				// if it were a ManyToOne relationship, remove the relationship like this
				// $tag->setPost(null);

				//$em->persist($tag);

				// if you wanted to delete the Tag entirely, you can also do that
				$em->remove($tag);
			}

			$em->persist($entity);
			$em->flush();

			return $this
					->redirect(
							$this
									->generateUrl(
											'aripd_admin_blog_post_edit',
											array('id' => $id)));
		}

		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:BlogPost:edit.html.twig',
						array('entity' => $entity,
								'edit_form' => $editForm->createView(),
								'delete_form' => $deleteForm->createView(),));
	}

	/**
	 * @Route("/{id}/delete", requirements={"id" = "\d+"}, name="aripd_admin_blog_post_delete")
	 * @Method("POST")
	 * @Template()
	 */
	public function deleteAction($id) {
		$form = $this->createDeleteForm($id);
		$request = $this->getRequest();

		$form->bind($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$entity = $em->getRepository('ARIPDBlogBundle:Post')->find($id);

			if (!$entity) {
				throw $this->createNotFoundException('Unable to find');
			}

			$em->remove($entity);
			$em->flush();
		}

		return $this
				->redirect($this->generateUrl('aripd_admin_blog_post_index'));
	}

	private function createDeleteForm($id) {
		return $this->createFormBuilder(array('id' => $id))
				->add('id', 'hidden')->getForm();
	}

}
