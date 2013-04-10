<?php
namespace ARIPD\DMSBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="dms_group")
 * @ORM\Entity
 */
class Group {
	
	public function __construct() {
		$this->dealers = new \Doctrine\Common\Collections\ArrayCollection();
	}
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	protected $name;
	
	/**
	 * @ORM\Column(type="decimal", scale=2, nullable=false)
	 */
	protected $impactRate = 0;

	/**
	 * @ORM\Column(type="decimal", scale=2, nullable=false)
	 */
	protected $impactPrice = 0;
	
	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\AdminBundle\Entity\Iso4217")
	 * @ORM\JoinColumn(name="iso4217_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
	 */
	protected $iso4217;
	
	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\DMSBundle\Entity\Dealer", mappedBy="group")
	 */
	protected $dealers;

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
     * @return Group
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
     * Set impactRate
     *
     * @param float $impactRate
     * @return Group
     */
    public function setImpactRate($impactRate)
    {
        $this->impactRate = $impactRate;
    
        return $this;
    }

    /**
     * Get impactRate
     *
     * @return float 
     */
    public function getImpactRate()
    {
        return $this->impactRate;
    }

    /**
     * Set impactPrice
     *
     * @param float $impactPrice
     * @return Group
     */
    public function setImpactPrice($impactPrice)
    {
        $this->impactPrice = $impactPrice;
    
        return $this;
    }

    /**
     * Get impactPrice
     *
     * @return float 
     */
    public function getImpactPrice()
    {
        return $this->impactPrice;
    }

    /**
     * Set iso4217
     *
     * @param \ARIPD\AdminBundle\Entity\Iso4217 $iso4217
     * @return Group
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
     * Add dealers
     *
     * @param \ARIPD\DMSBundle\Entity\Dealer $dealers
     * @return Group
     */
    public function addDealer(\ARIPD\DMSBundle\Entity\Dealer $dealers)
    {
        $this->dealers[] = $dealers;
    
        return $this;
    }

    /**
     * Remove dealers
     *
     * @param \ARIPD\DMSBundle\Entity\Dealer $dealers
     */
    public function removeDealer(\ARIPD\DMSBundle\Entity\Dealer $dealers)
    {
        $this->dealers->removeElement($dealers);
    }

    /**
     * Get dealers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDealers()
    {
        return $this->dealers;
    }
}