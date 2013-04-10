<?php
namespace ARIPD\CMSBundle\Form\Model;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

class BulletinBasicEnquiry {

	protected $emailaddress;

	public function getEmailaddress() {
		return $this->emailaddress;
	}

	public function setEmailaddress($emailaddress) {
		$this->emailaddress = $emailaddress;
	}

}
