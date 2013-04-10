<?php
namespace ARIPD\StoreBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="store_product")
 * @ORM\Entity(repositoryClass="ARIPD\StoreBundle\Repository\ProductRepository")
 * @ORM\HasLifecycleCallbacks()
 * @JMS\ExclusionPolicy("all")
 */
class Product {

	public function __construct() {
		$this->setCreatedAt(new \DateTime());
		$this->setUpdatedAt(new \DateTime());
		$this->setPublishedAt(new \DateTime());
		
		$this->attributekeys = new \Doctrine\Common\Collections\ArrayCollection();
		$this->categories = new \Doctrine\Common\Collections\ArrayCollection();
		$this->models = new \Doctrine\Common\Collections\ArrayCollection();
		$this->tags = new \Doctrine\Common\Collections\ArrayCollection();
		$this->comments = new \Doctrine\Common\Collections\ArrayCollection();
		$this->hits = new \Doctrine\Common\Collections\ArrayCollection();
		
		$this->recommendsWithMe = new \Doctrine\Common\Collections\ArrayCollection();
		$this->myRecommends = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @JMS\Expose
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=255, unique=true, nullable=false)
	 * @Assert\NotBlank(message = "This value should not be blank.")
	 */
	protected $code;
	
	/**
	 * @ORM\Column(type="string", length=255)
	 * @JMS\Expose
	 */
	protected $name;

	/**
	 * @ORM\ManyToMany(targetEntity="ARIPD\StoreBundle\Entity\Tag", inversedBy="products", cascade={"persist"})
	 * @ORM\JoinTable(name="store_products_tags",
	 *      joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="CASCADE")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id", onDelete="CASCADE")}
	 * )
	 */
	protected $tags;

	/**
	 * @ORM\Column(type="string")
	 * @JMS\Expose
	 */
	protected $slug;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 * @JMS\Expose
	 */
	protected $description;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $content;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 * @JMS\Expose
	 */
	protected $video;

	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $approved = false;

	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $vitrine = false;

	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $brandnew = false;
	
	/**
	 * @ORM\ManyToMany(targetEntity="ARIPD\StoreBundle\Entity\Attributekey", inversedBy="products", cascade={"persist"})
	 * @ORM\JoinTable(name="store_products_attributekeys",
	 *      joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="CASCADE")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="attributekey_id", referencedColumnName="id", onDelete="CASCADE")}
	 * )
	 */
	protected $attributekeys;
	
	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\StoreBundle\Entity\Model", mappedBy="product", cascade={"persist"})
	 * @ORM\OrderBy({"prime" = "DESC"})
	 */
	protected $models;

	public function getModel() {
		foreach ($this->getModels() as $model)
			if ($model->getPrime()) return $model;
		return null;
	}
	
