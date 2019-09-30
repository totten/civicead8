<?php

namespace Roundearth\CivicrmComposerPlugin\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command class for 'civicrm:run-bower' command.
 */
class CivicrmRunBowerCommand extends BaseCommand {

  protected function configure() {
    parent::configure();

    $this->setName('civicrm:run-bower')
      ->setDescription('Run bower in civicrm/civicrm-core');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $this->createHandler()->runBower();
  }


}