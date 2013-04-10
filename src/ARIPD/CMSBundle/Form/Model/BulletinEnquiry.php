<?php
namespace ARIPD\CMSBundle\Form\Model;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\MinLength;
use Symfony\Component\Validator\Constraints\MaxLength;
use Symfony\Component\Validator\Constraints\Type;

class BulletinEnquiry {

	protected $emailaddress;
	protected $gender;

	public function getEmailaddress() {
		return $this->emailaddress;
	}

	public function setEmailaddress($emailaddress) {
		$this->emailaddress = $emailaddress;
	}

	public function getGender() {
		return $this->gender;
	}

	public function setGender($gender) {
		$this->gender = $gender;
	}

}
