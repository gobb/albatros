<?php
namespace ARIPD\StoreBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="store_tag")
 * @ORM\Entity
 */
class Tag {
	
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
	 * @ORM\Column(type="string", length=255)
	 */
	protected $name;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $slug;
	
	/**
	 * @ORM\ManyToMany(targetEntity="ARIPD\StoreBundle\Entity\Product", mappedBy="tags")
	 */
	protected $products;

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
     * Add products
     *
     * @param \ARIPD\StoreBundle\Entity\Product $products
     * @return Tag
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