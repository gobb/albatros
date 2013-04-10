<?php
namespace ARIPD\StoreBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="store_brand")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Brand {
	
	public function __construct() {
		$this->products = new \Doctrine\Common\Collections\ArrayCollection();
	}
	
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=50, unique=true, nullable=false)
	 * @Assert\NotBlank(message = "This value should not be blank.")
	 */
	protected $code;

	/**
	 * @ORM\Column(type="string", length=255, nullable=false)
	 * @Assert\NotBlank(message = "This value should not be blank.")
	 */
	protected $name;
	
	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\StoreBundle\Entity\Product", mappedBy="brand")
	 */
	protected $products;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $url;

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
     * Set code
     *
     * @param string $code
     * @return Brand
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

    /**
     * Set name
     *
     * @param string $name
     * @return Brand
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
     * Set url
     *
     * @param string $url
     * @return Brand
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
     * Add products
     *
     * @param \ARIPD\StoreBundle\Entity\Product $products
     * @return Brand
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
}