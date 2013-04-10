<?php
namespace ARIPD\CMSBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Table(name="cms_page")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @JMS\ExclusionPolicy("all")
 */
class Page {
	
	public function __construct() {
		$this->setCreatedAt(new \DateTime());
		$this->setUpdatedAt(new \DateTime());
		
		$this->subs = new \Doctrine\Common\Collections\ArrayCollection();
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
	 * @ORM\Column(type="boolean", nullable=true)
	 */
	protected $initial;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 * @JMS\Expose
	 */
	protected $name;

	/**
	 * @ORM\Column(type="string")
	 * @JMS\Expose
	 */
	protected $slug;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $description;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $content;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\CMSBundle\Entity\Page", inversedBy="subs")
	 * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $parent;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\CMSBundle\Entity\Page", mappedBy="parent")
	 */
	protected $subs;

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

	public function __toString() {
		return $this->getName();
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
     * @return Page
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
     * Set initial
     *
     * @param boolean $initial
     * @return Page
     */
    public function setInitial($initial)
    {
        $this->initial = $initial;
    
        return $this;
    }

    /**
     * Get initial
     *
     * @return boolean 
     */
    public function getInitial()
    {
        return $this->initial;
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
     * @return Page
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
     * @return Page
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Page
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
     * @return Page
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
     * Set iso639
     *
     * @param \ARIPD\AdminBundle\Entity\Iso639 $iso639
     * @return Page
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
     * @param \ARIPD\CMSBundle\Entity\Page $parent
     * @return Page
     */
    public function setParent(\ARIPD\CMSBundle\Entity\Page $parent = null)
    {
        $this->parent = $parent;
    
        return $this;
    }

    /**
     * Get parent
     *
     * @return \ARIPD\CMSBundle\Entity\Page 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add subs
     *
     * @param \ARIPD\CMSBundle\Entity\Page $subs
     * @return Page
     */
    public function addSub(\ARIPD\CMSBundle\Entity\Page $subs)
    {
        $this->subs[] = $subs;
    
        return $this;
    }

    /**
     * Remove subs
     *
     * @param \ARIPD\CMSBundle\Entity\Page $subs
     */
    public function removeSub(\ARIPD\CMSBundle\Entity\Page $subs)
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
}