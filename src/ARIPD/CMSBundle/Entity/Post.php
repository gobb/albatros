<?php
namespace ARIPD\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Table(name="cms_post")
 * @ORM\Entity(repositoryClass="ARIPD\CMSBundle\Repository\PostRepository")
 * @ORM\HasLifecycleCallbacks()
 * @JMS\ExclusionPolicy("all")
 */
class Post {

	public function __construct() {
		$this->setCreatedAt(new \DateTime());
		$this->setUpdatedAt(new \DateTime());

		$this->tags = new \Doctrine\Common\Collections\ArrayCollection();
		$this->comments = new \Doctrine\Common\Collections\ArrayCollection();
		$this->hits = new \Doctrine\Common\Collections\ArrayCollection();
		$this->images = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @JMS\Expose
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 * @JMS\Expose
	 */
	protected $name;

	/**
	 * @ORM\ManyToMany(targetEntity="ARIPD\CMSBundle\Entity\Tag", inversedBy="posts", cascade={"persist"})
	 * @ORM\JoinTable(name="cms_posts_tags",
	 *      joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id", onDelete="CASCADE")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id", onDelete="CASCADE")}
	 * )
	 */
	protected $tags;

	/**
	 * @ORM\Column(type="string")
	 * @JMS\Expose
	 */
	protected $slug;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 * @JMS\Expose
	 */
	protected $description;

	/**
	 * @ORM\Column(type="text")
	 */
	protected $content;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $video;

	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $approved = false;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\CMSBundle\Entity\Image", mappedBy="post")
	 */
	protected $images;

	/**
	 * @ORM\OneToOne(targetEntity="ARIPD\CMSBundle\Entity\Image")
	 * @ORM\JoinColumn(name="image_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
	 * @JMS\Expose
	 */
	protected $defaultimage;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\UserBundle\Entity\User", inversedBy="cmsposts")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
	 * @JMS\Expose
	 */
	protected $user;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\CMSBundle\Entity\Topic", inversedBy="posts")
	 * @ORM\JoinColumn(name="topic_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
	 */
	protected $topic;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\CMSBundle\Entity\Subtopic", inversedBy="posts")
	 * @ORM\JoinColumn(name="subtopic_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
	 */
	protected $subtopic;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\SurveyBundle\Entity\Survey")
	 * @ORM\JoinColumn(name="survey_id", referencedColumnName="id", nullable=true, onDelete="RESTRICT")
	 */
	protected $survey;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $createdAt;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $updatedAt;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $publishedAt;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\CMSBundle\Entity\Comment", mappedBy="post")
	 */
	protected $comments;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\CMSBundle\Entity\Hit", mappedBy="post")
	 */
	protected $hits;

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
     * Set description
     *
     * @param string $description
     * @return Post
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
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
     * Set video
     *
     * @param string $video
     * @return Post
     */
    public function setVideo($video)
    {
        $this->video = $video;
    
        return $this;
    }

    /**
     * Get video
     *
     * @return string 
     */
    public function getVideo()
    {
        return $this->video;
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
     * Set publishedAt
     *
     * @param \DateTime $publishedAt
     * @return Post
     */
    public function setPublishedAt($publishedAt)
    {
        $this->publishedAt = $publishedAt;
    
        return $this;
    }

    /**
     * Get publishedAt
     *
     * @return \DateTime 
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    /**
     * Add tags
     *
     * @param \ARIPD\CMSBundle\Entity\Tag $tags
     * @return Post
     */
    public function addTag(\ARIPD\CMSBundle\Entity\Tag $tags)
    {
        $this->tags[] = $tags;
    
        return $this;
    }

    /**
     * Remove tags
     *
     * @param \ARIPD\CMSBundle\Entity\Tag $tags
     */
    public function removeTag(\ARIPD\CMSBundle\Entity\Tag $tags)
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

    /**
     * Add images
     *
     * @param \ARIPD\CMSBundle\Entity\Image $images
     * @return Post
     */
    public function addImage(\ARIPD\CMSBundle\Entity\Image $images)
    {
        $this->images[] = $images;
    
        return $this;
    }

    /**
     * Remove images
     *
     * @param \ARIPD\CMSBundle\Entity\Image $images
     */
    public function removeImage(\ARIPD\CMSBundle\Entity\Image $images)
    {
        $this->images->removeElement($images);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Set defaultimage
     *
     * @param \ARIPD\CMSBundle\Entity\Image $defaultimage
     * @return Post
     */
    public function setDefaultimage(\ARIPD\CMSBundle\Entity\Image $defaultimage = null)
    {
        $this->defaultimage = $defaultimage;
    
        return $this;
    }

    /**
     * Get defaultimage
     *
     * @return \ARIPD\CMSBundle\Entity\Image 
     */
    public function getDefaultimage()
    {
        return $this->defaultimage;
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
     * Set topic
     *
     * @param \ARIPD\CMSBundle\Entity\Topic $topic
     * @return Post
     */
    public function setTopic(\ARIPD\CMSBundle\Entity\Topic $topic)
    {
        $this->topic = $topic;
    
        return $this;
    }

    /**
     * Get topic
     *
     * @return \ARIPD\CMSBundle\Entity\Topic 
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * Set subtopic
     *
     * @param \ARIPD\CMSBundle\Entity\Subtopic $subtopic
     * @return Post
     */
    public function setSubtopic(\ARIPD\CMSBundle\Entity\Subtopic $subtopic)
    {
        $this->subtopic = $subtopic;
    
        return $this;
    }

    /**
     * Get subtopic
     *
     * @return \ARIPD\CMSBundle\Entity\Subtopic 
     */
    public function getSubtopic()
    {
        return $this->subtopic;
    }

    /**
     * Set survey
     *
     * @param \ARIPD\SurveyBundle\Entity\Survey $survey
     * @return Post
     */
    public function setSurvey(\ARIPD\SurveyBundle\Entity\Survey $survey = null)
    {
        $this->survey = $survey;
    
        return $this;
    }

    /**
     * Get survey
     *
     * @return \ARIPD\SurveyBundle\Entity\Survey 
     */
    public function getSurvey()
    {
        return $this->survey;
    }

    /**
     * Add comments
     *
     * @param \ARIPD\CMSBundle\Entity\Comment $comments
     * @return Post
     */
    public function addComment(\ARIPD\CMSBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;
    
        return $this;
    }

    /**
     * Remove comments
     *
     * @param \ARIPD\CMSBundle\Entity\Comment $comments
     */
    public function removeComment(\ARIPD\CMSBundle\Entity\Comment $comments)
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
     * @param \ARIPD\CMSBundle\Entity\Hit $hits
     * @return Post
     */
    public function addHit(\ARIPD\CMSBundle\Entity\Hit $hits)
    {
        $this->hits[] = $hits;
    
        return $this;
    }

    /**
     * Remove hits
     *
     * @param \ARIPD\CMSBundle\Entity\Hit $hits
     */
    public function removeHit(\ARIPD\CMSBundle\Entity\Hit $hits)
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
}