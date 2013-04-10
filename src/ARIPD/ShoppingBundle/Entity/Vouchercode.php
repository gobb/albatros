<?php
namespace ARIPD\ShoppingBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="shopping_vouchercode")
 * @ORM\Entity(repositoryClass="ARIPD\ShoppingBundle\Repository\VouchercodeRepository")
 */
class Vouchercode {
	/**
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(name="vouchercode", type="string", length=255, unique=true)
	 */
	protected $vouchercode;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\ShoppingBundle\Entity\Voucher", inversedBy="vouchercodes")
	 * @ORM\JoinColumn(name="voucher_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $voucher;

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
     * Set vouchercode
     *
     * @param string $vouchercode
     * @return Vouchercode
     */
    public function setVouchercode($vouchercode)
    {
        $this->vouchercode = $vouchercode;
    
        return $this;
    }

    /**
     * Get vouchercode
     *
     * @return string 
     */
    public function getVouchercode()
    {
        return $this->vouchercode;
    }

    /**
     * Set voucher
     *
     * @param \ARIPD\ShoppingBundle\Entity\Voucher $voucher
     * @return Vouchercode
     */
    public function setVoucher(\ARIPD\ShoppingBundle\Entity\Voucher $voucher = null)
    {
        $this->voucher = $voucher;
    
        return $this;
    }

    /**
     * Get voucher
     *
     * @return \ARIPD\ShoppingBundle\Entity\Voucher 
     */
    public function getVoucher()
    {
        return $this->voucher;
    }
}