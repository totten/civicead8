<?php

namespace Roundearth\CivicrmComposerPlugin\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command class for 'civicrm:add-missing-files' command.
 */
class CivicrmAddMissingFilesCommand extends BaseCommand {

  protected function configure() {
    parent::configure();

    $this->setName('civicrm:add-missing-files')
      ->setDescription('Add missing files from CiviCRM release');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $this->createHandler()->addMissingCivicrmFiles();
  }


}