<?php
namespace ARIPD\BlogBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="blog_post")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Post {
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
	 * @ORM\Column(type="text")
	 */
	protected $content;

	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $approved = false;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\UserBundle\Entity\User", inversedBy="blogposts")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
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
	 * @ORM\OneToMany(targetEntity="ARIPD\BlogBundle\Entity\Comment", mappedBy="post")
	 */
	protected $comments;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\BlogBundle\Entity\Hit", mappedBy="post")
	 */
	protected $hits;

	/**
	 * @ORM\ManyToMany(targetEntity="ARIPD\BlogBundle\Entity\Tag", inversedBy="posts", cascade={"persist"})
	 * @ORM\JoinTable(name="blog_posts_tags",
	 *      joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id", onDelete="CASCADE")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id", onDelete="CASCADE")}
	 * )
	 */
	protected $tags;

	public function __construct() {
		$this->setCreatedAt(new \DateTime());
		$this->setUpdatedAt(new \DateTime());

		$this->comments = new \Doctrine\Common\Collections\ArrayCollection();
		$this->hits = new \Doctrine\Common\Collections\ArrayCollection();
		$this->tags = new \Doctrine\Common\Collections\ArrayCollection();
	}

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
     * Set content
     *
     * @param string $content
     * @return Post
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
     * Set approved
     *
     * @param boolean $approved
     * @return Post
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;
    
        return $this;
    }

    /**
     * Get approved
     *
     * @return boolean 
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Post
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
     * @return Post
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
     * Set user
     *
     * @param \ARIPD\UserBundle\Entity\User $user
     * @return Post
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
     * Add comments
     *
     * @param \ARIPD\BlogBundle\Entity\Comment $comments
     * @return Post
     */
    public function addComment(\ARIPD\BlogBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;
    
        return $this;
    }

    /**
     * Remove comments
     *
     * @param \ARIPD\BlogBundle\Entity\Comment $comments
     */
    public function removeComment(\ARIPD\BlogBundle\Entity\Comment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add hits
     *
     * @param \ARIPD\BlogBundle\Entity\Hit $hits
     * @return Post
     */
    public function addHit(\ARIPD\BlogBundle\Entity\Hit $hits)
    {
        $this->hits[] = $hits;
    
        return $this;
    }

    /**
     * Remove hits
     *
     * @param \ARIPD\BlogBundle\Entity\Hit $hits
     */
    public function removeHit(\ARIPD\BlogBundle\Entity\Hit $hits)
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
     * Add tags
     *
     * @param \ARIPD\BlogBundle\Entity\Tag $tags
     * @return Post
     */
    public function addTag(\ARIPD\BlogBundle\Entity\Tag $tags)
    {
        $this->tags[] = $tags;
    
        return $this;
    }

    /**
     * Remove tags
     *
     * @param \ARIPD\BlogBundle\Entity\Tag $tags
     */
    public function removeTag(\ARIPD\BlogBundle\Entity\Tag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTags()
    {
        return $this->tags;
    }
}