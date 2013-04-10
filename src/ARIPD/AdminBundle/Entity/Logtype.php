<?php
namespace ARIPD\AdminBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="admin_logtype")
 * @ORM\Entity
 */
class Logtype {

	public function __construct() {
		$this->logs = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column()
	 */
	protected $code;

	/**
	 * @ORM\Column()
	 */
	protected $name;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $bonus;

	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $sendemail = true;
	
	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\UserBundle\Entity\Log", mappedBy="logtype")
	 */
	protected $logs;

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
     * @return Logtype
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
     * @return Logtype
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
     * Set bonus
     *
     * @param integer $bonus
     * @return Logtype
     */
    public function setBonus($bonus)
    {
        $this->bonus = $bonus;
    
        return $this;
    }

    /**
     * Get bonus
     *
     * @return integer 
     */
    public function getBonus()
    {
        return $this->bonus;
    }

    /**
     * Set sendemail
     *
     * @param boolean $sendemail
     * @return Logtype
     */
    public function setSendemail($sendemail)
    {
        $this->sendemail = $sendemail;
    
        return $this;
    }

    /**
     * Get sendemail
     *
     * @return boolean 
     */
    public function getSendemail()
    {
        return $this->sendemail;
    }

    /**
     * Add logs
     *
     * @param \ARIPD\UserBundle\Entity\Log $logs
     * @return Logtype
     */
    public function addLog(\ARIPD\UserBundle\Entity\Log $logs)
    {
        $this->logs[] = $logs;
    
        return $this;
    }

    /**
     * Remove logs
     *
     * @param \ARIPD\UserBundle\Entity\Log $logs
     */
    public function removeLog(\ARIPD\UserBundle\Entity\Log $logs)
    {
        $this->logs->removeElement($logs);
    }

    /**
     * Get logs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLogs()
    {
        return $this->logs;
    }
}