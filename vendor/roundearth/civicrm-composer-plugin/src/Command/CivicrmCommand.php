<?php

namespace Roundearth\CivicrmComposerPlugin\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command class for 'civicrm' command.
 */
class CivicrmCommand extends BaseCommand {

  protected function configure() {
    parent::configure();

    $this->setName('civicrm')
      ->setDescription('Run all the tasks usually run after civicrm/civicrm-core is installed');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $this->createHandler()->afterCivicrmInstallOrUpdate();
  }


}