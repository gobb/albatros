<?php
namespace ARIPD\ShoppingBundle\Form\Model;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\MinLength;
use Symfony\Component\Validator\Constraints\MaxLength;
use Symfony\Component\Validator\Constraints\Type;

class VoucherEnquiry {
	protected $vouchercode;

	public function getVouchercode() {
		return $this->vouchercode;
	}

	public function setVouchercode($vouchercode) {
		$this->vouchercode = $vouchercode;
	}

	public static function loadValidatorMetadata(ClassMetadata $metadata) {
		//$metadata->addPropertyConstraint('vouchercode', new NotBlank());
	}
}
