<?php
namespace ARIPD\CMSBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Table(name="cms_topic")
 * @ORM\Entity
 * @JMS\ExclusionPolicy("all")
 */
class Topic {

	public function __construct() {
		$this->subtopics = new \Doctrine\Common\Collections\ArrayCollection();
		$this->posts = new \Doctrine\Common\Collections\ArrayCollection();
		$this->groups = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @JMS\Expose
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\AdminBundle\Entity\Iso639")
	 * @ORM\JoinColumn(name="iso639_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $iso639;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $sorting;

	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $hidden = false;

	/**
	 * @ORM\Column(nullable=true)
	 */
	protected $template_topic;

	/**
	 * @ORM\Column(nullable=true)
	 */
	protected $template_post;

	/**
	 * @ORM\Column(type="string", length=255)
	 * @JMS\Expose
	 * @JMS\SerializedName("text")
	 */
	protected $name;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\CMSBundle\Entity\Subtopic", mappedBy="topic")
	 */
	protected $subtopics;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\CMSBundle\Entity\Post", mappedBy="topic")
	 */
	protected $posts;
	
	public function getPostsApproved() {
		$r = new \Doctrine\Common\Collections\ArrayCollection();
		foreach ($this->getPosts() as $post) {
			if ( $post->getApproved() ) {
				$r[] = $post;
			}
		}
		return $r;
	}

	/**
	 * @ORM\ManyToMany(targetEntity="ARIPD\UserBundle\Entity\Group", inversedBy="topics")
	 * @ORM\JoinTable(name="cms_topics_groups",
	 *      joinColumns={@ORM\JoinColumn(name="topic_id", referencedColumnName="id", onDelete="CASCADE")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id", onDelete="CASCADE")}
	 * )
	 */
	protected $groups;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $path;

	/**
	 * @Assert\File(
	 * 		maxSize = "1M", 
	 * 		mimeTypes = {"image/jpeg", "image/pjpeg", "image/gif", "image/png"}, 
	 * 		mimeTypesMessage = "The mime type of the file is invalid ({{ type }}). Allowed mime types are {{ types }}.", 
	 * 		maxSizeMessage = "The file is too large ({{ size }}). Allowed maximum size is {{ limit }}."
	 * )
	 */
	protected $file;

	public function getFile() {
		return $this->file;
	}
	
	public function setFile($file) {
		$this->file = $file;
	
		return $this;
	}
	
	public function getAbsolutePath() {
		return null === $this->path ? null
				: $this->getUploadRootDir() . '/' . $this->path;
	}

	public function getWebPath() {
		return null === $this->path ? null
				: $this->getUploadDir() . '/' . $this->path;
	}

	protected function getUploadRootDir() {
		return __DIR__ . '/../../../../web/' . $this->getUploadDir();
	}

	protected function getUploadDir() {
		return 'uploads/_site';
	}

	/**
	 * @ORM\PrePersist()
	 * @ORM\PreUpdate()
	 */
	public function preUpload() {
		if (null !== $this->file) {
			// do whatever you want to generate a unique name
			$this->removeUpload();
			$this->path = uniqid() . '.' . $this->file->guessExtension();
		}
	}

	/**
	 * @ORM\PostPersist()
	 * @ORM\PostUpdate()
	 */
	public function upload() {
		// the file property can be empty if the field is not required
		if (null === $this->file) {
			return;
		}

		// if there is an error when moving the file, an exception will
		// be automatically thrown by move(). This will properly prevent
		// the entity from being persisted to the database on error
		$this->file->move($this->getUploadRootDir(), $this->path);

		// clean up the file property as you won't need it anymore
		unset($this->file);
	}

	/**
	 * @ORM\PostRemove()
	 */
	public function removeUpload() {
		if ($file = $this->getAbsolutePath()) {
			unlink($file);
		}
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
     * Set sorting
     *
     * @param integer $sorting
     * @return Topic
     */
    public function setSorting($sorting)
    {
        $this->sorting = $sorting;
    
        return $this;
    }

    /**
     * Get sorting
     *
     * @return integer 
     */
    public function getSorting()
    {
        return $this->sorting;
    }

    /**
     * Set hidden
     *
     * @param boolean $hidden
     * @return Topic
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
    
        return $this;
    }

    /**
     * Get hidden
     *
     * @return boolean 
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * Set template_topic
     *
     * @param string $templateTopic
     * @return Topic
     */
    public function setTemplateTopic($templateTopic)
    {
        $this->template_topic = $templateTopic;
    
        return $this;
    }

    /**
     * Get template_topic
     *
     * @return string 
     */
    public function getTemplateTopic()
    {
        return $this->template_topic;
    }

    /**
     * Set template_post
     *
     * @param string $templatePost
     * @return Topic
     */
    public function setTemplatePost($templatePost)
    {
        $this->template_post = $templatePost;
    
        return $this;
    }

    /**
     * Get template_post
     *
     * @return string 
     */
    public function getTemplatePost()
    {
        return $this->template_post;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Topic
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
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
     * Set path
     *
     * @param string $path
     * @return Topic
     */
    public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set iso639
     *
     * @param \ARIPD\AdminBundle\Entity\Iso639 $iso639
     * @return Topic
     */
    public function setIso639(\ARIPD\AdminBundle\Entity\Iso639 $iso639 = null)
    {
        $this->iso639 = $iso639;
    
        return $this;
    }

    /**
     * Get iso639
     *
     * @return \ARIPD\AdminBundle\Entity\Iso639 
     */
    public function getIso639()
    {
        return $this->iso639;
    }

    /**
     * Add subtopics
     *
     * @param \ARIPD\CMSBundle\Entity\Subtopic $subtopics
     * @return Topic
     */
    public function addSubtopic(\ARIPD\CMSBundle\Entity\Subtopic $subtopics)
    {
        $this->subtopics[] = $subtopics;
    
        return $this;
    }

    /**
     * Remove subtopics
     *
     * @param \ARIPD\CMSBundle\Entity\Subtopic $subtopics
     */
    public function removeSubtopic(\ARIPD\CMSBundle\Entity\Subtopic $subtopics)
    {
        $this->subtopics->removeElement($subtopics);
    }

    /**
     * Get subtopics
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSubtopics()
    {
        return $this->subtopics;
    }

    /**
     * Add posts
     *
     * @param \ARIPD\CMSBundle\Entity\Post $posts
     * @return Topic
     */
    public function addPost(\ARIPD\CMSBundle\Entity\Post $posts)
    {
        $this->posts[] = $posts;
    
        return $this;
    }

    /**
     * Remove posts
     *
     * @param \ARIPD\CMSBundle\Entity\Post $posts
     */
    public function removePost(\ARIPD\CMSBundle\Entity\Post $posts)
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

    /**
     * Add groups
     *
     * @param \ARIPD\UserBundle\Entity\Group $groups
     * @return Topic
     */
    public function addGroup(\ARIPD\UserBundle\Entity\Group $groups)
    {
        $this->groups[] = $groups;
    
        return $this;
    }

    /**
     * Remove groups
     *
     * @param \ARIPD\UserBundle\Entity\Group $groups
     */
    public function removeGroup(\ARIPD\UserBundle\Entity\Group $groups)
    {
        $this->groups->removeElement($groups);
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGroups()
    {
        return $this->groups;
    }
}