<?php
namespace ARIPD\StoreBundle\Entity;
use Symfony\Component\Filesystem\Filesystem;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="store_model")
 * @ORM\Entity
 * @UniqueEntity(fields = {"code"}, message = "This value is already used.")
 * @JMS\ExclusionPolicy("all")
 */
class Model {

	public function __construct() {
		$this->attributevalues = new \Doctrine\Common\Collections\ArrayCollection();
		$this->images = new \Doctrine\Common\Collections\ArrayCollection();
		$this->wishlists = new \Doctrine\Common\Collections\ArrayCollection();
		$this->baskets = new \Doctrine\Common\Collections\ArrayCollection();
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
	 * @JMS\Expose
	 */
	protected $code;

	/**
	 * Retail price
	 * 
	 * @ORM\Column(type="decimal", scale=2)
	 * @JMS\Expose
	 */
	protected $price;

	/**
	 * Retail price currency
	 * 
	 * @ORM\ManyToOne(targetEntity="ARIPD\AdminBundle\Entity\Iso4217")
	 * @ORM\JoinColumn(name="iso4217_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $iso4217;
	
	/**
	 * Bulk price
	 * 
	 * @ORM\Column(type="decimal", scale=2)
	 * @JMS\Expose
	 */
	protected $bulk_price;

	/**
	 * Bulk price currency
	 * 
	 * @ORM\ManyToOne(targetEntity="ARIPD\AdminBundle\Entity\Iso4217")
	 * @ORM\JoinColumn(name="bulk_iso4217_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $bulk_iso4217;
	
	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\StockBundle\Entity\Incoming", mappedBy="model")
	 */
	protected $incomings;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\StockBundle\Entity\Outgoing", mappedBy="model")
	 */
	protected $outgoings;

	/**
	 * Calculates stock quantity of the model
	 * 
	 * @return number
	 */
	public function getQuantity() {
		$incomingTotal = 0;
		foreach ($this->getIncomings() as $incoming) {
			$incomingTotal += $incoming->getQuantity();
		}
		
		$outgoingTotal = 0;
		foreach ($this->getOutgoings() as $outgoing) {
			$outgoingTotal += $outgoing->getQuantity();
		}
		
		return $incomingTotal-$outgoingTotal;
	}

	/**
	 * TODO Integrate currency to calculation
	 * Calculates unit cost of the model
	 * 
	 * @return number
	 */
	public function getCost() {
		$totalCost = 0;
		foreach ($this->getIncomings() as $incoming) {
			$totalCost += $incoming->getQuantity() * $incoming->getPrice();
		}
		return ($this->getQuantity()==0) ? 0 : $totalCost / $this->getQuantity();
	}

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\StoreBundle\Entity\Product", inversedBy="models")
	 * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
	 */
	protected $product;

	/**
	 * @ORM\Column(type="boolean", nullable=false)
	 */
	protected $prime = false;
	
	/**
	 * @ORM\ManyToMany(targetEntity="ARIPD\StoreBundle\Entity\Attributevalue", inversedBy="models", cascade={"persist"})
	 * @ORM\JoinTable(name="store_models_attributevalues",
	 *      joinColumns={@ORM\JoinColumn(name="model_id", referencedColumnName="id", onDelete="CASCADE")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="attributevalue_id", referencedColumnName="id", onDelete="CASCADE")}
	 * )
	 */
	protected $attributevalues;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\StoreBundle\Entity\Modelimage", mappedBy="model")
	 * @ORM\OrderBy({"prime" = "DESC"})
	 */
	protected $images;
	
	public function getImage() {
		foreach ($this->getImages() as $image)
			if ($image->getPrime()) return $image;
		return null;
	}
	
	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\UserBundle\Entity\Wishlist", mappedBy="model")
	 */
	protected $wishlists;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\UserBundle\Entity\Basket", mappedBy="model")
	 */
	protected $baskets;

	public function getSystemcode() {
		$systemcode = $this->getProduct()->getId();
		$systemcode .= ',' . $this->getId();
		if (count($this->getAttributevalues()) > 0) {
			foreach ($this->getAttributevalues() as $attributevalue) {
				$systemcode .= ','
						. $attributevalue->getAttributekey()->getId() . ':'
						. $attributevalue->getId();
			}
		}
		return $systemcode;
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
     * @return Model
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
     * Set price
     *
     * @param float $price
     * @return Model
     */
    public function setPrice($price)
    {
        $this->price = $price;
    
        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set bulk_price
     *
     * @param float $bulkPrice
     * @return Model
     */
    public function setBulkPrice($bulkPrice)
    {
        $this->bulk_price = $bulkPrice;
    
        return $this;
    }

    /**
     * Get bulk_price
     *
     * @return float 
     */
    public function getBulkPrice()
    {
        return $this->bulk_price;
    }

    /**
     * Set prime
     *
     * @param boolean $prime
     * @return Model
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
     * Set iso4217
     *
     * @param \ARIPD\AdminBundle\Entity\Iso4217 $iso4217
     * @return Model
     */
    public function setIso4217(\ARIPD\AdminBundle\Entity\Iso4217 $iso4217 = null)
    {
        $this->iso4217 = $iso4217;
    
        return $this;
    }

    /**
     * Get iso4217
     *
     * @return \ARIPD\AdminBundle\Entity\Iso4217 
     */
    public function getIso4217()
    {
        return $this->iso4217;
    }

    /**
     * Set bulk_iso4217
     *
     * @param \ARIPD\AdminBundle\Entity\Iso4217 $bulkIso4217
     * @return Model
     */
    public function setBulkIso4217(\ARIPD\AdminBundle\Entity\Iso4217 $bulkIso4217 = null)
    {
        $this->bulk_iso4217 = $bulkIso4217;
    
        return $this;
    }

    /**
     * Get bulk_iso4217
     *
     * @return \ARIPD\AdminBundle\Entity\Iso4217 
     */
    public function getBulkIso4217()
    {
        return $this->bulk_iso4217;
    }

    /**
     * Add incomings
     *
     * @param \ARIPD\StockBundle\Entity\Incoming $incomings
     * @return Model
     */
    public function addIncoming(\ARIPD\StockBundle\Entity\Incoming $incomings)
    {
        $this->incomings[] = $incomings;
    
        return $this;
    }

    /**
     * Remove incomings
     *
     * @param \ARIPD\StockBundle\Entity\Incoming $incomings
     */
    public function removeIncoming(\ARIPD\StockBundle\Entity\Incoming $incomings)
    {
        $this->incomings->removeElement($incomings);
    }

    /**
     * Get incomings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIncomings()
    {
        return $this->incomings;
    }

    /**
     * Add outgoings
     *
     * @param \ARIPD\StockBundle\Entity\Outgoing $outgoings
     * @return Model
     */
    public function addOutgoing(\ARIPD\StockBundle\Entity\Outgoing $outgoings)
    {
        $this->outgoings[] = $outgoings;
    
        return $this;
    }

    /**
     * Remove outgoings
     *
     * @param \ARIPD\StockBundle\Entity\Outgoing $outgoings
     */
    public function removeOutgoing(\ARIPD\StockBundle\Entity\Outgoing $outgoings)
    {
        $this->outgoings->removeElement($outgoings);
    }

    /**
     * Get outgoings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOutgoings()
    {
        return $this->outgoings;
    }

    /**
     * Set product
     *
     * @param \ARIPD\StoreBundle\Entity\Product $product
     * @return Model
     */
    public function setProduct(\ARIPD\StoreBundle\Entity\Product $product)
    {
        $this->product = $product;
    
        return $this;
    }

    /**
     * Get product
     *
     * @return \ARIPD\StoreBundle\Entity\Product 
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Add attributevalues
     *
     * @param \ARIPD\StoreBundle\Entity\Attributevalue $attributevalues
     * @return Model
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
     * Add images
     *
     * @param \ARIPD\StoreBundle\Entity\Modelimage $images
     * @return Model
     */
    public function addImage(\ARIPD\StoreBundle\Entity\Modelimage $images)
    {
        $this->images[] = $images;
    
        return $this;
    }

    /**
     * Remove images
     *
     * @param \ARIPD\StoreBundle\Entity\Modelimage $images
     */
    public function removeImage(\ARIPD\StoreBundle\Entity\Modelimage $images)
    {
        $this->images->removeElement($images);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Add wishlists
     *
     * @param \ARIPD\UserBundle\Entity\Wishlist $wishlists
     * @return Model
     */
    public function addWishlist(\ARIPD\UserBundle\Entity\Wishlist $wishlists)
    {
        $this->wishlists[] = $wishlists;
    
        return $this;
    }

    /**
     * Remove wishlists
     *
     * @param \ARIPD\UserBundle\Entity\Wishlist $wishlists
     */
    public function removeWishlist(\ARIPD\UserBundle\Entity\Wishlist $wishlists)
    {
        $this->wishlists->removeElement($wishlists);
    }

    /**
     * Get wishlists
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getWishlists()
    {
        return $this->wishlists;
    }

    /**
     * Add baskets
     *
     * @param \ARIPD\UserBundle\Entity\Basket $baskets
     * @return Model
     */
    public function addBasket(\ARIPD\UserBundle\Entity\Basket $baskets)
    {
        $this->baskets[] = $baskets;
    
        return $this;
    }

    /**
     * Remove baskets
     *
     * @param \ARIPD\UserBundle\Entity\Basket $baskets
     */
    public function removeBasket(\ARIPD\UserBundle\Entity\Basket $baskets)
    {
        $this->baskets->removeElement($baskets);
    }

    /**
     * Get baskets
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBaskets()
    {
        return $this->baskets;
    }
}