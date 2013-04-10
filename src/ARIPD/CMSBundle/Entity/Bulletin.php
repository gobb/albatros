<?php
namespace ARIPD\CMSBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use ARIPD\AdminBundle\Util\ARIPDString;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="cms_bulletin")
 * @ORM\Entity
 */
class Bulletin {
	/**
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=255, unique=true)
	 * @Assert\NotBlank(message = "This value should not be blank.")
	 * @Assert\Email(message = "This value is not a valid email address")
	 */
	protected $emailaddress;

	/**
	 * @ORM\Column(type="string", length=1)
	 * @Assert\NotBlank(message = "This value should not be blank.")
	 * @Assert\Choice(
	 *     choices = { "M", "F" },
	 *     message = "Choose a valid gender."
	 * )
	 */
	protected $gender;

	/**
	 * @ORM\Column(type="string", length=255, unique=true)
	 */
	protected $token;

	public function setEmailaddress($emailaddress) {
		$this->emailaddress = $emailaddress;
		$this->setToken($this->emailaddress);
		return $this;
	}

	public function setToken($token) {
		$this->token = ARIPDString::encrypt($this->getId(), $token);
	}

	public function getToken() {
		return urlencode($this->token);
	}

	//******************************//

	/**
	 * Get id
	 *
	 * @return integer 
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Get emailaddress
	 *
	 * @return string 
	 */
	public function getEmailaddress() {
		return $this->emailaddress;
	}

	/**
	 * Set gender
	 *
	 * @param string $gender
	 * @return Bulletin
	 */
	public function setGender($gender) {
		$this->gender = $gender;

		return $this;
	}

	/**
	 * Get gender
	 *
	 * @return string 
	 */
	public function getGender() {
		return $this->gender;
	}
}