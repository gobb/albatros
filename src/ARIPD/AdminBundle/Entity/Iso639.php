<?php
namespace ARIPD\AdminBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="admin_iso639")
 * @ORM\Entity
 */
class Iso639 {
	/**
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

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
	protected $name;

	/**
	 * @ORM\Column(type="string", length=30)
	 */
	protected $native;

	/**
	 * @ORM\Column(type="boolean", nullable=false)
	 */
	protected $active = false;
	
	/**
	 * @ORM\OneToOne(targetEntity="ARIPD\AdminBundle\Entity\Iso4217")
	 * @ORM\JoinColumn(name="iso4217_id", referencedColumnName="id")
	 **/
	protected $iso4217;

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
     * Set a2
     *
     * @param string $a2
     * @return Iso639
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
     * @return Iso639
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
     * Set name
     *
     * @param string $name
     * @return Iso639
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
     * @return Iso639
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
     * Set active
     *
     * @param boolean $active
     * @return Iso639
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
     * Set iso4217
     *
     * @param \ARIPD\AdminBundle\Entity\Iso4217 $iso4217
     * @return Iso639
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
}