<?php
namespace ARIPD\StoreBundle\Entity;
use ARIPD\AdminBundle\Util\ARIPDString;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Table(name="store_branch")
 * @ORM\Entity
 * @JMS\ExclusionPolicy("all")
 */
class Branch {

	public function __construct() {
		$this->images = new \Doctrine\Common\Collections\ArrayCollection();
	}
	
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @JMS\Expose
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 * @JMS\Expose
	 */
	protected $name;

	/**
	 * @ORM\Column(type="string")
	 * @JMS\Expose
	 */
	protected $slug;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $content;
	
	/**
	 * @ORM\Column(type="string", length=255)
	 * @JMS\Expose
	 */
	protected $address;

	/**
	 * @ORM\Column(type="string", length=255)
	 * @JMS\Expose
	 */
	protected $city;

	/**
	 * @ORM\Column(type="string", length=255)
	 * @JMS\Expose
	 */
	protected $state;

	/**
	 * @ORM\Column(type="string", length=255)
	 * @JMS\Expose
	 */
	protected $zip;

	/**
	 * @ORM\Column(type="string", length=255)
	 * @JMS\Expose
	 */
	protected $phone;

	/**
	 * @ORM\Column(type="decimal", precision=10, scale=6)
	 * @JMS\Expose
	 */
	protected $latitude = 0;

	/**
	 * @ORM\Column(type="decimal", precision=10, scale=6)
	 * @JMS\Expose
	 */
	protected $longitude = 0;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\StoreBundle\Entity\Branchimage", mappedBy="branch")
	 */
	protected $images;

	public function getImage() {
		$url = null;
		foreach ($this->getImages() as $image) {
			if ($image->getPrime()) {
				$url = $image->getUrl();
			}
		}
		return $url;
	}

	public function setName($name) {
		$this->name = $name;
		$this->setSlug($this->name);
		return $this;
	}

	public function setSlug($slug) {
		$this->slug = ARIPDString::slugify($slug);
	}

	public function getFulladdress() {
		return sprintf('%s, %s, %s, %s', $this->getAddress(), $this->getCity(), $this->getState(), $this->getZip());
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
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Branch
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
     * Set address
     *
     * @param string $address
     * @return Branch
     */
    public function setAddress($address)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Branch
     */
    public function setCity($city)
    {
        $this->city = $city;
    
        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return Branch
     */
    public function setState($state)
    {
        $this->state = $state;
    
        return $this;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set zip
     *
     * @param string $zip
     * @return Branch
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
    
        return $this;
    }

    /**
     * Get zip
     *
     * @return string 
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Branch
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     * @return Branch
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    
        return $this;
    }

    /**
     * Get latitude
     *
     * @return float 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     * @return Branch
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    
        return $this;
    }

    /**
     * Get longitude
     *
     * @return float 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Add images
     *
     * @param \ARIPD\StoreBundle\Entity\Branchimage $images
     * @return Branch
     */
    public function addImage(\ARIPD\StoreBundle\Entity\Branchimage $images)
    {
        $this->images[] = $images;
    
        return $this;
    }

    /**
     * Remove images
     *
     * @param \ARIPD\StoreBundle\Entity\Branchimage $images
     */
    public function removeImage(\ARIPD\StoreBundle\Entity\Branchimage $images)
    {
        $this->images->removeElement($images);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImages()
    {
        return $this->images;
    }
}