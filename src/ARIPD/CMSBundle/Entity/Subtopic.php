<?php
namespace ARIPD\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="cms_subtopic")
 * @ORM\Entity
 */
class Subtopic {
	
	public function __construct() {
		$this->posts = new \Doctrine\Common\Collections\ArrayCollection();
	}
	
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $sorting;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	protected $name;
	
	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\CMSBundle\Entity\Topic", inversedBy="subtopics")
	 * @ORM\JoinColumn(name="topic_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
	 */
	protected $topic;
	
	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\CMSBundle\Entity\Post", mappedBy="subtopic")
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
     * @param integer $sorting
     * @return Subtopic
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
     * @return Subtopic
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
     * Set topic
     *
     * @param \ARIPD\CMSBundle\Entity\Topic $topic
     * @return Subtopic
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
     * Add posts
     *
     * @param \ARIPD\CMSBundle\Entity\Post $posts
     * @return Subtopic
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
}