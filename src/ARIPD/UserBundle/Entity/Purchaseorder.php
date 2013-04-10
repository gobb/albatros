<?php
namespace ARIPD\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="user_purchaseorder")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Purchaseorder {

	public function __construct() {
		$this->setPoid('___00000' . date("ymdHis"));

		$this->setCreatedAt(new \DateTime());
		$this->setUpdatedAt(new \DateTime());

		$this->outgoings = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=255, unique=true)
	 */
	protected $poid;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\ShoppingBundle\Entity\Purchaseorderstatus")
	 * @ORM\JoinColumn(name="status_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $status;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\UserBundle\Entity\User")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
	 */
	protected $user;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $createdAt;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $updatedAt;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\ShoppingBundle\Entity\Payment")
	 * @ORM\JoinColumn(name="payment_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $payment;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\ShoppingBundle\Entity\Transportation")
	 * @ORM\JoinColumn(name="transportation_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $transportation;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\UserBundle\Entity\Postaladdress")
	 * @ORM\JoinColumn(name="deliveryaddress_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $deliveryaddress;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\UserBundle\Entity\Postaladdress")
	 * @ORM\JoinColumn(name="invoiceaddress_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $invoiceaddress;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\ShoppingBundle\Entity\Voucher")
	 * @ORM\JoinColumn(name="voucher_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
	 */
	protected $voucher;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\ShoppingBundle\Entity\Vouchercode")
	 * @ORM\JoinColumn(name="vouchercode_id", referencedColumnName="id", unique=true, nullable=true, onDelete="CASCADE")
	 */
	protected $vouchercode;

	/**
	 * @ORM\Column(type="text")
	 */
	protected $content;

	/**
	 * A unidirectional one-to-many association can be mapped through a join table.
	 * unidirectional many-to-many whereby a unique constraint on one of the join columns
	 * enforces the one-to-many cardinality
	 * 
	 * @ORM\ManyToMany(targetEntity="ARIPD\StockBundle\Entity\Outgoing", cascade={"persist"})
	 * @ORM\JoinTable(name="user_purchaseorder_outgoings",
	 *      joinColumns={@ORM\JoinColumn(name="purchaseorder_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="outgoing_id", referencedColumnName="id", unique=true)}
	 *      )
	 */
	protected $outgoings;

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
		$this->setContent($this->serialize());
	}

	public function serialize() {
		return serialize(
				array($this->payment, $this->transportation,
						$this->deliveryaddress, $this->invoiceaddress,
						$this->vouchercode,));
	}

	public function unserialize($serialized) {
		list($this->payment, $this->transportation, $this->deliveryaddress, $this
				->invoiceaddress, $this->vouchercode) = unserialize($serialized);
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
     * Set poid
     *
     * @param string $poid
     * @return Purchaseorder
     */
    public function setPoid($poid)
    {
        $this->poid = $poid;
    
        return $this;
    }

    /**
     * Get poid
     *
     * @return string 
     */
    public function getPoid()
    {
        return $this->poid;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Purchaseorder
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
     * @return Purchaseorder
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
     * Set content
     *
     * @param string $content
     * @return Purchaseorder
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
     * Set status
     *
     * @param \ARIPD\ShoppingBundle\Entity\Purchaseorderstatus $status
     * @return Purchaseorder
     */
    public function setStatus(\ARIPD\ShoppingBundle\Entity\Purchaseorderstatus $status = null)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return \ARIPD\ShoppingBundle\Entity\Purchaseorderstatus 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set user
     *
     * @param \ARIPD\UserBundle\Entity\User $user
     * @return Purchaseorder
     */
    public function setUser(\ARIPD\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \ARIPD\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set payment
     *
     * @param \ARIPD\ShoppingBundle\Entity\Payment $payment
     * @return Purchaseorder
     */
    public function setPayment(\ARIPD\ShoppingBundle\Entity\Payment $payment = null)
    {
        $this->payment = $payment;
    
        return $this;
    }

    /**
     * Get payment
     *
     * @return \ARIPD\ShoppingBundle\Entity\Payment 
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * Set transportation
     *
     * @param \ARIPD\ShoppingBundle\Entity\Transportation $transportation
     * @return Purchaseorder
     */
    public function setTransportation(\ARIPD\ShoppingBundle\Entity\Transportation $transportation = null)
    {
        $this->transportation = $transportation;
    
        return $this;
    }

    /**
     * Get transportation
     *
     * @return \ARIPD\ShoppingBundle\Entity\Transportation 
     */
    public function getTransportation()
    {
        return $this->transportation;
    }

    /**
     * Set deliveryaddress
     *
     * @param \ARIPD\UserBundle\Entity\Postaladdress $deliveryaddress
     * @return Purchaseorder
     */
    public function setDeliveryaddress(\ARIPD\UserBundle\Entity\Postaladdress $deliveryaddress = null)
    {
        $this->deliveryaddress = $deliveryaddress;
    
        return $this;
    }

    /**
     * Get deliveryaddress
     *
     * @return \ARIPD\UserBundle\Entity\Postaladdress 
     */
    public function getDeliveryaddress()
    {
        return $this->deliveryaddress;
    }

    /**
     * Set invoiceaddress
     *
     * @param \ARIPD\UserBundle\Entity\Postaladdress $invoiceaddress
     * @return Purchaseorder
     */
    public function setInvoiceaddress(\ARIPD\UserBundle\Entity\Postaladdress $invoiceaddress = null)
    {
        $this->invoiceaddress = $invoiceaddress;
    
        return $this;
    }

    /**
     * Get invoiceaddress
     *
     * @return \ARIPD\UserBundle\Entity\Postaladdress 
     */
    public function getInvoiceaddress()
    {
        return $this->invoiceaddress;
    }

    /**
     * Set voucher
     *
     * @param \ARIPD\ShoppingBundle\Entity\Voucher $voucher
     * @return Purchaseorder
     */
    public function setVoucher(\ARIPD\ShoppingBundle\Entity\Voucher $voucher = null)
    {
        $this->voucher = $voucher;
    
        return $this;
    }

    /**
     * Get voucher
     *
     * @return \ARIPD\ShoppingBundle\Entity\Voucher 
     */
    public function getVoucher()
    {
        return $this->voucher;
    }

    /**
     * Set vouchercode
     *
     * @param \ARIPD\ShoppingBundle\Entity\Vouchercode $vouchercode
     * @return Purchaseorder
     */
    public function setVouchercode(\ARIPD\ShoppingBundle\Entity\Vouchercode $vouchercode = null)
    {
        $this->vouchercode = $vouchercode;
    
        return $this;
    }

    /**
     * Get vouchercode
     *
     * @return \ARIPD\ShoppingBundle\Entity\Vouchercode 
     */
    public function getVouchercode()
    {
        return $this->vouchercode;
    }

    /**
     * Add outgoings
     *
     * @param \ARIPD\StockBundle\Entity\Outgoing $outgoings
     * @return Purchaseorder
     */
    public function addOutgoing(\ARIPD\StockBundle\Entity\Outgoing $outgoings)
    {
        $this->outgoings[] = $outgoings;
    
        return $this;
    }

    /**
     * Remove outgoings
     *
     * @param \ARIPD\StockBundle\Entity\Outgoing $outgoings
     */
    public function removeOutgoing(\ARIPD\StockBundle\Entity\Outgoing $outgoings)
    {
        $this->outgoings->removeElement($outgoings);
    }

    /**
     * Get outgoings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOutgoings()
    {
        return $this->outgoings;
    }
}