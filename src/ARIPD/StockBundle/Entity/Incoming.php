<?php
namespace ARIPD\StockBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="stock_incoming")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class Incoming {
	
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
	 * @ORM\ManyToOne(targetEntity="ARIPD\SCMBundle\Entity\Supplier")
	 * @ORM\JoinColumn(name="supplier_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
	 */
	protected $supplier;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\StoreBundle\Entity\Model", inversedBy="incomings")
	 * @ORM\JoinColumn(name="model_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
	 */
	protected $model;
	
	/**
	 * @ORM\Column(type="integer", nullable=false)
	 */
	protected $quantity;
	
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
	 * @ORM\Column(type="date", nullable=false)
	 */
	protected $invoicedAt;
	
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
     * @return Incoming
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
     * Set price
     *
     * @param float $price
     * @return Incoming
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
     * Set invoicedAt
     *
     * @param \DateTime $invoicedAt
     * @return Incoming
     */
    public function setInvoicedAt($invoicedAt)
    {
        $this->invoicedAt = $invoicedAt;
    
        return $this;
    }

    /**
     * Get invoicedAt
     *
     * @return \DateTime 
     */
    public function getInvoicedAt()
    {
        return $this->invoicedAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Incoming
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
     * @return Incoming
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
     * Set supplier
     *
     * @param \ARIPD\SCMBundle\Entity\Supplier $supplier
     * @return Incoming
     */
    public function setSupplier(\ARIPD\SCMBundle\Entity\Supplier $supplier)
    {
        $this->supplier = $supplier;
    
        return $this;
    }

    /**
     * Get supplier
     *
     * @return \ARIPD\SCMBundle\Entity\Supplier 
     */
    public function getSupplier()
    {
        return $this->supplier;
    }

    /**
     * Set model
     *
     * @param \ARIPD\StoreBundle\Entity\Model $model
     * @return Incoming
     */
    public function setModel(\ARIPD\StoreBundle\Entity\Model $model)
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
     * @return Incoming
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