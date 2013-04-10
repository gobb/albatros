<?php
namespace ARIPD\CMSBundle\Form\Model;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

class ContactEnquiry {
	
	/**
	 * @Assert\NotBlank(message = "This value should not be blank.")
	 */
	protected $name;

	/**
	 * @Assert\NotBlank(message = "This value should not be blank.")
	 * @Assert\Email(message = "This value is not a valid email address")
	 */
	protected $email;

	/**
	 * @Assert\NotBlank(message = "This value should not be blank.")
	 * @Assert\Length(max=50, maxMessage="This value is too long. It should have {{ limit }} characters or less.")
	 */
	protected $subject;

	/**
	 * @Assert\NotBlank(message = "This value should not be blank.")
	 * @Assert\Length(min=50, minMessage="This value is too short. It should have {{ limit }} characters or more.")
	 */
	protected $body;

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getEmail() {
		return $this->email;
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	public function getSubject() {
		return $this->subject;
	}

	public function setSubject($subject) {
		$this->subject = $subject;
	}

	public function getBody() {
		return $this->body;
	}

	public function setBody($body) {
		$this->body = $body;
	}

}
