<?php
namespace ARIPD\DMSBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="dms_salesorder")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Salesorder {
	
	public function __construct() {
		$this->setSoid('___00000' . date("ymdHis"));

		$this->setCreatedAt(new \DateTime());
		$this->setUpdatedAt(new \DateTime());
		
		$this->outgoings = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=255, unique=true)
	 */
	protected $soid;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\DMSBundle\Entity\SalesorderStatus")
	 * @ORM\JoinColumn(name="status_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $status;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\UserBundle\Entity\User")
	 * @ORM\JoinColumn(name="dealer_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $dealer;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\UserBundle\Entity\User")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
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
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $content;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\StockBundle\Entity\Outgoing", mappedBy="salesorder")
	 */
	protected $outgoings;
	
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
     * Set soid
     *
     * @param string $soid
     * @return Salesorder
     */
    public function setSoid($soid)
    {
        $this->soid = $soid;
    
        return $this;
    }

    /**
     * Get soid
     *
     * @return string 
     */
    public function getSoid()
    {
        return $this->soid;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Salesorder
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
     * @return Salesorder
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
     * @return Salesorder
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
     * @param \ARIPD\DMSBundle\Entity\SalesorderStatus $status
     * @return Salesorder
     */
    public function setStatus(\ARIPD\DMSBundle\Entity\SalesorderStatus $status = null)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return \ARIPD\DMSBundle\Entity\SalesorderStatus 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set dealer
     *
     * @param \ARIPD\UserBundle\Entity\User $dealer
     * @return Salesorder
     */
    public function setDealer(\ARIPD\UserBundle\Entity\User $dealer = null)
    {
        $this->dealer = $dealer;
    
        return $this;
    }

    /**
     * Get dealer
     *
     * @return \ARIPD\UserBundle\Entity\User 
     */
    public function getDealer()
    {
        return $this->dealer;
    }

    /**
     * Set user
     *
     * @param \ARIPD\UserBundle\Entity\User $user
     * @return Salesorder
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
     * Add outgoings
     *
     * @param \ARIPD\StockBundle\Entity\Outgoing $outgoings
     * @return Salesorder
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