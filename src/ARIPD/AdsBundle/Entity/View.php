<?php
namespace ARIPD\AdsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ads_view")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class View {
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\UserBundle\Entity\User", inversedBy="advertisementviews")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $user;
	
	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\AdsBundle\Entity\Advertisement", inversedBy="views")
	 * @ORM\JoinColumn(name="advertisement_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $advertisement;

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
     * @return View
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
     * @return View
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
     * @return View
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
     * @return View
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
     * Set advertisement
     *
     * @param \ARIPD\AdsBundle\Entity\Advertisement $advertisement
     * @return View
     */
    public function setAdvertisement(\ARIPD\AdsBundle\Entity\Advertisement $advertisement = null)
    {
        $this->advertisement = $advertisement;
    
        return $this;
    }

    /**
     * Get advertisement
     *
     * @return \ARIPD\AdsBundle\Entity\Advertisement 
     */
    public function getAdvertisement()
    {
        return $this->advertisement;
    }
}