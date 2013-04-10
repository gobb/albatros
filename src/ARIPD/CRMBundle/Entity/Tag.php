<?php
namespace ARIPD\CRMBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="crm_tag")
 * @ORM\Entity
 */
class Tag {

	public function __construct() {
		$this->individuals = new \Doctrine\Common\Collections\ArrayCollection();
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
	 * @ORM\ManyToOne(targetEntity="ARIPD\CRMBundle\Entity\Tagkey", inversedBy="tags")
	 * @ORM\JoinColumn(name="tagkey_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $tagkey;

	/**
	 * @ORM\ManyToMany(targetEntity="ARIPD\CRMBundle\Entity\Individual", mappedBy="tags")
	 */
	protected $individuals;

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
     * @return Tag
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
     * Set tagkey
     *
     * @param \ARIPD\CRMBundle\Entity\Tagkey $tagkey
     * @return Tag
     */
    public function setTagkey(\ARIPD\CRMBundle\Entity\Tagkey $tagkey = null)
    {
        $this->tagkey = $tagkey;
    
        return $this;
    }

    /**
     * Get tagkey
     *
     * @return \ARIPD\CRMBundle\Entity\Tagkey 
     */
    public function getTagkey()
    {
        return $this->tagkey;
    }

    /**
     * Add individuals
     *
     * @param \ARIPD\CRMBundle\Entity\Individual $individuals
     * @return Tag
     */
    public function addIndividual(\ARIPD\CRMBundle\Entity\Individual $individuals)
    {
        $this->individuals[] = $individuals;
    
        return $this;
    }

    /**
     * Remove individuals
     *
     * @param \ARIPD\CRMBundle\Entity\Individual $individuals
     */
    public function removeIndividual(\ARIPD\CRMBundle\Entity\Individual $individuals)
    {
        $this->individuals->removeElement($individuals);
    }

    /**
     * Get individuals
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIndividuals()
    {
        return $this->individuals;
    }
}