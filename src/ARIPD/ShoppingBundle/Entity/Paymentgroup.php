<?php
namespace ARIPD\ShoppingBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Table(name="shopping_paymentgroup")
 * @ORM\Entity
 * @JMS\ExclusionPolicy("all")
 */
class Paymentgroup {
	
	public function __construct() {
		$this->payments = new \Doctrine\Common\Collections\ArrayCollection();
		$this->transportations = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $active = false;
	
	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\ShoppingBundle\Entity\Paymentgrouptype", inversedBy="paymentgroups")
	 * @ORM\JoinColumn(name="paymentgrouptype_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $paymentgrouptype;

	/**
	 * @ORM\Column(type="string", length=255)
	 * @JMS\SerializedName("text")
	 * @JMS\Expose
	 */
	protected $name;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\AdminBundle\Entity\Iso4217")
	 * @ORM\JoinColumn(name="iso4217_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
	 */
	protected $iso4217;
	
	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $content;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $gate1;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $gate2;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $mid;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $tid;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $posid;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $clientid;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $storetype;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $storekey;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\ShoppingBundle\Entity\Payment", mappedBy="paymentgroup")
	 */
	protected $payments;

	/**
	 * @ORM\ManyToMany(targetEntity="ARIPD\ShoppingBundle\Entity\Transportation")
	 * @ORM\JoinTable(name="shopping_paymentgroups_transportations")
	 */
	protected $transportations;

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
     * Set active
     *
     * @param boolean $active
     * @return Paymentgroup
     */
    public function setActive($active)
    {
        $this->active = $active;
    
        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Paymentgroup
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
     * Set content
     *
     * @param string $content
     * @return Paymentgroup
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set gate1
     *
     * @param string $gate1
     * @return Paymentgroup
     */
    public function setGate1($gate1)
    {
        $this->gate1 = $gate1;
    
        return $this;
    }

    /**
     * Get gate1
     *
     * @return string 
     */
    public function getGate1()
    {
        return $this->gate1;
    }

    /**
     * Set gate2
     *
     * @param string $gate2
     * @return Paymentgroup
     */
    public function setGate2($gate2)
    {
        $this->gate2 = $gate2;
    
        return $this;
    }

    /**
     * Get gate2
     *
     * @return string 
     */
    public function getGate2()
    {
        return $this->gate2;
    }

    /**
     * Set mid
     *
     * @param string $mid
     * @return Paymentgroup
     */
    public function setMid($mid)
    {
        $this->mid = $mid;
    
        return $this;
    }

    /**
     * Get mid
     *
     * @return string 
     */
    public function getMid()
    {
        return $this->mid;
    }

    /**
     * Set tid
     *
     * @param string $tid
     * @return Paymentgroup
     */
    public function setTid($tid)
    {
        $this->tid = $tid;
    
        return $this;
    }

    /**
     * Get tid
     *
     * @return string 
     */
    public function getTid()
    {
        return $this->tid;
    }

    /**
     * Set posid
     *
     * @param string $posid
     * @return Paymentgroup
     */
    public function setPosid($posid)
    {
        $this->posid = $posid;
    
        return $this;
    }

    /**
     * Get posid
     *
     * @return string 
     */
    public function getPosid()
    {
        return $this->posid;
    }

    /**
     * Set clientid
     *
     * @param string $clientid
     * @return Paymentgroup
     */
    public function setClientid($clientid)
    {
        $this->clientid = $clientid;
    
        return $this;
    }

    /**
     * Get clientid
     *
     * @return string 
     */
    public function getClientid()
    {
        return $this->clientid;
    }

    /**
     * Set storetype
     *
     * @param string $storetype
     * @return Paymentgroup
     */
    public function setStoretype($storetype)
    {
        $this->storetype = $storetype;
    
        return $this;
    }

    /**
     * Get storetype
     *
     * @return string 
     */
    public function getStoretype()
    {
        return $this->storetype;
    }

    /**
     * Set storekey
     *
     * @param string $storekey
     * @return Paymentgroup
     */
    public function setStorekey($storekey)
    {
        $this->storekey = $storekey;
    
        return $this;
    }

    /**
     * Get storekey
     *
     * @return string 
     */
    public function getStorekey()
    {
        return $this->storekey;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Paymentgroup
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
     * Set paymentgrouptype
     *
     * @param \ARIPD\ShoppingBundle\Entity\Paymentgrouptype $paymentgrouptype
     * @return Paymentgroup
     */
    public function setPaymentgrouptype(\ARIPD\ShoppingBundle\Entity\Paymentgrouptype $paymentgrouptype = null)
    {
        $this->paymentgrouptype = $paymentgrouptype;
    
        return $this;
    }

    /**
     * Get paymentgrouptype
     *
     * @return \ARIPD\ShoppingBundle\Entity\Paymentgrouptype 
     */
    public function getPaymentgrouptype()
    {
        return $this->paymentgrouptype;
    }

    /**
     * Set iso4217
     *
     * @param \ARIPD\AdminBundle\Entity\Iso4217 $iso4217
     * @return Paymentgroup
     */
    public function setIso4217(\ARIPD\AdminBundle\Entity\Iso4217 $iso4217)
    {
        $this->iso4217 = $iso4217;
    
        return $this;
    }

    /**
     * Get iso4217
     *
     * @return \ARIPD\AdminBundle\Entity\Iso4217 
     */
    public function getIso4217()
    {
        return $this->iso4217;
    }

    /**
     * Add payments
     *
     * @param \ARIPD\ShoppingBundle\Entity\Payment $payments
     * @return Paymentgroup
     */
    public function addPayment(\ARIPD\ShoppingBundle\Entity\Payment $payments)
    {
        $this->payments[] = $payments;
    
        return $this;
    }

    /**
     * Remove payments
     *
     * @param \ARIPD\ShoppingBundle\Entity\Payment $payments
     */
    public function removePayment(\ARIPD\ShoppingBundle\Entity\Payment $payments)
    {
        $this->payments->removeElement($payments);
    }

    /**
     * Get payments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPayments()
    {
        return $this->payments;
    }

    /**
     * Add transportations
     *
     * @param \ARIPD\ShoppingBundle\Entity\Transportation $transportations
     * @return Paymentgroup
     */
    public function addTransportation(\ARIPD\ShoppingBundle\Entity\Transportation $transportations)
    {
        $this->transportations[] = $transportations;
    
        return $this;
    }

    /**
     * Remove transportations
     *
     * @param \ARIPD\ShoppingBundle\Entity\Transportation $transportations
     */
    public function removeTransportation(\ARIPD\ShoppingBundle\Entity\Transportation $transportations)
    {
        $this->transportations->removeElement($transportations);
    }

    /**
     * Get transportations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTransportations()
    {
        return $this->transportations;
    }
}