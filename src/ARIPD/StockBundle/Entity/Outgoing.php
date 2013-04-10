<?php
namespace ARIPD\StockBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="stock_outgoing")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class Outgoing {
	
	public function __construct() {
		$this->setCreatedAt(new \DateTime());
		$this->setUpdatedAt(new \DateTime());
	}

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\DMSBundle\Entity\Salesorder", inversedBy="outgoings")
	 * @ORM\JoinColumn(name="salesorder_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $salesorder;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\StoreBundle\Entity\Model", inversedBy="outgoings")
	 * @ORM\JoinColumn(name="model_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $model;

	/**
	 * @ORM\Column(type="integer")
	 */
	protected $quantity;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $quantityCancelled;

	/**
	 * @ORM\Column(type="decimal", scale=2, nullable=false)
	 */
	protected $price;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\AdminBundle\Entity\Iso4217")
	 * @ORM\JoinColumn(name="iso4217_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
	 */
	protected $iso4217;
	
	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $createdAt;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $updatedAt;

	/**
	 * @ORM\PreUpdate
	 */
	public function setPreUpdate() {
		$this->setUpdatedAt(new \DateTime());
	}

	/**
	 * @ORM\PrePersist
	 */
	public function setPrePersist() {
		$this->setPrice($this->getModel()->getPrice());
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
     * Set quantity
     *
     * @param integer $quantity
     * @return Outgoing
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    
        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set quantityCancelled
     *
     * @param integer $quantityCancelled
     * @return Outgoing
     */
    public function setQuantityCancelled($quantityCancelled)
    {
        $this->quantityCancelled = $quantityCancelled;
    
        return $this;
    }

    /**
     * Get quantityCancelled
     *
     * @return integer 
     */
    public function getQuantityCancelled()
    {
        return $this->quantityCancelled;
    }

    /**
     * Set price
     *
     * @param float $price
     * @return Outgoing
     */
    public function setPrice($price)
    {
        $this->price = $price;
    
        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Outgoing
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Outgoing
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set salesorder
     *
     * @param \ARIPD\DMSBundle\Entity\Salesorder $salesorder
     * @return Outgoing
     */
    public function setSalesorder(\ARIPD\DMSBundle\Entity\Salesorder $salesorder = null)
    {
        $this->salesorder = $salesorder;
    
        return $this;
    }

    /**
     * Get salesorder
     *
     * @return \ARIPD\DMSBundle\Entity\Salesorder 
     */
    public function getSalesorder()
    {
        return $this->salesorder;
    }

    /**
     * Set model
     *
     * @param \ARIPD\StoreBundle\Entity\Model $model
     * @return Outgoing
     */
    public function setModel(\ARIPD\StoreBundle\Entity\Model $model = null)
    {
        $this->model = $model;
    
        return $this;
    }

    /**
     * Get model
     *
     * @return \ARIPD\StoreBundle\Entity\Model 
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set iso4217
     *
     * @param \ARIPD\AdminBundle\Entity\Iso4217 $iso4217
     * @return Outgoing
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
}