<?php
namespace ARIPD\StoreBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Table(name="store_category")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @JMS\ExclusionPolicy("all")
 */
class Category {

	public function __construct() {
		$this->subs = new \Doctrine\Common\Collections\ArrayCollection();
		$this->products = new \Doctrine\Common\Collections\ArrayCollection();
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
	 * @ORM\Column(nullable=true)
	 */
	protected $template_category;

	/**
	 * @ORM\Column(nullable=true)
	 */
	protected $template_product;

	/**
	 * @ORM\Column(type="string", length=255)
	 * @JMS\Expose
	 */
	protected $name;

	/**
	 * @ORM\Column(type="string")
	 * @JMS\Expose
	 */
	protected $slug;

	public function setName($name) {
		$this->name = $name;
		$this->setSlug($this->name);

		return $this;
	}

	public function setSlug($slug) {
		$this->slug = \ARIPD\AdminBundle\Util\ARIPDString::slugify($slug);
	}

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $description;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $content;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\StoreBundle\Entity\Category", inversedBy="subs")
	 * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $parent;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\StoreBundle\Entity\Category", mappedBy="parent")
	 */
	protected $subs;

	/**
	 * @ORM\ManyToMany(targetEntity="ARIPD\StoreBundle\Entity\Product", mappedBy="categories")
	 * @JMS\Expose
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
     * Set sorting
     *
     * @param integer $sorting
     * @return Category
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
     * Set template_category
     *
     * @param string $templateCategory
     * @return Category
     */
    public function setTemplateCategory($templateCategory)
    {
        $this->template_category = $templateCategory;
    
        return $this;
    }

    /**
     * Get template_category
     *
     * @return string 
     */
    public function getTemplateCategory()
    {
        return $this->template_category;
    }

    /**
     * Set template_product
     *
     * @param string $templateProduct
     * @return Category
     */
    public function setTemplateProduct($templateProduct)
    {
        $this->template_product = $templateProduct;
    
        return $this;
    }

    /**
     * Get template_product
     *
     * @return string 
     */
    public function getTemplateProduct()
    {
        return $this->template_product;
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
     * @return Category
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
     * @return Category
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
     * Set url
     *
     * @param string $url
     * @return Category
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
     * Set iso639
     *
     * @param \ARIPD\AdminBundle\Entity\Iso639 $iso639
     * @return Category
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
     * @param \ARIPD\StoreBundle\Entity\Category $parent
     * @return Category
     */
    public function setParent(\ARIPD\StoreBundle\Entity\Category $parent = null)
    {
        $this->parent = $parent;
    
        return $this;
    }

    /**
     * Get parent
     *
     * @return \ARIPD\StoreBundle\Entity\Category 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add subs
     *
     * @param \ARIPD\StoreBundle\Entity\Category $subs
     * @return Category
     */
    public function addSub(\ARIPD\StoreBundle\Entity\Category $subs)
    {
        $this->subs[] = $subs;
    
        return $this;
    }

    /**
     * Remove subs
     *
     * @param \ARIPD\StoreBundle\Entity\Category $subs
     */
    public function removeSub(\ARIPD\StoreBundle\Entity\Category $subs)
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

    /**
     * Add products
     *
     * @param \ARIPD\StoreBundle\Entity\Product $products
     * @return Category
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