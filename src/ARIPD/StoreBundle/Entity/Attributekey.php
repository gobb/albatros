<?php
namespace ARIPD\StoreBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="store_attributekey")
 * @ORM\Entity
 */
class Attributekey {

	public function __construct() {
		$this->attributevalues = new \Doctrine\Common\Collections\ArrayCollection();
		$this->products = new \Doctrine\Common\Collections\ArrayCollection();
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
	 * @ORM\OneToMany(targetEntity="ARIPD\StoreBundle\Entity\Attributevalue", mappedBy="attributekey")
	 */
	protected $attributevalues;

	/**
	 * @ORM\ManyToMany(targetEntity="ARIPD\StoreBundle\Entity\Product", mappedBy="attributekeys")
	 */
	protected $products;

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
     * @return Attributekey
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
     * Add attributevalues
     *
     * @param \ARIPD\StoreBundle\Entity\Attributevalue $attributevalues
     * @return Attributekey
     */
    public function addAttributevalue(\ARIPD\StoreBundle\Entity\Attributevalue $attributevalues)
    {
        $this->attributevalues[] = $attributevalues;
    
        return $this;
    }

    /**
     * Remove attributevalues
     *
     * @param \ARIPD\StoreBundle\Entity\Attributevalue $attributevalues
     */
    public function removeAttributevalue(\ARIPD\StoreBundle\Entity\Attributevalue $attributevalues)
    {
        $this->attributevalues->removeElement($attributevalues);
    }

    /**
     * Get attributevalues
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAttributevalues()
    {
        return $this->attributevalues;
    }

    /**
     * Add products
     *
     * @param \ARIPD\StoreBundle\Entity\Product $products
     * @return Attributekey
     */
    public function addProduct(\ARIPD\StoreBundle\Entity\Product $products)
    {
        $this->products[] = $products;
    
        return $this;
    }

    /**
     * Remove products
     *
     * @param \ARIPD\StoreBundle\Entity\Product $products
     */
    public function removeProduct(\ARIPD\StoreBundle\Entity\Product $products)
    {
        $this->products->removeElement($products);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Attributekey
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