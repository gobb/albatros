<?php
namespace ARIPD\IntranetBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="intranet_tcmb")
 * @ORM\Entity
 */
class TCMB {

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $date;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $code;

	/**
	 * @var float $forexbuying
	 * 
	 * @ORM\Column(type="decimal", scale=4)
	 */
	private $forexbuying;

	/**
	 * @var float $forexselling
	 * 
	 * @ORM\Column(type="decimal", scale=4)
	 */
	private $forexselling;

	/**
	 * @var float $banknotebuying
	 * 
	 * @ORM\Column(type="decimal", scale=4)
	 */
	private $banknotebuying;

	/**
	 * @var float $banknoteselling
	 * 
	 * @ORM\Column(type="decimal", scale=4)
	 */
	private $banknoteselling;

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
     * Set date
     *
     * @param \DateTime $date
     * @return TCMB
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return TCMB
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
     * Set forexbuying
     *
     * @param float $forexbuying
     * @return TCMB
     */
    public function setForexbuying($forexbuying)
    {
        $this->forexbuying = $forexbuying;
    
        return $this;
    }

    /**
     * Get forexbuying
     *
     * @return float 
     */
    public function getForexbuying()
    {
        return $this->forexbuying;
    }

    /**
     * Set forexselling
     *
     * @param float $forexselling
     * @return TCMB
     */
    public function setForexselling($forexselling)
    {
        $this->forexselling = $forexselling;
    
        return $this;
    }

    /**
     * Get forexselling
     *
     * @return float 
     */
    public function getForexselling()
    {
        return $this->forexselling;
    }

    /**
     * Set banknotebuying
     *
     * @param float $banknotebuying
     * @return TCMB
     */
    public function setBanknotebuying($banknotebuying)
    {
        $this->banknotebuying = $banknotebuying;
    
        return $this;
    }

    /**
     * Get banknotebuying
     *
     * @return float 
     */
    public function getBanknotebuying()
    {
        return $this->banknotebuying;
    }

    /**
     * Set banknoteselling
     *
     * @param float $banknoteselling
     * @return TCMB
     */
    public function setBanknoteselling($banknoteselling)
    {
        $this->banknoteselling = $banknoteselling;
    
        return $this;
    }

    /**
     * Get banknoteselling
     *
     * @return float 
     */
    public function getBanknoteselling()
    {
        return $this->banknoteselling;
    }
}