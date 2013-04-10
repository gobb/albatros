<?php
namespace ARIPD\AdminBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="admin_iso3166")
 * @ORM\Entity
 */
class Iso3166 {
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $name;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $native;

	/**
	 * @ORM\Column(type="string", length=3, unique=true)
	 */
	protected $an;

	/**
	 * @ORM\Column(type="string", length=2, unique=true)
	 */
	protected $a2;

	/**
	 * @ORM\Column(type="string", length=3, unique=true)
	 */
	protected $a3;

	/**
	 * @ORM\Column(type="string", length=30)
	 */
	protected $phonecode;

	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $active = false;

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
     * @return Iso3166
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
     * Set native
     *
     * @param string $native
     * @return Iso3166
     */
    public function setNative($native)
    {
        $this->native = $native;
    
        return $this;
    }

    /**
     * Get native
     *
     * @return string 
     */
    public function getNative()
    {
        return $this->native;
    }

    /**
     * Set an
     *
     * @param string $an
     * @return Iso3166
     */
    public function setAn($an)
    {
        $this->an = $an;
    
        return $this;
    }

    /**
     * Get an
     *
     * @return string 
     */
    public function getAn()
    {
        return $this->an;
    }

    /**
     * Set a2
     *
     * @param string $a2
     * @return Iso3166
     */
    public function setA2($a2)
    {
        $this->a2 = $a2;
    
        return $this;
    }

    /**
     * Get a2
     *
     * @return string 
     */
    public function getA2()
    {
        return $this->a2;
    }

    /**
     * Set a3
     *
     * @param string $a3
     * @return Iso3166
     */
    public function setA3($a3)
    {
        $this->a3 = $a3;
    
        return $this;
    }

    /**
     * Get a3
     *
     * @return string 
     */
    public function getA3()
    {
        return $this->a3;
    }

    /**
     * Set phonecode
     *
     * @param string $phonecode
     * @return Iso3166
     */
    public function setPhonecode($phonecode)
    {
        $this->phonecode = $phonecode;
    
        return $this;
    }

    /**
     * Get phonecode
     *
     * @return string 
     */
    public function getPhonecode()
    {
        return $this->phonecode;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Iso3166
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
}