<?php
namespace ARIPD\CRMBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="crm_taggroup")
 * @ORM\Entity
 */
class Taggroup {

	public function __construct() {
		$this->tagkeys = new \Doctrine\Common\Collections\ArrayCollection();
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
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $sorting;
	
	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\CRMBundle\Entity\Tagkey", mappedBy="taggroup")
	 * @ORM\OrderBy({"sorting" = "ASC"})
	 */
	protected $tagkeys;

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
     * @return Taggroup
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
     * @return Taggroup
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
     * Add tagkeys
     *
     * @param \ARIPD\CRMBundle\Entity\Tagkey $tagkeys
     * @return Taggroup
     */
    public function addTagkey(\ARIPD\CRMBundle\Entity\Tagkey $tagkeys)
    {
        $this->tagkeys[] = $tagkeys;
    
        return $this;
    }

    /**
     * Remove tagkeys
     *
     * @param \ARIPD\CRMBundle\Entity\Tagkey $tagkeys
     */
    public function removeTagkey(\ARIPD\CRMBundle\Entity\Tagkey $tagkeys)
    {
        $this->tagkeys->removeElement($tagkeys);
    }

    /**
     * Get tagkeys
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTagkeys()
    {
        return $this->tagkeys;
    }
}