<?php
namespace ARIPD\UserBundle\Controller;
use ARIPD\CMSBundle\Entity\Image;
use ARIPD\UserBundle\Form\Type\CMSImageFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * @Route("/cms/image")
 * @PreAuthorize("hasRole('ROLE_WRITER')")
 */
class CMSImageController extends Controller {

	/**
	 * @Route("/post/{post_id}/new", name="aripd_user_cms_image_new")
	 * @Method("POST")
	 * @Template()
	 * 
	 * @param number $post_id
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function newAction($post_id) {
		$em = $this->getDoctrine()->getManager();

		$post = $em->getRepository('ARIPDCMSBundle:Post')->find($post_id);

		if (!$post) {
			throw $this->createNotFoundException('Unable to find');
		}

		$form = $this->createForm(new CMSImageFormType(), new Image());

		return $this
				->render('::ARIPDUserBundle/CMSImage/new.html.twig',
						array('post' => $post, 'form' => $form->createView(),));
	}

	/**
	 * @Route("/post/{post_id}/image/create", name="aripd_user_cms_image_create")
	 * @Method("POST")
	 * @Template()
	 * 
	 * @param number $post_id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function createAction($post_id) {
		$em = $this->getDoctrine()->getManager();

		$post = $em->getRepository('ARIPDCMSBundle:Post')->find($post_id);

		if (!$post) {
			throw $this->createNotFoundException('Unable to find');
		}

		$image = new Image();
		$image->setPost($post);

		$form = $this->createForm(new CMSImageFormType(), $image);

		$request = $this->getRequest();
		$form->bind($request);

		if ($form->isValid()) {
			$em->persist($image);
			$em->flush();

			$this->get('session')
					->getFlashBag()->add('global-notice',
							$this->get('translator')
									->trans('Upload completed', array(), 'ARIPDCMSBundle'));

			return $this
					->redirect(
							$this
									->generateUrl('aripd_user_cms_post_show',
											array('id' => $post->getId(),))
									. '#image-' . $image->getId());
		}

		return $this
				->render('::ARIPDUserBundle/CMSImage/new.html.twig',
						array('post' => $post, 'form' => $form->createView(),));
	}

	/**
	 * @Route("/{id}/delete", name="aripd_user_cms_image_delete")
	 * @Template()
	 * 
	 * @param number $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function deleteAction($id) {
		$user = $this->container->get('security.context')->getToken()
				->getUser();

		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDCMSBundle:Image')
				->createQueryBuilder('i')->leftJoin('i.post', 'p')
				->where('p.user = ?1')->setParameter(1, $user->getId())
				->andWhere('i.id = ?2')->setParameter(2, $id)->getQuery()
				->getOneOrNullResult();

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$em->remove($entity);
		$em->flush();

		$this->get('session')
				->getFlashBag()->add('global-notice',
						$this->get('translator')
								->trans('Deleted successfully', array(), 'ARIPDCMSBundle'));

		return $this
				->redirect(
						$this
								->generateUrl('aripd_user_cms_post_show',
										array(
												'id' => $entity->getPost()
														->getId(),)));
	}

	/**
	 * @Route("/{id}/set", name="aripd_user_cms_image_set")
	 * @Template()
	 * 
	 * @param number $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function setAction($id) {
		$user = $this->container->get('security.context')->getToken()
				->getUser();

		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDCMSBundle:Image')
				->createQueryBuilder('i')->leftJoin('i.post', 'p')
				->where('p.user = ?1')->setParameter(1, $user->getId())
				->andWhere('i.id = ?2')->setParameter(2, $id)->getQuery()
				->getOneOrNullResult();

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$entity->getPost()->setDefaultimage($entity);
		$em->persist($entity);
		$em->flush();

		return $this
				->redirect(
						$this
								->generateUrl('aripd_user_cms_post_show',
										array(
												'id' => $entity->getPost()
														->getId(),)));
	}

}
