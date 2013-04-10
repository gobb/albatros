<?php
// http://symfony.com/doc/master/components/console.html

namespace ARIPD\AdminBundle\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FxrateCommand extends ContainerAwareCommand {
	
	protected function configure() {
		$this
				->setName('admin:fxrate')
				->setDescription('Döviz verilerini indirir')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$this->getContainer()->get('aripdadmin.fxrate_service')->test();
	}
	
}
