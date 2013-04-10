<?php
namespace ARIPD\AdminBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * This is the class that manages ARIPDCMSBundle:Comment
 * 
 * @Route("/cms/comment")
 * @PreAuthorize("hasRole('ROLE_EDITOR')")
 */
class CMSCommentController extends Controller {

	/**
	 * Changes status of entity
	 * 
	 * @param number $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/{id}/status", requirements={"id" = "\d+"}, options={"expose" = true}), name="aripd_admin_cmscomment_status")
	 * @Template()
	 */
	public function statusAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDCMSBundle:Comment')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		if ($approval = $entity->getApproved() ^ 1) {
			$this->container->get('aripduser.log_service')
					->saveLog($entity->getUser(),
							'logtype___cms_comment_confirmed');
		} else {
			$this->container->get('aripduser.log_service')
					->saveLog($entity->getUser(),
							'logtype___cms_comment_confirmation_withdrawn');
		}
		$entity->setApproved($approval);
		$em->persist($entity);
		$em->flush();

		return $this
				->redirect(
						$this
								->generateUrl('aripd_admin_cmspost_show',
										array(
												'id' => $entity->getPost()
														->getId(),))
								. '#comment-' . $entity->getId());
	}

	/**
	 * Method that gets data in json format to use in datatable
	 * 
	 * @return Ambigous <\LanKit\DatatablesBundle\Datatables\mixed, mixed>
	 * 
	 * @Route("/data", name="aripd_admin_cmscomment_data")
	 */
	public function dataAction() {
		$datatable = $this->get('lankit_datatables')->getDatatable('ARIPDCMSBundle:Comment');
		return $datatable->getSearchResults();
	}

	/**
	 * Lists entities
	 *
	 * @Route("/index", options={"expose" = true}), name="aripd_admin_cmscomment_index")
	 * @Template()
	 */
	public function indexAction() {
		return $this->container->get('templating')
				->renderResponse('ARIPDAdminBundle:CMSComment:index.html.twig');
	}
	
}
