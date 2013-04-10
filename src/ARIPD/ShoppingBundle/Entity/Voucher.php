<?php
namespace ARIPD\ShoppingBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="shopping_voucher")
 * @ORM\Entity(repositoryClass="ARIPD\ShoppingBundle\Repository\VoucherRepository")
 */
class Voucher {
	/**
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=255, unique=true)
	 */
	protected $code;
	
	/**
	 * @ORM\Column(name="name", type="string", length=255)
	 */
	protected $name;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $startingAt;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $endingAt;

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
	 * @ORM\OneToMany(targetEntity="ARIPD\ShoppingBundle\Entity\Vouchercode", mappedBy="voucher")
	 */
	protected $vouchercodes;

	public function __construct() {
		$this->vouchercodes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set code
     *
     * @param string $code
     * @return Voucher
     */
    public function setCode($code)
    {
        $this->code = $code;
    
        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Voucher
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
     * Set startingAt
     *
     * @param \DateTime $startingAt
     * @return Voucher
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
     * @return Voucher
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
     * Set impactRate
     *
     * @param float $impactRate
     * @return Voucher
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
     * @return Voucher
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
     * @return Voucher
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

    /**
     * Add vouchercodes
     *
     * @param \ARIPD\ShoppingBundle\Entity\Vouchercode $vouchercodes
     * @return Voucher
     */
    public function addVouchercode(\ARIPD\ShoppingBundle\Entity\Vouchercode $vouchercodes)
    {
        $this->vouchercodes[] = $vouchercodes;
    
        return $this;
    }

    /**
     * Remove vouchercodes
     *
     * @param \ARIPD\ShoppingBundle\Entity\Vouchercode $vouchercodes
     */
    public function removeVouchercode(\ARIPD\ShoppingBundle\Entity\Vouchercode $vouchercodes)
    {
        $this->vouchercodes->removeElement($vouchercodes);
    }

    /**
     * Get vouchercodes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVouchercodes()
    {
        return $this->vouchercodes;
    }
}