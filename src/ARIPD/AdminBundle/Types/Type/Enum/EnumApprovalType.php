<?php
namespace ARIPD\DefaultBundle\Types\Type\Enum;
class EnumApprovalType extends EnumType {
	protected $name = 'enumapproval';
	protected $values = array('not approved', 'approved', 'rejected');
}
