<?php

namespace Roundearth\CivicrmComposerPlugin;

use Composer\Plugin\Capability\CommandProvider as CommandProviderCapability;
use Roundearth\CivicrmComposerPlugin\Command\CivicrmAddMissingFilesCommand;
use Roundearth\CivicrmComposerPlugin\Command\CivicrmCommand;
use Roundearth\CivicrmComposerPlugin\Command\CivicrmDownloadExtensionsCommand;
use Roundearth\CivicrmComposerPlugin\Command\CivicrmRunBowerCommand;
use Roundearth\CivicrmComposerPlugin\Command\CivicrmSyncWebAssetsCommand;

/**
 * Provides all the commands for this plugin.
 */
class CommandProvider implements CommandProviderCapability {

  /**
   * {@inheritdoc}
   */
  public function getCommands() {
    return [
      new CivicrmCommand(),
      new CivicrmRunBowerCommand(),
      new CivicrmAddMissingFilesCommand(),
      new CivicrmDownloadExtensionsCommand(),
      new CivicrmSyncWebAssetsCommand(),
    ];
  }

}