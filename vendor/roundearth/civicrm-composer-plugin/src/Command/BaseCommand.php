<?php

namespace Roundearth\CivicrmComposerPlugin\Command;

use Composer\Command\BaseCommand as ComposerBaseCommand;
use Roundearth\CivicrmComposerPlugin\Handler;
use Roundearth\CivicrmComposerPlugin\Util;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Base class for all our commands.
 */
class BaseCommand extends ComposerBaseCommand {

  /**
   * Creates a new handler object.
   *
   * @return \Roundearth\CivicrmComposerPlugin\Handler
   *   A new handler service.
   */
  protected function createHandler() {
    $filesystem = new Filesystem();
    $util = new Util($filesystem);
    return new Handler($this->getComposer(), $this->getIO(), $filesystem, $util);
  }

}