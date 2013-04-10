<?php
namespace ARIPD\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="blog_hit")
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
	 * @ORM\ManyToOne(targetEntity="ARIPD\UserBundle\Entity\User", inversedBy="blogposthits")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $user;
	
	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\BlogBundle\Entity\Post", inversedBy="hits")
	 * @ORM\JoinColumn(name="post_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $post;

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
     * Set post
     *
     * @param \ARIPD\BlogBundle\Entity\Post $post
     * @return Hit
     */
    public function setPost(\ARIPD\BlogBundle\Entity\Post $post = null)
    {
        $this->post = $post;
    
        return $this;
    }

    /**
     * Get post
     *
     * @return \ARIPD\BlogBundle\Entity\Post 
     */
    public function getPost()
    {
        return $this->post;
    }
}