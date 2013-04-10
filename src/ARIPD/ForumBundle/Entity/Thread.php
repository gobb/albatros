<?php
namespace ARIPD\ForumBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="forum_thread")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Thread {
	public function __construct() {
		$this->setCreatedAt(new \DateTime());
		$this->setUpdatedAt(new \DateTime());

		$this->hits = new \Doctrine\Common\Collections\ArrayCollection();
		$this->members = new \Doctrine\Common\Collections\ArrayCollection();
		$this->posts = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	protected $name;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $slug;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\ForumBundle\Entity\Topic", inversedBy="threads")
	 * @ORM\JoinColumn(name="topic_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $topic;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\ForumBundle\Entity\Hit", mappedBy="thread")
	 */
	protected $hits;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\UserBundle\Entity\User", inversedBy="forumthreads")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $user;

	/**
	 * @ORM\ManyToMany(targetEntity="ARIPD\UserBundle\Entity\User", cascade={"persist"})
	 * @ORM\JoinTable(name="forum_threads_members",
	 *       joinColumns={@ORM\JoinColumn(name="thread_id", referencedColumnName="id", onDelete="CASCADE")},
	 *       inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")}
	 * )
	 */
	protected $members;

	/**
	 * @ORM\OneToMany(targetEntity="Post", mappedBy="thread", cascade={"all"})
	 */
	protected $posts;

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

	public function setName($name) {
		$this->name = $name;
		$this->setSlug($this->name);
		return $this;
	}

	public function setSlug($slug) {
		$this->slug = \ARIPD\AdminBundle\Util\ARIPDString::slugify($slug);
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
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Thread
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
     * @return Thread
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
     * Set topic
     *
     * @param \ARIPD\ForumBundle\Entity\Topic $topic
     * @return Thread
     */
    public function setTopic(\ARIPD\ForumBundle\Entity\Topic $topic = null)
    {
        $this->topic = $topic;
    
        return $this;
    }

    /**
     * Get topic
     *
     * @return \ARIPD\ForumBundle\Entity\Topic 
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * Add hits
     *
     * @param \ARIPD\ForumBundle\Entity\Hit $hits
     * @return Thread
     */
    public function addHit(\ARIPD\ForumBundle\Entity\Hit $hits)
    {
        $this->hits[] = $hits;
    
        return $this;
    }

    /**
     * Remove hits
     *
     * @param \ARIPD\ForumBundle\Entity\Hit $hits
     */
    public function removeHit(\ARIPD\ForumBundle\Entity\Hit $hits)
    {
        $this->hits->removeElement($hits);
    }

    /**
     * Get hits
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHits()
    {
        return $this->hits;
    }

    /**
     * Set user
     *
     * @param \ARIPD\UserBundle\Entity\User $user
     * @return Thread
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
     * Add members
     *
     * @param \ARIPD\UserBundle\Entity\User $members
     * @return Thread
     */
    public function addMember(\ARIPD\UserBundle\Entity\User $members)
    {
        $this->members[] = $members;
    
        return $this;
    }

    /**
     * Remove members
     *
     * @param \ARIPD\UserBundle\Entity\User $members
     */
    public function removeMember(\ARIPD\UserBundle\Entity\User $members)
    {
        $this->members->removeElement($members);
    }

    /**
     * Get members
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * Add posts
     *
     * @param \ARIPD\ForumBundle\Entity\Post $posts
     * @return Thread
     */
    public function addPost(\ARIPD\ForumBundle\Entity\Post $posts)
    {
        $this->posts[] = $posts;
    
        return $this;
    }

    /**
     * Remove posts
     *
     * @param \ARIPD\ForumBundle\Entity\Post $posts
     */
    public function removePost(\ARIPD\ForumBundle\Entity\Post $posts)
    {
        $this->posts->removeElement($posts);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPosts()
    {
        return $this->posts;
    }
}