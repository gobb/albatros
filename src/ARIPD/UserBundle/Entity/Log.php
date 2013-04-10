<?php
namespace ARIPD\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="user_log")
 * @ORM\Entity
 */
class Log {

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\UserBundle\Entity\User", inversedBy="logs", cascade={"persist"})
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $user;

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

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\AdminBundle\Entity\Logtype", inversedBy="logs")
	 * @ORM\JoinColumn(name="logtype_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $logtype;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $bonus;

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
     * @return Log
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
     * @return Log
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
     * @return Log
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
     * Set bonus
     *
     * @param number $bonus
     * @return Log
     */
    public function setBonus($bonus)
    {
        $this->bonus = $bonus;
    
        return $this;
    }

    /**
     * Get bonus
     *
     * @return integer 
     */
    public function getBonus()
    {
        return $this->bonus;
    }

    /**
     * Set user
     *
     * @param \ARIPD\UserBundle\Entity\User $user
     * @return Log
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
     * Set logtype
     *
     * @param \ARIPD\AdminBundle\Entity\Logtype $logtype
     * @return Log
     */
    public function setLogtype(\ARIPD\AdminBundle\Entity\Logtype $logtype = null)
    {
        $this->logtype = $logtype;
    
        return $this;
    }

    /**
     * Get logtype
     *
     * @return \ARIPD\AdminBundle\Entity\Logtype 
     */
    public function getLogtype()
    {
        return $this->logtype;
    }
}