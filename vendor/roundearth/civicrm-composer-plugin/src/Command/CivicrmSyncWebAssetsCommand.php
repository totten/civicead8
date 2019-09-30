<?php

namespace Roundearth\CivicrmComposerPlugin\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command class for 'civicrm:sync-web-assets' command.
 */
class CivicrmSyncWebAssetsCommand extends BaseCommand {

  protected function configure() {
    parent::configure();

    $this->setName('civicrm:sync-web-assets')
      ->setDescription('Sync web assets from civicrm/civicrm-core to the web root');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $this->createHandler()->syncWebAssetsToWebRoot();
  }


}