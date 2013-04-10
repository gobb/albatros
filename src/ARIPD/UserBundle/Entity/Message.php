<?php
namespace ARIPD\UserBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="user_message")
 * @ORM\Entity(repositoryClass="ARIPD\UserBundle\Repository\MessageRepository")
 */
class Message {

	public function __construct() {
		$this->setCreatedAt(new \DateTime());
		$this->subs = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\UserBundle\Entity\User")
	 * @ORM\JoinColumn(name="producer_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $producer;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\UserBundle\Entity\User")
	 * @ORM\JoinColumn(name="consumer_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $consumer;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	protected $subject;

	/**
	 * @ORM\Column(type="text")
	 */
	protected $body;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $createdAt;

	/**
	 * @ORM\Column(nullable=true)
	 */
	protected $ip;

	/**
	 * @ORM\Column(type="string", length=2)
	 */
	protected $statusProducer;

	/**
	 * @ORM\Column(type="string", length=2)
	 */
	protected $statusConsumer;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\UserBundle\Entity\Message", inversedBy="subs")
	 * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $parent;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\UserBundle\Entity\Message", mappedBy="parent")
	 */
	protected $subs;

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
     * Set subject
     *
     * @param string $subject
     * @return Message
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    
        return $this;
    }

    /**
     * Get subject
     *
     * @return string 
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return Message
     */
    public function setBody($body)
    {
        $this->body = $body;
    
        return $this;
    }

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Message
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
     * Set ip
     *
     * @param string $ip
     * @return Message
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    
        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set statusProducer
     *
     * @param string $statusProducer
     * @return Message
     */
    public function setStatusProducer($statusProducer)
    {
        $this->statusProducer = $statusProducer;
    
        return $this;
    }

    /**
     * Get statusProducer
     *
     * @return string 
     */
    public function getStatusProducer()
    {
        return $this->statusProducer;
    }

    /**
     * Set statusConsumer
     *
     * @param string $statusConsumer
     * @return Message
     */
    public function setStatusConsumer($statusConsumer)
    {
        $this->statusConsumer = $statusConsumer;
    
        return $this;
    }

    /**
     * Get statusConsumer
     *
     * @return string 
     */
    public function getStatusConsumer()
    {
        return $this->statusConsumer;
    }

    /**
     * Set producer
     *
     * @param \ARIPD\UserBundle\Entity\User $producer
     * @return Message
     */
    public function setProducer(\ARIPD\UserBundle\Entity\User $producer = null)
    {
        $this->producer = $producer;
    
        return $this;
    }

    /**
     * Get producer
     *
     * @return \ARIPD\UserBundle\Entity\User 
     */
    public function getProducer()
    {
        return $this->producer;
    }

    /**
     * Set consumer
     *
     * @param \ARIPD\UserBundle\Entity\User $consumer
     * @return Message
     */
    public function setConsumer(\ARIPD\UserBundle\Entity\User $consumer = null)
    {
        $this->consumer = $consumer;
    
        return $this;
    }

    /**
     * Get consumer
     *
     * @return \ARIPD\UserBundle\Entity\User 
     */
    public function getConsumer()
    {
        return $this->consumer;
    }

    /**
     * Set parent
     *
     * @param \ARIPD\UserBundle\Entity\Message $parent
     * @return Message
     */
    public function setParent(\ARIPD\UserBundle\Entity\Message $parent = null)
    {
        $this->parent = $parent;
    
        return $this;
    }

    /**
     * Get parent
     *
     * @return \ARIPD\UserBundle\Entity\Message 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add subs
     *
     * @param \ARIPD\UserBundle\Entity\Message $subs
     * @return Message
     */
    public function addSub(\ARIPD\UserBundle\Entity\Message $subs)
    {
        $this->subs[] = $subs;
    
        return $this;
    }

    /**
     * Remove subs
     *
     * @param \ARIPD\UserBundle\Entity\Message $subs
     */
    public function removeSub(\ARIPD\UserBundle\Entity\Message $subs)
    {
        $this->subs->removeElement($subs);
    }

    /**
     * Get subs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSubs()
    {
        return $this->subs;
    }
}