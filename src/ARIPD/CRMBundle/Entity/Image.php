<?php
namespace ARIPD\CRMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="crm_image")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Image {
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlank(message = "This value should not be blank.")
	 */
	protected $name;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $description;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\CRMBundle\Entity\Individual", inversedBy="images")
	 * @ORM\JoinColumn(name="individual_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $individual;
	
	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $path;

	/**
	 * @Assert\File(
	 * 		maxSize = "1M", 
	 * 		mimeTypes = {"image/jpeg", "image/pjpeg", "image/gif", "image/png"}, 
	 * 		mimeTypesMessage = "The mime type of the file is invalid ({{ type }}). Allowed mime types are {{ types }}.", 
	 * 		maxSizeMessage = "The file is too large ({{ size }}). Allowed maximum size is {{ limit }}."
	 * )
	 */
	protected $file;

	public function getFile() {
		return $this->file;
	}
	
	public function setFile($file) {
		$this->file = $file;
	
		return $this;
	}
	
	public function getAbsolutePath() {
		return null === $this->path ? null
				: $this->getUploadRootDir() . '/' . $this->path;
	}

	public function getWebPath() {
		return null === $this->path ? null
				: $this->getUploadDir() . '/' . $this->path;
	}

	protected function getUploadRootDir() {
		return __DIR__ . '/../../../../web/' . $this->getUploadDir();
	}

	protected function getUploadDir() {
		return 'uploads/_site';
	}

	/**
	 * @ORM\PrePersist()
	 * @ORM\PreUpdate()
	 */
	public function preUpload() {
		if (null !== $this->file) {
			// do whatever you want to generate a unique name
			$this->removeUpload();
			$this->path = uniqid() . '.' . $this->file->guessExtension();
		}
	}

	/**
	 * @ORM\PostPersist()
	 * @ORM\PostUpdate()
	 */
	public function upload() {
		// the file property can be empty if the field is not required
		if (null === $this->file) {
			return;
		}

		// if there is an error when moving the file, an exception will
		// be automatically thrown by move(). This will properly prevent
		// the entity from being persisted to the database on error
		$this->file->move($this->getUploadRootDir(), $this->path);

		// clean up the file property as you won't need it anymore
		unset($this->file);
	}

	/**
	 * @ORM\PostRemove()
	 */
	public function removeUpload() {
		if ($file = $this->getAbsolutePath()) {
			unlink($file);
		}
	}

	//******************************//
	

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Image
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Image
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Image
     */
    public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set individual
     *
     * @param \ARIPD\CRMBundle\Entity\Individual $individual
     * @return Image
     */
    public function setIndividual(\ARIPD\CRMBundle\Entity\Individual $individual = null)
    {
        $this->individual = $individual;
    
        return $this;
    }

    /**
     * Get individual
     *
     * @return \ARIPD\CRMBundle\Entity\Individual 
     */
    public function getIndividual()
    {
        return $this->individual;
    }
}