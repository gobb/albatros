<?php
namespace ARIPD\ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Table(name="forum_topic")
 * @ORM\Entity
 * @JMS\ExclusionPolicy("all")
 */
class Topic {
	public function __construct() {
		$this->subs = new \Doctrine\Common\Collections\ArrayCollection();
		$this->threads = new \Doctrine\Common\Collections\ArrayCollection();
		$this->posts = new \Doctrine\Common\Collections\ArrayCollection();
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
	 * @ORM\Column(type="string", length=255)
	 * @JMS\Expose
	 */
	protected $name;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\ForumBundle\Entity\Topic", inversedBy="subs")
	 * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $parent;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\ForumBundle\Entity\Topic", mappedBy="parent")
	 */
	protected $subs;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\ForumBundle\Entity\Thread", mappedBy="topic")
	 */
	protected $threads;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\ForumBundle\Entity\Post", mappedBy="topic")
	 */
	protected $posts;

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
     * @param number $sorting
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
     * Set parent
     *
     * @param \ARIPD\ForumBundle\Entity\Topic $parent
     * @return Topic
     */
    public function setParent(\ARIPD\ForumBundle\Entity\Topic $parent = null)
    {
        $this->parent = $parent;
    
        return $this;
    }

    /**
     * Get parent
     *
     * @return \ARIPD\ForumBundle\Entity\Topic 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add subs
     *
     * @param \ARIPD\ForumBundle\Entity\Topic $subs
     * @return Topic
     */
    public function addSub(\ARIPD\ForumBundle\Entity\Topic $subs)
    {
        $this->subs[] = $subs;
    
        return $this;
    }

    /**
     * Remove subs
     *
     * @param \ARIPD\ForumBundle\Entity\Topic $subs
     */
    public function removeSub(\ARIPD\ForumBundle\Entity\Topic $subs)
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

    /**
     * Add threads
     *
     * @param \ARIPD\ForumBundle\Entity\Thread $threads
     * @return Topic
     */
    public function addThread(\ARIPD\ForumBundle\Entity\Thread $threads)
    {
        $this->threads[] = $threads;
    
        return $this;
    }

    /**
     * Remove threads
     *
     * @param \ARIPD\ForumBundle\Entity\Thread $threads
     */
    public function removeThread(\ARIPD\ForumBundle\Entity\Thread $threads)
    {
        $this->threads->removeElement($threads);
    }

    /**
     * Get threads
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getThreads()
    {
        return $this->threads;
    }

    /**
     * Add posts
     *
     * @param \ARIPD\ForumBundle\Entity\Post $posts
     * @return Topic
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