<?php

namespace ARIPD\ConfigBundle\Controller;

use ARIPD\ConfigBundle\Entity\Setting;
use ARIPD\ConfigBundle\Form\ModifySettingsForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

/**
 * This is the class that manages ARIPDConfigBundle:Settings
 * 
 * @Route("/settings")
 * @PreAuthorize("hasRole('ROLE_ADMIN')")
 */
class SettingsController extends Controller {

	/**
	 * 
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 * 
	 * @Route("/modify", name="aripd_config_settings_modify")
	 * @Template()
	 */
	public function modifyAction() {
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository(get_class(new Setting()));
		$allStoredSettings = $repo->findAll();

		$formData = array(
			'settings' => $allStoredSettings,
		);

		$form = $this->createForm(new ModifySettingsForm(), $formData);
		$request = $this->get('request');
		if ($request->getMethod() === 'POST') {
			$form->bind($request);

			if ($form->isValid()) {
				foreach ($formData['settings'] as $formSetting) {
					$storedSetting = $this->getSettingByName($allStoredSettings, $formSetting->getName());
					if ($storedSetting !== null) {
						$storedSetting->setValue($formSetting->getValue());
						$em->persist($storedSetting);
					}
				}

				$em->flush();

				$this->get('session')->getFlashBag->add('global-notice',
						$this->get('translator')->trans('The settings were changed', array(), 'ARIPDConfigBundle'));
				return $this->redirect($this->generateUrl('aripd_config_settings_modify'));
			}
		}

		return $this->render('ARIPDConfigBundle:Settings:modify.html.twig', array(
			'form' => $form->createView(),
			'sections' => $this->getSections($allStoredSettings),
		));
	}

	/**
	 * @param Setting[] $settings
	 * @return string[] (may also contain a null value)
	 */
	protected function getSections(array $settings) {
		$sections = array();

		foreach ($settings as $setting) {
			$section = $setting->getSection();
			if (!in_array($section, $sections)) {
				$sections[] = $section;
			}
		}

		sort($sections);

		return $sections;
	}

	/**
	 * @param Setting[] $settings
	 * @param string $name
	 * @return Setting|null
	 */
	protected function getSettingByName(array $settings, $name) {
		foreach ($settings as $setting) {
			if ($setting->getName() === $name) {
				return $setting;
			}
		}

		return null;
	}

}
