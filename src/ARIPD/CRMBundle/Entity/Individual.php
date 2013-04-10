<?php
namespace ARIPD\CRMBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="crm_individual")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Individual {

	public function __construct() {
		$this->setCreatedAt(new \DateTime());
		$this->setUpdatedAt(new \DateTime());

		$this->tags = new \Doctrine\Common\Collections\ArrayCollection();
		$this->images = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ManyToMany(targetEntity="ARIPD\CRMBundle\Entity\Tag", inversedBy="individuals", cascade={"persist"})
	 * @ORM\JoinTable(name="crm_individuals_tags",
	 *      joinColumns={@ORM\JoinColumn(name="individual_id", referencedColumnName="id", onDelete="CASCADE")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id", onDelete="CASCADE")}
	 * )
	 */
	protected $tags;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $idno;

	/**
	 * @ORM\Column(type="string", length=1, nullable=true)
	 * @Assert\NotBlank(message = "This value should not be blank.")
	 * @Assert\Choice(
	 *     choices = { "M", "F" },
	 *     message = "Choose a valid gender."
	 * )
	 */
	protected $gender;

	/**
	 * @ORM\Column(type="date", nullable=true)
	 */
	protected $birthdate;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	protected $firstname;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	protected $middlename;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	protected $lastname;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\CRMBundle\Entity\Image", mappedBy="individual")
	 */
	protected $images;

	/**
	 * @ORM\OneToOne(targetEntity="ARIPD\CRMBundle\Entity\Image")
	 * @ORM\JoinColumn(name="image_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
	 */
	protected $defaultimage;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $createdAt;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $updatedAt;

	public function getFullname() {
		return (string) $this->getFirstname() . ' ' . $this->getMiddlename() . ' ' . $this->getLastname();
	}

	/**
	 * @ORM\PreUpdate
	 */
	public function setPreUpdate() {
		$this->setUpdatedAt(new \DateTime());
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
	 * Set idno
	 *
	 * @param string $idno
	 * @return Individual
	 */
	public function setIdno($idno) {
		$this->idno = $idno;

		return $this;
	}

	/**
	 * Get idno
	 *
	 * @return string 
	 */
	public function getIdno() {
		return $this->idno;
	}

	/**
	 * Set gender
	 *
	 * @param string $gender
	 * @return Individual
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

	/**
	 * Set birthdate
	 *
	 * @param \DateTime $birthdate
	 * @return Individual
	 */
	public function setBirthdate($birthdate) {
		$this->birthdate = $birthdate;

		return $this;
	}

	/**
	 * Get birthdate
	 *
	 * @return \DateTime 
	 */
	public function getBirthdate() {
		return $this->birthdate;
	}

	/**
	 * Set firstname
	 *
	 * @param string $firstname
	 * @return Individual
	 */
	public function setFirstname($firstname) {
		$this->firstname = $firstname;

		return $this;
	}

	/**
	 * Get firstname
	 *
	 * @return string 
	 */
	public function getFirstname() {
		return $this->firstname;
	}

	/**
	 * Set middlename
	 *
	 * @param string $middlename
	 * @return Individual
	 */
	public function setMiddlename($middlename) {
		$this->middlename = $middlename;

		return $this;
	}

	/**
	 * Get middlename
	 *
	 * @return string 
	 */
	public function getMiddlename() {
		return $this->middlename;
	}

	/**
	 * Set lastname
	 *
	 * @param string $lastname
	 * @return Individual
	 */
	public function setLastname($lastname) {
		$this->lastname = $lastname;

		return $this;
	}

	/**
	 * Get lastname
	 *
	 * @return string 
	 */
	public function getLastname() {
		return $this->lastname;
	}

	/**
	 * Set createdAt
	 *
	 * @param \DateTime $createdAt
	 * @return Individual
	 */
	public function setCreatedAt($createdAt) {
		$this->createdAt = $createdAt;

		return $this;
	}

	/**
	 * Get createdAt
	 *
	 * @return \DateTime 
	 */
	public function getCreatedAt() {
		return $this->createdAt;
	}

	/**
	 * Set updatedAt
	 *
	 * @param \DateTime $updatedAt
	 * @return Individual
	 */
	public function setUpdatedAt($updatedAt) {
		$this->updatedAt = $updatedAt;

		return $this;
	}

	/**
	 * Get updatedAt
	 *
	 * @return \DateTime 
	 */
	public function getUpdatedAt() {
		return $this->updatedAt;
	}

	/**
	 * Add tags
	 *
	 * @param \ARIPD\CRMBundle\Entity\Tag $tags
	 * @return Individual
	 */
	public function addTag(\ARIPD\CRMBundle\Entity\Tag $tags) {
		$this->tags[] = $tags;

		return $this;
	}

	/**
	 * Remove tags
	 *
	 * @param \ARIPD\CRMBundle\Entity\Tag $tags
	 */
	public function removeTag(\ARIPD\CRMBundle\Entity\Tag $tags) {
		$this->tags->removeElement($tags);
	}

	/**
	 * Get tags
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getTags() {
		return $this->tags;
	}

	/**
	 * Add images
	 *
	 * @param \ARIPD\CRMBundle\Entity\Image $images
	 * @return Individual
	 */
	public function addImage(\ARIPD\CRMBundle\Entity\Image $images) {
		$this->images[] = $images;

		return $this;
	}

	/**
	 * Remove images
	 *
	 * @param \ARIPD\CRMBundle\Entity\Image $images
	 */
	public function removeImage(\ARIPD\CRMBundle\Entity\Image $images) {
		$this->images->removeElement($images);
	}

	/**
	 * Get images
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getImages() {
		return $this->images;
	}

	/**
	 * Set defaultimage
	 *
	 * @param \ARIPD\CRMBundle\Entity\Image $defaultimage
	 * @return Individual
	 */
	public function setDefaultimage(
			\ARIPD\CRMBundle\Entity\Image $defaultimage = null) {
		$this->defaultimage = $defaultimage;

		return $this;
	}

	/**
	 * Get defaultimage
	 *
	 * @return \ARIPD\CRMBundle\Entity\Image 
	 */
	public function getDefaultimage() {
		return $this->defaultimage;
	}
}