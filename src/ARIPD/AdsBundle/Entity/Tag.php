<?php
namespace ARIPD\AdsBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ads_tag")
 * @ORM\Entity
 */
class Tag {
	
	public function __construct() {
		$this->advertisements = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	protected $name;

	/**
	 * @ORM\ManyToMany(targetEntity="ARIPD\AdsBundle\Entity\Advertisement", mappedBy="tags")
	 */
	protected $advertisements;

	public function __toString() {
		return $this->getName();
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
     * @return Tag
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
     * Add advertisements
     *
     * @param \ARIPD\AdsBundle\Entity\Advertisement $advertisements
     * @return Tag
     */
    public function addAdvertisement(\ARIPD\AdsBundle\Entity\Advertisement $advertisements)
    {
        $this->advertisements[] = $advertisements;
    
        return $this;
    }

    /**
     * Remove advertisements
     *
     * @param \ARIPD\AdsBundle\Entity\Advertisement $advertisements
     */
    public function removeAdvertisement(\ARIPD\AdsBundle\Entity\Advertisement $advertisements)
    {
        $this->advertisements->removeElement($advertisements);
    }

    /**
     * Get advertisements
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAdvertisements()
    {
        return $this->advertisements;
    }
}