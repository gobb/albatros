<?php
namespace ARIPD\CRMBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Table(name="crm_tagkey")
 * @ORM\Entity
 * @JMS\ExclusionPolicy("all")
 */
class Tagkey {

	public function __construct() {
		$this->tags = new \Doctrine\Common\Collections\ArrayCollection();
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
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $sorting;
	
	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\CRMBundle\Entity\Tag", mappedBy="tagkey")
	 */
	protected $tags;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\CRMBundle\Entity\Taggroup", inversedBy="tagkeys")
	 * @ORM\JoinColumn(name="taggroup_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $taggroup;

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
     * Set name
     *
     * @param string $name
     * @return Tagkey
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
     * Set sorting
     *
     * @param number $sorting
     * @return Tagkey
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
     * Add tags
     *
     * @param \ARIPD\CRMBundle\Entity\Tag $tags
     * @return Tagkey
     */
    public function addTag(\ARIPD\CRMBundle\Entity\Tag $tags)
    {
        $this->tags[] = $tags;
    
        return $this;
    }

    /**
     * Remove tags
     *
     * @param \ARIPD\CRMBundle\Entity\Tag $tags
     */
    public function removeTag(\ARIPD\CRMBundle\Entity\Tag $tags)
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
     * Set taggroup
     *
     * @param \ARIPD\CRMBundle\Entity\Taggroup $taggroup
     * @return Tagkey
     */
    public function setTaggroup(\ARIPD\CRMBundle\Entity\Taggroup $taggroup = null)
    {
        $this->taggroup = $taggroup;
    
        return $this;
    }

    /**
     * Get taggroup
     *
     * @return \ARIPD\CRMBundle\Entity\Taggroup 
     */
    public function getTaggroup()
    {
        return $this->taggroup;
    }
}