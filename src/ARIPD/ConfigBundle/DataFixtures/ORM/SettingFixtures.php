<?php
namespace ARIPD\ConfigBundle\DataFixtures\ORM;
use ARIPD\ConfigBundle\Entity\Setting;
use ARIPD\AdminBundle\Util\ARIPDString;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class SettingFixtures extends AbstractFixture {
	
	public function load(ObjectManager $manager) {
		
		$initials = array(
				array('application', 'app_name', 'PDC'),
				array('application', 'app_tagline', 'pdc.com.tr'),
				array('application', 'app_description', 'PDC, Premium Smartphone Cases, Tablet Cases and Accessories for Apple iPhone, Apple iPad, Samsung Galaxy S and Samsung Note Series'),
				array('application', 'app_keywords', 'Premium Case, Accessory, Smartphone, Tablet, Apple, Samsung, iPhone, iPad, Galaxy S, Galaxy Note'),
				
				array('company', 'company_title', 'saha bilişim hizmetleri ticaret limited şirketi'),
				array('company', 'company_address', 'çobançeşme mah. sanayi cad. kımız sok. no:12 34196 yenibosna, bahçelievler, istanbul, türkiye'),
				array('company', 'company_phone', '+90-212-512-3163'),
				array('company', 'company_fax', '+90-212-511-3581'),
				array('company', 'company_email', 'bilgi@pdc.com.tr'),
				array('company', 'company_latitude', '40.993496'),
				array('company', 'company_longitude', '28.829284'),
				
				array('mailer', 'mailer_transport', 'smtp'),
				array('mailer', 'mailer_encryption', 'ssl'),
				array('mailer', 'mailer_auth_mode', 'login'),
				array('mailer', 'mailer_port', 465),
				array('mailer', 'mailer_host', 'smtpout.secureserver.net'),
				array('mailer', 'mailer_user', 'alias@domain.tld'),
				array('mailer', 'mailer_password', null),
				
				array('theme', 'themes', 'default'),
				array('theme', 'theme_active', 'default'),
				
				array('mail', 'mail_sender_name', 'ARIPD'),
				array('mail', 'mail_sender_address', 'alias@domain.tld'),
				array('mail', 'administrators', 'bilgi@aripd.com'),
				
				array('seo', 'google-site-verification', '6loH5RF-F9uR3jnRPjJKdqtqM7bMreCayh79bRyxvG8'),
				
				array('analytic', 'ga_tracking', 'UA-37312401-1'),
				
				array('advertisement', 'google_ad_client', false),
				array('advertisement', 'google_ad_slot', 'xxxxxxxxxx'),
				array('advertisement', 'google_ad_width', 468),
				array('advertisement', 'google_ad_height', 60),
				
				array('module', 'module_cms', true),
				array('module', 'module_bulletin', true),
				array('module', 'module_blog', true),
				array('module', 'module_forum', true),
				array('module', 'module_crm', true),
				array('module', 'module_store', true),
				array('module', 'module_dms', true),
				array('module', 'module_scm', true),
				
				array('image', 'cms_post_width', 752),
				array('image', 'cms_post_height', 470),
				array('image', 'cms_post_thumb_width', 80),
				array('image', 'cms_post_thumb_height', 80),
				array('image', 'cms_topic_width', 300),
				array('image', 'cms_topic_height', 300),
				array('image', 'user_user_width', 300),
				array('image', 'user_user_height', 300),
				array('image', 'crm_individual_width', 150),
				array('image', 'crm_individual_height', 200),
				
				array('paginator', 'b2c_paginator_nof_products', 8),
				
				array('shopping', 'cms_bulletin_freecoupon_amount', 10),
				array('shopping', 'cms_bulletin_freecoupon_currency', 'TRL'),
				
				array('shopping', 'shopping_transportation_foc_minimum_amount', 50),
				array('shopping', 'shopping_transportation_foc_minimum_currency', 'TRL'),
				
		);
		
		foreach ($initials as $initial) {
			$entity = new Setting();
			$entity->setSection($initial[0]);
			$entity->setName($initial[1]);
			$entity->setValue($initial[2]);
			$manager->persist($entity);
		}

		$manager->flush();
	}

}
