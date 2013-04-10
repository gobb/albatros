<?php
namespace ARIPD\ForumBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="forum_hit")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Hit {
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\UserBundle\Entity\User", inversedBy="forumthreadhits")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $user;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\ForumBundle\Entity\Thread", inversedBy="hits")
	 * @ORM\JoinColumn(name="thread_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $thread;

	/**
	 * @ORM\Column(nullable=true)
	 */
	protected $sessionId;

	/**
	 * @ORM\Column(nullable=true)
	 */
	protected $ip;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $createdAt;

	public function __construct() {
		$this->setCreatedAt(new \DateTime());
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
     * Set sessionId
     *
     * @param string $sessionId
     * @return Hit
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
    
        return $this;
    }

    /**
     * Get sessionId
     *
     * @return string 
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return Hit
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Hit
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
     * Set user
     *
     * @param \ARIPD\UserBundle\Entity\User $user
     * @return Hit
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
     * Set thread
     *
     * @param \ARIPD\ForumBundle\Entity\Thread $thread
     * @return Hit
     */
    public function setThread(\ARIPD\ForumBundle\Entity\Thread $thread = null)
    {
        $this->thread = $thread;
    
        return $this;
    }

    /**
     * Get thread
     *
     * @return \ARIPD\ForumBundle\Entity\Thread 
     */
    public function getThread()
    {
        return $this->thread;
    }
}