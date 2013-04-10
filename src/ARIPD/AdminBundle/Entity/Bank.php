<?php
namespace ARIPD\AdminBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="admin_bank")
 * @ORM\Entity
 */
class Bank {
	public function __construct() {
		$this->creditcards = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=4, unique=true)
	 */
	protected $code;

	/**
	 * @ORM\Column(type="string", length=30)
	 */
	protected $name;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\AdminBundle\Entity\Creditcard", mappedBy="bank")
	 */
	protected $creditcards;

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
     * @return Bank
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
     * @return Bank
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
     * Add creditcards
     *
     * @param \ARIPD\AdminBundle\Entity\Creditcard $creditcards
     * @return Bank
     */
    public function addCreditcard(\ARIPD\AdminBundle\Entity\Creditcard $creditcards)
    {
        $this->creditcards[] = $creditcards;
    
        return $this;
    }

    /**
     * Remove creditcards
     *
     * @param \ARIPD\AdminBundle\Entity\Creditcard $creditcards
     */
    public function removeCreditcard(\ARIPD\AdminBundle\Entity\Creditcard $creditcards)
    {
        $this->creditcards->removeElement($creditcards);
    }

    /**
     * Get creditcards
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCreditcards()
    {
        return $this->creditcards;
    }
}