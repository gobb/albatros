<?php
namespace ARIPD\StoreBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="store_branchimage")
 * @ORM\Entity
 */
class Branchimage {
	
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=255, nullable=false)
	 * @Assert\NotBlank(message = "This value should not be blank.")
	 * @Assert\Url(message = "This value is not a valid URL.")
	 */
	protected $url;
	
	/**
	 * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlank(message = "This value should not be blank.")
	 */
	protected $name;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $description;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\StoreBundle\Entity\Branch", inversedBy="images")
	 * @ORM\JoinColumn(name="branch_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $branch;

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
     * Set url
     *
     * @param string $url
     * @return Branchimage
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
     * Set name
     *
     * @param string $name
     * @return Branchimage
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
     * @return Branchimage
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
     * Set prime
     *
     * @param boolean $prime
     * @return Branchimage
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
     * Set branch
     *
     * @param \ARIPD\StoreBundle\Entity\Branch $branch
     * @return Branchimage
     */
    public function setBranch(\ARIPD\StoreBundle\Entity\Branch $branch = null)
    {
        $this->branch = $branch;
    
        return $this;
    }

    /**
     * Get branch
     *
     * @return \ARIPD\StoreBundle\Entity\Branch 
     */
    public function getBranch()
    {
        return $this->branch;
    }
}