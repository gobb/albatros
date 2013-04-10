<?php
namespace ARIPD\AdminBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="admin_creditcard")
 * @ORM\Entity
 */
class Creditcard {
	/**
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=6)
	 */
	protected $bin;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\AdminBundle\Entity\Bank", inversedBy="creditcards")
	 * @ORM\JoinColumn(name="bank_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $bank;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\AdminBundle\Entity\Iso3166")
	 * @ORM\JoinColumn(name="iso3166_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $iso3166;
	
	/**
	 * @ORM\Column(type="string", length=10)
	 */
	protected $product;

	/**
	 * @ORM\Column(type="string", length=10)
	 */
	protected $organization;

	/**
	 * @ORM\Column(type="string", length=1)
	 */
	protected $t;

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
     * Set bin
     *
     * @param string $bin
     * @return Creditcard
     */
    public function setBin($bin)
    {
        $this->bin = $bin;
    
        return $this;
    }

    /**
     * Get bin
     *
     * @return string 
     */
    public function getBin()
    {
        return $this->bin;
    }

    /**
     * Set product
     *
     * @param string $product
     * @return Creditcard
     */
    public function setProduct($product)
    {
        $this->product = $product;
    
        return $this;
    }

    /**
     * Get product
     *
     * @return string 
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set organization
     *
     * @param string $organization
     * @return Creditcard
     */
    public function setOrganization($organization)
    {
        $this->organization = $organization;
    
        return $this;
    }

    /**
     * Get organization
     *
     * @return string 
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * Set t
     *
     * @param string $t
     * @return Creditcard
     */
    public function setT($t)
    {
        $this->t = $t;
    
        return $this;
    }

    /**
     * Get t
     *
     * @return string 
     */
    public function getT()
    {
        return $this->t;
    }

    /**
     * Set bank
     *
     * @param \ARIPD\AdminBundle\Entity\Bank $bank
     * @return Creditcard
     */
    public function setBank(\ARIPD\AdminBundle\Entity\Bank $bank = null)
    {
        $this->bank = $bank;
    
        return $this;
    }

    /**
     * Get bank
     *
     * @return \ARIPD\AdminBundle\Entity\Bank 
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * Set iso3166
     *
     * @param \ARIPD\AdminBundle\Entity\Iso3166 $iso3166
     * @return Creditcard
     */
    public function setIso3166(\ARIPD\AdminBundle\Entity\Iso3166 $iso3166 = null)
    {
        $this->iso3166 = $iso3166;
    
        return $this;
    }

    /**
     * Get iso3166
     *
     * @return \ARIPD\AdminBundle\Entity\Iso3166 
     */
    public function getIso3166()
    {
        return $this->iso3166;
    }
}