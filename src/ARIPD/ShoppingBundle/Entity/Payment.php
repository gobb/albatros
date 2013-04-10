<?php
namespace ARIPD\ShoppingBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Table(name="shopping_payment")
 * @ORM\Entity
 * @JMS\ExclusionPolicy("all")
 */
class Payment {
	
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @JMS\Expose
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string")
	 * @JMS\Expose
	 */
	protected $parameter;
	
	/**
	 * @ORM\Column(type="integer")
	 * @JMS\Expose
	 */
	protected $period;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $name;
	
	public function getFullname(){
		return sprintf('%s, %s, %s', $this->getPaymentgroup()->getPaymentgrouptype()->getName(), $this->getPaymentgroup()->getName(), $this->getName());
	}
	
	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\ShoppingBundle\Entity\Paymentgroup", inversedBy="payments")
	 * @ORM\JoinColumn(name="paymentgroup_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $paymentgroup;

	/**
	 * @ORM\Column(type="decimal", scale=4, nullable=false)
	 * @JMS\Expose
	 */
	protected $impactRate = 0;

	/**
	 * @ORM\Column(type="decimal", scale=2, nullable=false)
	 * @JMS\Expose
	 */
	protected $impactPrice = 0;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\AdminBundle\Entity\Iso4217")
	 * @ORM\JoinColumn(name="iso4217_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
	 */
	protected $iso4217;
	
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
     * Set period
     *
     * @param integer $period
     * @return Payment
     */
    public function setPeriod($period)
    {
        $this->period = $period;
    
        return $this;
    }

    /**
     * Get period
     *
     * @return integer 
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Payment
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
     * @return Payment
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
     * @return Payment
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
     * Set paymentgroup
     *
     * @param \ARIPD\ShoppingBundle\Entity\Paymentgroup $paymentgroup
     * @return Payment
     */
    public function setPaymentgroup(\ARIPD\ShoppingBundle\Entity\Paymentgroup $paymentgroup = null)
    {
        $this->paymentgroup = $paymentgroup;
    
        return $this;
    }

    /**
     * Get paymentgroup
     *
     * @return \ARIPD\ShoppingBundle\Entity\Paymentgroup 
     */
    public function getPaymentgroup()
    {
        return $this->paymentgroup;
    }

    /**
     * Set iso4217
     *
     * @param \ARIPD\AdminBundle\Entity\Iso4217 $iso4217
     * @return Payment
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
     * Set parameter
     *
     * @param string $parameter
     * @return Payment
     */
    public function setParameter($parameter)
    {
        $this->parameter = $parameter;
    
        return $this;
    }

    /**
     * Get parameter
     *
     * @return string 
     */
    public function getParameter()
    {
        return $this->parameter;
    }
}