<?php
namespace ARIPD\UserBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ARIPDUserBundle extends Bundle {
	public function getParent() {
		return 'FOSUserBundle';
	}
}
