<?php
namespace ARIPD\AdminBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="admin_iso4217")
 * @ORM\Entity
 */
class Iso4217 {
	
	public function __construct() {
		$this->fxrates = new \Doctrine\Common\Collections\ArrayCollection();
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
	 * @ORM\Column(type="string", length=3, unique=true)
	 */
	protected $code;

	/**
	 * @ORM\Column(type="string", length=3, unique=true)
	 */
	protected $no;
	
	/**
	 * @ORM\Column(type="string", length=3, unique=true)
	 */
	protected $symbol;

	/**
	 * @ORM\Column(type="boolean", nullable=false)
	 */
	protected $active = false;

	/**
	 * @ORM\Column(type="integer")
	 */
	protected $decimalPlaces;

	/**
	 * @ORM\Column(type="string", length=1)
	 */
	protected $decimalPoint;

	/**
	 * @ORM\Column(type="string", length=1)
	 */
	protected $thousandsSeperator;

	/**
	 * @ORM\Column(type="decimal", scale=5)
	 */
	protected $rate;
	
	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\AdminBundle\Entity\Fxrate", mappedBy="iso4217")
	 */
	protected $fxrates;

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
     * @return Iso4217
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
     * Set code
     *
     * @param string $code
     * @return Iso4217
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
     * Set no
     *
     * @param string $no
     * @return Iso4217
     */
    public function setNo($no)
    {
        $this->no = $no;
    
        return $this;
    }

    /**
     * Get no
     *
     * @return string 
     */
    public function getNo()
    {
        return $this->no;
    }

    /**
     * Set symbol
     *
     * @param string $symbol
     * @return Iso4217
     */
    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;
    
        return $this;
    }

    /**
     * Get symbol
     *
     * @return string 
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Iso4217
     */
    public function setActive($active)
    {
        $this->active = $active;
    
        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set decimalPlaces
     *
     * @param integer $decimalPlaces
     * @return Iso4217
     */
    public function setDecimalPlaces($decimalPlaces)
    {
        $this->decimalPlaces = $decimalPlaces;
    
        return $this;
    }

    /**
     * Get decimalPlaces
     *
     * @return integer 
     */
    public function getDecimalPlaces()
    {
        return $this->decimalPlaces;
    }

    /**
     * Set decimalPoint
     *
     * @param string $decimalPoint
     * @return Iso4217
     */
    public function setDecimalPoint($decimalPoint)
    {
        $this->decimalPoint = $decimalPoint;
    
        return $this;
    }

    /**
     * Get decimalPoint
     *
     * @return string 
     */
    public function getDecimalPoint()
    {
        return $this->decimalPoint;
    }

    /**
     * Set thousandsSeperator
     *
     * @param string $thousandsSeperator
     * @return Iso4217
     */
    public function setThousandsSeperator($thousandsSeperator)
    {
        $this->thousandsSeperator = $thousandsSeperator;
    
        return $this;
    }

    /**
     * Get thousandsSeperator
     *
     * @return string 
     */
    public function getThousandsSeperator()
    {
        return $this->thousandsSeperator;
    }

    /**
     * Set rate
     *
     * @param float $rate
     * @return Iso4217
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
    
        return $this;
    }

    /**
     * Get rate
     *
     * @return float 
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Add fxrates
     *
     * @param \ARIPD\AdminBundle\Entity\Fxrate $fxrates
     * @return Iso4217
     */
    public function addFxrate(\ARIPD\AdminBundle\Entity\Fxrate $fxrates)
    {
        $this->fxrates[] = $fxrates;
    
        return $this;
    }

    /**
     * Remove fxrates
     *
     * @param \ARIPD\AdminBundle\Entity\Fxrate $fxrates
     */
    public function removeFxrate(\ARIPD\AdminBundle\Entity\Fxrate $fxrates)
    {
        $this->fxrates->removeElement($fxrates);
    }

    /**
     * Get fxrates
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFxrates()
    {
        return $this->fxrates;
    }
}