<?php
namespace ARIPD\StoreBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="store_banner")
 * @ORM\Entity
 */
class Banner {
	
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\AdminBundle\Entity\Iso639")
	 * @ORM\JoinColumn(name="iso639_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $iso639;
	
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
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $sorting;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $startingAt;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $endingAt;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $href;

	/**
	 * @ORM\Column(type="string", length=255, nullable=false)
	 */
	protected $url;
	
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
     * @return Banner
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
     * @return Banner
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
     * Set sorting
     *
     * @param integer $sorting
     * @return Banner
     */
    public function setSorting($sorting)
    {
        $this->sorting = $sorting;
    
        return $this;
    }

    /**
     * Get sorting
     *
     * @return integer 
     */
    public function getSorting()
    {
        return $this->sorting;
    }

    /**
     * Set startingAt
     *
     * @param \DateTime $startingAt
     * @return Banner
     */
    public function setStartingAt($startingAt)
    {
        $this->startingAt = $startingAt;
    
        return $this;
    }

    /**
     * Get startingAt
     *
     * @return \DateTime 
     */
    public function getStartingAt()
    {
        return $this->startingAt;
    }

    /**
     * Set endingAt
     *
     * @param \DateTime $endingAt
     * @return Banner
     */
    public function setEndingAt($endingAt)
    {
        $this->endingAt = $endingAt;
    
        return $this;
    }

    /**
     * Get endingAt
     *
     * @return \DateTime 
     */
    public function getEndingAt()
    {
        return $this->endingAt;
    }

    /**
     * Set href
     *
     * @param string $href
     * @return Banner
     */
    public function setHref($href)
    {
        $this->href = $href;
    
        return $this;
    }

    /**
     * Get href
     *
     * @return string 
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Banner
     */
    public function setUrl($url)
    {
        $this->url = $url;
    
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set iso639
     *
     * @param \ARIPD\AdminBundle\Entity\Iso639 $iso639
     * @return Banner
     */
    public function setIso639(\ARIPD\AdminBundle\Entity\Iso639 $iso639 = null)
    {
        $this->iso639 = $iso639;
    
        return $this;
    }

    /**
     * Get iso639
     *
     * @return \ARIPD\AdminBundle\Entity\Iso639 
     */
    public function getIso639()
    {
        return $this->iso639;
    }
}