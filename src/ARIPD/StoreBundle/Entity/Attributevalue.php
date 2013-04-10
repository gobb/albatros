<?php
namespace ARIPD\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="store_attributevalue")
 * @ORM\Entity
 */
class Attributevalue {
	
	public function __construct() {
		$this->models = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=255, unique=true, nullable=false)
	 * @Assert\NotBlank(message = "This value should not be blank.")
	 */
	protected $code;
	
	/**
	 * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlank(message = "This value should not be blank.")
	 */
	protected $name;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\StoreBundle\Entity\Attributekey", inversedBy="attributevalues")
	 * @ORM\JoinColumn(name="attributekey_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $attributekey;
	
	/**
	 * @ORM\ManyToMany(targetEntity="ARIPD\StoreBundle\Entity\Model", mappedBy="attributevalues")
	 */
	protected $models;
	
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
     * @return Attributevalue
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
     * Set attributekey
     *
     * @param \ARIPD\StoreBundle\Entity\Attributekey $attributekey
     * @return Attributevalue
     */
    public function setAttributekey(\ARIPD\StoreBundle\Entity\Attributekey $attributekey = null)
    {
        $this->attributekey = $attributekey;
    
        return $this;
    }

    /**
     * Get attributekey
     *
     * @return \ARIPD\StoreBundle\Entity\Attributekey 
     */
    public function getAttributekey()
    {
        return $this->attributekey;
    }

    /**
     * Add models
     *
     * @param \ARIPD\StoreBundle\Entity\Model $models
     * @return Attributevalue
     */
    public function addModel(\ARIPD\StoreBundle\Entity\Model $models)
    {
        $this->models[] = $models;
    
        return $this;
    }

    /**
     * Remove models
     *
     * @param \ARIPD\StoreBundle\Entity\Model $models
     */
    public function removeModel(\ARIPD\StoreBundle\Entity\Model $models)
    {
        $this->models->removeElement($models);
    }

    /**
     * Get models
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getModels()
    {
        return $this->models;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Attributevalue
     */
    public function setCode($code)
    {
        $this->code = $code;
    
        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }
}