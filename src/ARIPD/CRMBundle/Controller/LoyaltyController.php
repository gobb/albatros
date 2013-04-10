<?php
namespace ARIPD\CRMBundle\Controller;

use ARIPD\CRMBundle\Form\Type\LoyaltyFormType;
use ARIPD\CRMBundle\Form\Model\LoyaltyFormModel;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * @Route("/loyalty")
 * @PreAuthorize("hasRole('ROLE_EDITOR')")
 *
 */
class LoyaltyController extends Controller {
	
	/**
	 * Creates a form to search
	 * 
	 * @Route("/index", name="aripd_admin_crm_loyalty_index")
	 * @Template()
	 */
	public function indexAction() {
		$entity = new LoyaltyFormModel();
		$form = $this->createForm(new LoyaltyFormType(), $entity);

		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$form->bind($request);

			if ($form->isValid()) {
				//$data = $form->getData();
				$postData = $request->request
						->get('aripdcrm_loyaltyformtype');
				return $this
						->redirect(
								$this
										->generateUrl(
												'aripd_admin_crm_loyalty_result',
												array(
														'token' => $postData['token'])));
			}
		}

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDAdminBundle:Loyalty:index.html.twig',
						array('entity' => $entity,
								'form' => $form->createView(),));
	}

	/**
	 * Lists search results
	 * 
	 * @param string $token
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/{token}/result", requirements={"token" = "\w+"}, name="aripd_admin_crm_loyalty_result")
	 * @Template()
	 */
	public function resultAction($token) {
		$entity = $this->container->get('fos_user.user_manager')
				->findUserByConfirmationToken($token);

		if (null === $entity) {
			$this->get('session')
					->getFlashBag()->add('global-notice',
							$this->get('translator')
									->trans('flash.crm.loyalty.notfound'));

			return $this
					->redirect(
							$this
									->generateUrl(
											'aripd_admin_crm_loyalty_index'));
		}

		return $this->container->get('templating')
				->renderResponse(
						'ARIPDAdminBundle:Loyalty:result.html.twig',
						compact('entity', 'token'));
	}

	/**
	 * Confirms token and gives bonus to user
	 * 
	 * @param string $token
	 * @throws NotFoundHttpException
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * 
	 * @Route("/{token}/confirm", requirements={"token" = "\w+"}, name="aripd_admin_crm_loyalty_confirm")
	 * @Template()
	 */
	public function confirmAction($token) {
		$user = $this->container->get('fos_user.user_manager')
				->findUserByConfirmationToken($token);

		if (null === $user) {
			throw new NotFoundHttpException(
					sprintf(
							'The user with confirmation token "%s" does not exist',
							$token));
		}

		/*****************************************************
		 * Log to the database
		 *****************************************************/
		$this->container->get('aripduser.log_service')
				->saveLog($user, 'logtype___crm_loyalty_confirmed');

		$this->get('session')
				->getFlashBag()->add('global-notice',
						$this->get('translator')
								->trans('flash.crm.loyalty.confirmed'));

		$user->setConfirmationToken(null);

		$this->container->get('fos_user.user_manager')->updateUser($user);

		return $this
				->redirect($this->generateUrl('aripd_admin_crm_loyalty_index'));
	}

}
