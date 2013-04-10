<?php
namespace ARIPD\AdminBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * @Route("/store/comment")
 */
class StoreCommentController extends Controller {

	/**
	 * @Route("/{id}/status", requirements={"id" = "\d+"}, name="aripd_admin_store_comment_status")
	 * @Template()
	 * @Secure(roles="ROLE_EDITOR")
	 */
	public function statusAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('ARIPDStoreBundle:Comment')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find');
		}

		if ($approval = $entity->getApproved() ^ 1) {
			$this->container->get('aripduser.log_service')
					->saveLog($entity->getUser(),
							'logtype___store_comment_confirmed');
		} else {
			$this->container->get('aripduser.log_service')
					->saveLog($entity->getUser(),
							'logtype___store_comment_confirmation_withdrawn');
		}
		$entity->setApproved($approval);
		$em->persist($entity);
		$em->flush();

		return $this
				->redirect(
						$this
								->generateUrl(
										'aripd_admin_storeproduct_show',
										array(
												'id' => $entity->getProduct()
														->getId(),))
								. '#comment-' . $entity->getId());
	}

}
