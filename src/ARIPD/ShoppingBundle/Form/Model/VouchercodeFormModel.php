<?php
namespace ARIPD\ShoppingBundle\Form\Model;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class VouchercodeFormModel {

	protected $voucher;
	
	protected $quantity;

	public function getVoucher() {
		return $this->voucher;
	}

	public function setVoucher(\ARIPD\ShoppingBundle\Entity\Voucher $voucher) {
		$this->voucher = $voucher;
	}

	public function getQuantity() {
		return $this->quantity;
	}

	public function setQuantity($quantity) {
		$this->quantity = $quantity;
	}

	public static function loadValidatorMetadata(ClassMetadata $metadata) {
		$metadata->addPropertyConstraint('quantity', new NotBlank());
	}

}