	/**
	 * @ORM\ManyToMany(targetEntity="ARIPD\StoreBundle\Entity\Category", inversedBy="products")
	 * @ORM\JoinTable(name="store_products_categories")
	 */
	protected $categories;
	
	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\UserBundle\Entity\User", inversedBy="products")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
	 */
	protected $user;
	
	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\StoreBundle\Entity\Category")
	 * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
	 */
	protected $defaultcategory;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\StoreBundle\Entity\Brand", inversedBy="products")
	 * @ORM\JoinColumn(name="brand_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
	 */
	protected $brand;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\AdminBundle\Entity\Taxonomy")
	 * @ORM\JoinColumn(name="taxonomy_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $taxonomy;

	/**
	 * @ORM\Column(type="decimal", scale=2)
	 * @JMS\Expose
	 */
	protected $point = 0;
	
	/**
	 * @ORM\ManyToMany(targetEntity="ARIPD\StoreBundle\Entity\Product", mappedBy="myRecommends")
	 */
	protected $recommendsWithMe;
	
	/**
	 * @ORM\ManyToMany(targetEntity="ARIPD\StoreBundle\Entity\Product", inversedBy="recommendsWithMe")
	 * @ORM\JoinTable(name="store_products_recommends",
	 * 		joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="CASCADE")},
	 * 		inverseJoinColumns={@ORM\JoinColumn(name="recommend_product_id", referencedColumnName="id", onDelete="CASCADE")}
	 * 		)
	 */
	protected $myRecommends;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $createdAt;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $updatedAt;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $publishedAt;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\StoreBundle\Entity\Comment", mappedBy="product")
	 */
	protected $comments;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\StoreBundle\Entity\Hit", mappedBy="product")
	 */
	protected $hits;

	/**
	 * @ORM\PreUpdate
	 */
	public function setPreUpdate() {
		$this->setUpdatedAt(new \DateTime());
	}

	public function setName($name) {
		$this->name = $name;
		$this->setSlug($this->name);
		
		return $this;
	}

	public function setSlug($slug) {
		$this->slug = \ARIPD\AdminBundle\Util\ARIPDString::slugify($slug);
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
     * Set code
     *
     * @param string $code
     * @return Product
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
     * @return Product
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
     * @return Product
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
     * Set video
     *
     * @param string $video
     * @return Product
     */
    public function setVideo($video)
    {
        $this->video = $video;
    
        return $this;
    }

    /**
     * Get video
     *
     * @return string 
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * Set approved
     *
     * @param boolean $approved
     * @return Product
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;
    
        return $this;
    }

    /**
     * Get approved
     *
     * @return boolean 
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * Set vitrine
     *
     * @param boolean $vitrine
     * @return Product
     */
    public function setVitrine($vitrine)
    {
        $this->vitrine = $vitrine;
    
        return $this;
    }

    /**
     * Get vitrine
     *
     * @return boolean 
     */
    public function getVitrine()
    {
        return $this->vitrine;
    }

    /**
     * Set brandnew
     *
     * @param boolean $brandnew
     * @return Product
     */
    public function setBrandnew($brandnew)
    {
        $this->brandnew = $brandnew;
    
        return $this;
    }

    /**
     * Get brandnew
     *
     * @return boolean 
     */
    public function getBrandnew()
    {
        return $this->brandnew;
    }

    /**
     * Set point
     *
     * @param float $point
     * @return Product
     */
    public function setPoint($point)
    {
        $this->point = $point;
    
        return $this;
    }

    /**
     * Get point
     *
     * @return float 
     */
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Product
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Product
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set publishedAt
     *
     * @param \DateTime $publishedAt
     * @return Product
     */
    public function setPublishedAt($publishedAt)
    {
        $this->publishedAt = $publishedAt;
    
        return $this;
    }

    /**
     * Get publishedAt
     *
     * @return \DateTime 
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    /**
     * Add tags
     *
     * @param \ARIPD\StoreBundle\Entity\Tag $tags
     * @return Product
     */
    public function addTag(\ARIPD\StoreBundle\Entity\Tag $tags)
    {
        $this->tags[] = $tags;
    
        return $this;
    }

    /**
     * Remove tags
     *
     * @param \ARIPD\StoreBundle\Entity\Tag $tags
     */
    public function removeTag(\ARIPD\StoreBundle\Entity\Tag $tags)
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
     * Add attributekeys
     *
     * @param \ARIPD\StoreBundle\Entity\Attributekey $attributekeys
     * @return Product
     */
    public function addAttributekey(\ARIPD\StoreBundle\Entity\Attributekey $attributekeys)
    {
        $this->attributekeys[] = $attributekeys;
    
        return $this;
    }

    /**
     * Remove attributekeys
     *
     * @param \ARIPD\StoreBundle\Entity\Attributekey $attributekeys
     */
    public function removeAttributekey(\ARIPD\StoreBundle\Entity\Attributekey $attributekeys)
    {
        $this->attributekeys->removeElement($attributekeys);
    }

    /**
     * Get attributekeys
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAttributekeys()
    {
        return $this->attributekeys;
    }

    /**
     * Add models
     *
     * @param \ARIPD\StoreBundle\Entity\Model $models
     * @return Product
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
     * Add categories
     *
     * @param \ARIPD\StoreBundle\Entity\Category $categories
     * @return Product
     */
    public function addCategorie(\ARIPD\StoreBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;
    
        return $this;
    }

    /**
     * Remove categories
     *
     * @param \ARIPD\StoreBundle\Entity\Category $categories
     */
    public function removeCategorie(\ARIPD\StoreBundle\Entity\Category $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set user
     *
     * @param \ARIPD\UserBundle\Entity\User $user
     * @return Product
     */
    public function setUser(\ARIPD\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \ARIPD\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set defaultcategory
     *
     * @param \ARIPD\StoreBundle\Entity\Category $defaultcategory
     * @return Product
     */
    public function setDefaultcategory(\ARIPD\StoreBundle\Entity\Category $defaultcategory = null)
    {
        $this->defaultcategory = $defaultcategory;
    
        return $this;
    }

    /**
     * Get defaultcategory
     *
     * @return \ARIPD\StoreBundle\Entity\Category 
     */
    public function getDefaultcategory()
    {
        return $this->defaultcategory;
    }

    /**
     * Set brand
     *
     * @param \ARIPD\StoreBundle\Entity\Brand $brand
     * @return Product
     */
    public function setBrand(\ARIPD\StoreBundle\Entity\Brand $brand = null)
    {
        $this->brand = $brand;
    
        return $this;
    }

    /**
     * Get brand
     *
     * @return \ARIPD\StoreBundle\Entity\Brand 
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set taxonomy
     *
     * @param \ARIPD\AdminBundle\Entity\Taxonomy $taxonomy
     * @return Product
     */
    public function setTaxonomy(\ARIPD\AdminBundle\Entity\Taxonomy $taxonomy = null)
    {
        $this->taxonomy = $taxonomy;
    
        return $this;
    }

    /**
     * Get taxonomy
     *
     * @return \ARIPD\AdminBundle\Entity\Taxonomy 
     */
    public function getTaxonomy()
    {
        return $this->taxonomy;
    }

    /**
     * Add recommendsWithMe
     *
     * @param \ARIPD\StoreBundle\Entity\Product $recommendsWithMe
     * @return Product
     */
    public function addRecommendsWithMe(\ARIPD\StoreBundle\Entity\Product $recommendsWithMe)
    {
        $this->recommendsWithMe[] = $recommendsWithMe;
    
        return $this;
    }

    /**
     * Remove recommendsWithMe
     *
     * @param \ARIPD\StoreBundle\Entity\Product $recommendsWithMe
     */
    public function removeRecommendsWithMe(\ARIPD\StoreBundle\Entity\Product $recommendsWithMe)
    {
        $this->recommendsWithMe->removeElement($recommendsWithMe);
    }

    /**
     * Get recommendsWithMe
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRecommendsWithMe()
    {
        return $this->recommendsWithMe;
    }

    /**
     * Add myRecommends
     *
     * @param \ARIPD\StoreBundle\Entity\Product $myRecommends
     * @return Product
     */
    public function addMyRecommend(\ARIPD\StoreBundle\Entity\Product $myRecommends)
    {
        $this->myRecommends[] = $myRecommends;
    
        return $this;
    }

    /**
     * Remove myRecommends
     *
     * @param \ARIPD\StoreBundle\Entity\Product $myRecommends
     */
    public function removeMyRecommend(\ARIPD\StoreBundle\Entity\Product $myRecommends)
    {
        $this->myRecommends->removeElement($myRecommends);
    }

    /**
     * Get myRecommends
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMyRecommends()
    {
        return $this->myRecommends;
    }

    /**
     * Add comments
     *
     * @param \ARIPD\StoreBundle\Entity\Comment $comments
     * @return Product
     */
    public function addComment(\ARIPD\StoreBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;
    
        return $this;
    }

    /**
     * Remove comments
     *
     * @param \ARIPD\StoreBundle\Entity\Comment $comments
     */
    public function removeComment(\ARIPD\StoreBundle\Entity\Comment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add hits
     *
     * @param \ARIPD\StoreBundle\Entity\Hit $hits
     * @return Product
     */
    public function addHit(\ARIPD\StoreBundle\Entity\Hit $hits)
    {
        $this->hits[] = $hits;
    
        return $this;
    }

    /**
     * Remove hits
     *
     * @param \ARIPD\StoreBundle\Entity\Hit $hits
     */
    public function removeHit(\ARIPD\StoreBundle\Entity\Hit $hits)
    {
        $this->hits->removeElement($hits);
    }

    /**
     * Get hits
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHits()
    {
        return $this->hits;
    }
}