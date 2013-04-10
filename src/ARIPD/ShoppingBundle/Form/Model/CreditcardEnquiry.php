<?php
namespace ARIPD\ShoppingBundle\Form\Model;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

class CreditcardEnquiry {

	/**
	 * @Assert\NotBlank(message = "This value should not be blank.")
	 * @Assert\CardScheme(schemes = {"MASTERCARD", "VISA"}, message = "Unsupported card type or invalid card number.")
	 * @Assert\Luhn(message = "Invalid card number.")
	 */
	protected $ccno;

	/**
	 * @Assert\NotBlank(message = "This value should not be blank.")
	 * @Assert\Type(type="numeric", message="The value {{ value }} is not a valid {{ type }}.")
	 * @Assert\Length(min=3, max=4, minMessage="This value is too short. It should have {{ limit }} characters or more.", maxMessage="This value is too long. It should have {{ limit }} characters or less.")
	 */
	protected $cvc;

	/**
	 * @Assert\NotBlank(message = "This value should not be blank.")
	 */
	protected $expdateYear;

	/**
	 * @Assert\NotBlank(message = "This value should not be blank.")
	 */
	protected $expdateMonth;

	/**
	 * @Assert\NotBlank(message = "This value should not be blank.")
	 * @Assert\Type(type="double", message="The value {{ value }} is not a valid {{ type }}.")
	 */
	protected $amount;

	public function getCcno() {
		return $this->ccno;
	}

	public function setCcno($ccno) {
		$this->ccno = $ccno;
	}

	public function getCvc() {
		return $this->cvc;
	}

	public function setCvc($cvc) {
		$this->cvc = $cvc;
	}

	public function getExpdateYear() {
		return $this->expdateYear;
	}

	public function setExpdateYear($expdateYear) {
		$this->expdateYear = $expdateYear;
	}

	public function getExpdateMonth() {
		return $this->expdateMonth;
	}

	public function setExpdateMonth($expdateMonth) {
		$this->expdateMonth = $expdateMonth;
	}

	public function getAmount() {
		return $this->amount;
	}

	public function setAmount($amount) {
		$this->amount = $amount;
	}

}
