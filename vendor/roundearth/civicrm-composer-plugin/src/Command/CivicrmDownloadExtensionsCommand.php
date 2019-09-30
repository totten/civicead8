<?php

namespace Roundearth\CivicrmComposerPlugin\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command class for 'civicrm:download-extensions' command.
 */
class CivicrmDownloadExtensionsCommand extends BaseCommand {

  protected function configure() {
    parent::configure();

    $this->setName('civicrm:download-extensions')
      ->setDescription('Download CiviCRM extensions defined in composer.json');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $this->createHandler()->downloadCivicrmExtensions();
  }


}