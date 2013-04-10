<?php
namespace ARIPD\AdminBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="admin_fxrate")
 * @ORM\Entity
 */
class Fxrate {
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="date")
	 */
	protected $date;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\AdminBundle\Entity\Iso4217", inversedBy="fxrates")
	 * @ORM\JoinColumn(name="iso4217_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $iso4217;
	
	/**
	 * @ORM\Column(type="decimal", scale=5)
	 */
	protected $forexBuying;

	/**
	 * @ORM\Column(type="decimal", scale=5)
	 */
	protected $forexSelling;

	/**
	 * @ORM\Column(type="decimal", scale=5)
	 */
	protected $banknoteBuying;

	/**
	 * @ORM\Column(type="decimal", scale=5)
	 */
	protected $banknoteSelling;

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
     * Set date
     *
     * @param \DateTime $date
     * @return Fxrate
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set forexBuying
     *
     * @param float $forexBuying
     * @return Fxrate
     */
    public function setForexBuying($forexBuying)
    {
        $this->forexBuying = $forexBuying;
    
        return $this;
    }

    /**
     * Get forexBuying
     *
     * @return float 
     */
    public function getForexBuying()
    {
        return $this->forexBuying;
    }

    /**
     * Set forexSelling
     *
     * @param float $forexSelling
     * @return Fxrate
     */
    public function setForexSelling($forexSelling)
    {
        $this->forexSelling = $forexSelling;
    
        return $this;
    }

    /**
     * Get forexSelling
     *
     * @return float 
     */
    public function getForexSelling()
    {
        return $this->forexSelling;
    }

    /**
     * Set banknoteBuying
     *
     * @param float $banknoteBuying
     * @return Fxrate
     */
    public function setBanknoteBuying($banknoteBuying)
    {
        $this->banknoteBuying = $banknoteBuying;
    
        return $this;
    }

    /**
     * Get banknoteBuying
     *
     * @return float 
     */
    public function getBanknoteBuying()
    {
        return $this->banknoteBuying;
    }

    /**
     * Set banknoteSelling
     *
     * @param float $banknoteSelling
     * @return Fxrate
     */
    public function setBanknoteSelling($banknoteSelling)
    {
        $this->banknoteSelling = $banknoteSelling;
    
        return $this;
    }

    /**
     * Get banknoteSelling
     *
     * @return float 
     */
    public function getBanknoteSelling()
    {
        return $this->banknoteSelling;
    }

    /**
     * Set iso4217
     *
     * @param \ARIPD\AdminBundle\Entity\Iso4217 $iso4217
     * @return Fxrate
     */
    public function setIso4217(\ARIPD\AdminBundle\Entity\Iso4217 $iso4217 = null)
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
}