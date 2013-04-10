<?php
namespace ARIPD\UserBundle\Controller;
use ARIPD\UserBundle\Form\Type\ImageFormType;
use ARIPD\UserBundle\Entity\Image;
use ARIPD\DefaultBundle\Handler\UploadHandler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * @Route("/image")
 * @PreAuthorize("hasRole('ROLE_USER')")
 */
class ImageController extends Controller {

	/**
	 * @Route("/upload", name="aripd_user_image_upload")
	 * @Template()
	 */
	public function uploadAction() {

		$request = $this->get('request');

		$int = '/web/uploads/';
		$root = dirname($_SERVER['SCRIPT_FILENAME']) . $int;
		$options = array('upload_dir' => $root, 'upload_url' => $int,);

		$upload_handler = new UploadHandler($options);

		switch ($request->getMethod()) {
		case 'OPTIONS':
			break;
		case 'HEAD':
		case 'GET':
			$upload_handler->get();
			break;
		case 'POST':
			if ($request->get('_method') === 'DELETE') {
				$upload_handler->delete();
			} else {
				$upload_handler->post();
			}
			break;
		case 'DELETE':
			$upload_handler->delete();
			break;
		default:
			header('HTTP/1.1 405 Method Not Allowed');
		}

		$response = new Response();
		$response->headers->set('Pragma', 'no-cache');
		$response->headers
				->set('Cache-Control', 'no-store, no-cache, must-revalidate');
		$response->headers
				->set('Content-Disposition', 'inline; filename="files.json"');
		$response->headers->set('X-Content-Type-Options', 'nosniff');
		$response->headers->set('Access-Control-Allow-Origin', '*');
		$response->headers
				->set('Access-Control-Allow-Methods',
						'OPTIONS, HEAD, GET, POST, PUT, DELETE');
		$response->headers
				->set('Access-Control-Allow-Headers',
						'X-File-Name, X-File-Type, X-File-Size');
		return $response;
	}

	/**
	 * @Route("/new", name="aripd_user_image_new")
	 * @Template()
	 */
	public function newAction() {
		$entity = new Image();
		$form = $this->createForm(new ImageFormType(), $entity);

		return $this
				->render('::ARIPDUserBundle/Image/new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView()));
	}

	/**
	 * @Route("/create", name="aripd_user_image_create")
	 * @Method("POST")
	 * @Template()
	 */
	public function createAction() {
		$user = $this->container->get('security.context')->getToken()
				->getUser();

		$entity = new Image();
		$entity->setUser($user);

		$request = $this->getRequest();
		$form = $this->createForm(new ImageFormType(), $entity);
		$form->bind($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			return $this
					->redirect($this->generateUrl('aripd_user_profile_show'));
		}

		return $this
				->render('::ARIPDUserBundle/Image/new.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * @Route("/{id}/delete", requirements={"id" = "\d+"}, name="aripd_user_image_delete")
	 * @Template()
	 */
	public function deleteAction($id) {
		$user = $this->container->get('security.context')->getToken()
				->getUser();

		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDUserBundle:Image')
				->findOneBy(array('user' => $user->getId(), 'id' => $id));

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$em->remove($entity);
		$em->flush();

		return new RedirectResponse(
				$this->get('router')->generate('aripd_user_profile_show'));
	}

	/**
	 * @Route("/{id}/set", requirements={"id" = "\d+"}, name="aripd_user_image_set")
	 * @Template()
	 */
	public function setAction($id) {
		$user = $this->container->get('security.context')->getToken()
				->getUser();

		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDUserBundle:Image')
				->findOneBy(array('user' => $user->getId(), 'id' => $id));

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		$user->setDefaultimage($entity);
		$em->persist($user);
		$em->flush();

		return new RedirectResponse(
				$this->get('router')->generate('aripd_user_profile_show'));
	}
}
