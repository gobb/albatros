<?php
namespace ARIPD\StoreBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="store_modelimage")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Modelimage {
	
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlank(message = "This value should not be blank.")
	 */
	protected $name;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	protected $description;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\StoreBundle\Entity\Model", inversedBy="images")
	 * @ORM\JoinColumn(name="model_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $model;

	/**
	 * @ORM\Column(type="string", length=255, nullable=false)
	 */
	protected $url;

	/**
	 * @ORM\Column(type="boolean", nullable=false)
	 */
	protected $prime = false;
	
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
     * @return Modelimage
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
     * Set description
     *
     * @param string $description
     * @return Modelimage
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
     * Set url
     *
     * @param string $url
     * @return Modelimage
     */
    public function setUrl($url)
    {
        $this->url = $url;
    
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set prime
     *
     * @param boolean $prime
     * @return Modelimage
     */
    public function setPrime($prime)
    {
        $this->prime = $prime;
    
        return $this;
    }

    /**
     * Get prime
     *
     * @return boolean 
     */
    public function getPrime()
    {
        return $this->prime;
    }

    /**
     * Set model
     *
     * @param \ARIPD\StoreBundle\Entity\Model $model
     * @return Modelimage
     */
    public function setModel(\ARIPD\StoreBundle\Entity\Model $model = null)
    {
        $this->model = $model;
    
        return $this;
    }

    /**
     * Get model
     *
     * @return \ARIPD\StoreBundle\Entity\Model 
     */
    public function getModel()
    {
        return $this->model;
    }
}