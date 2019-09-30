<?php

namespace Roundearth\CivicrmComposerPlugin;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Package\Package;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

/**
 * Handler service does all the actual work of the plugin. :-)
 */
class Handler {

  const ASSET_EXTENSIONS = [
    'html',
    'js',
    'css',
    'svg',
    'png',
    'jpg',
    'jpeg',
    'ico',
    'gif',
    'woff',
    'woff2',
    'ttf',
    'eot',
    'swf',
  ];

  /**
   * @var \Composer\Composer
   */
  protected $composer;

  /**
   * @var \Composer\IO\IOInterface
   */
  protected $io;

  /**
   * @var \Symfony\Component\Filesystem\Filesystem
   */
  protected $filesystem;

  /**
   * @var \Roundearth\CivicrmComposerPlugin\Util
   */
  protected $util;

  /**
   * Handler constructor.
   *
   * @param \Composer\Composer $composer
   *   The composer object.
   * @param \Composer\IO\IOInterface $io
   *   The composer I/O object.
   * @param \Symfony\Component\Filesystem\Filesystem $filesystem
   *   The filesystem service.
   * @param \Roundearth\CivicrmComposerPlugin\Util $util
   *   The util service.
   */
  public function __construct(Composer $composer, IOInterface $io, Filesystem $filesystem, Util $util) {
    $this->composer = $composer;
    $this->io = $io;
    $this->filesystem = $filesystem;
    $this->util = $util;
  }

   /**
   * Gets the path to the CiviCRM code.
   *
   * @return string
   */
  protected function getCivicrmCorePath() {
    $vendor_path = $this->composer->getConfig()->get('vendor-dir');
    return "{$vendor_path}/civicrm/civicrm-core";
  }

  /**
   * Gets the CiviCRM core version.
   *
   * @param \Composer\Package\Package|NULL $package
   *   The package that was just installed or updated.
   *
   * @return mixed
   */
  protected function getCivicrmCoreVersion(Package $package = NULL) {
    if (!$package) {
      $package = $this->getCivicrmCorePackage();
      if (!$package) {
        throw new \RuntimeException("The civicrm/civicrm-core package doesn't appear to be installed. Did you forget to run 'composer require civicrm/civicrm-core'?");
      }
    }

    if (preg_match('/(\d+\.\d+\.\d+)/', $package->getPrettyVersion(), $matches)) {
      $civicrm_version = $matches[1];
    }
    else {
      // @todo Allow the user to give a version number.
      throw new \RuntimeException("Unable to determine CiviCRM release version from {$package->getPrettyVersion()}");
    }

    return $civicrm_version;
  }

  /**
   * Gets the currently installed CiviCRM core package.
   *
   * @return \Composer\Package\Package
   *   The package.
   */
  protected function getCivicrmCorePackage() {
    /** @var \Composer\Repository\RepositoryManager $repository_manager */
    $repository_manager = $this->composer->getRepositoryManager();

    /** @var \Composer\Repository\RepositoryInterface $local_repository */
    $local_repository = $repository_manager->getLocalRepository();

    /** @var \Composer\Package\Package $package */
    foreach ($local_repository->getPackages() as $package) {
      if ($package->getName() == 'civicrm/civicrm-core') {
        return $package;
      }
    }

    throw new \RuntimeException("Unable to find civicrm/civicrm-core package");
  }

  /**
   * Does all the stuff we want to do after CiviCRM has been installed.
   */
  public function afterCivicrmInstallOrUpdate(Package $civicrm_package = NULL) {
    $this->runBower();
    $this->addMissingCivicrmFiles($this->getCivicrmCoreVersion($civicrm_package));
    $this->downloadCivicrmExtensions();
    $this->syncWebAssetsToWebRoot();
  }

  /**
   * Outputs a message to the user.
   *
   * @param string $message
   *   The message.
   * @param bool $newline
   *   Whether or not to add a newline.
   * @param int $verbosity
   *   The verbosity.
   */
  protected function output($message, $newline = TRUE, $verbosity = IOInterface::NORMAL) {
    $this->io->write("> [civicrm-composer-plugin] {$message}", $newline, $verbosity);
  }

  /**
   * Runs bower in civicrm-core/civicrm.
   */
  public function runBower() {
    $this->output("<info>Running bower for CiviCRM...</info>");

    $bower = new Process("bower install", $this->getCivicrmCorePath());
    $bower->setTimeout(NULL);
    $bower->mustRun();

    $this->output($bower->getOutput(), FALSE, IOInterface::VERBOSE);
  }

  /**
   * Adds all the missing files from the release tarball.
   *
   * @param string|null $civicrm_version
   *   The CiviCRM version.
   */
  public function addMissingCivicrmFiles($civicrm_version = NULL) {
    if (!$civicrm_version) {
      $civicrm_version = $this->getCivicrmCoreVersion();
    }
    $civicrm_core_path = $this->getCivicrmCorePath();
    $civicrm_archive_url = "https://download.civicrm.org/civicrm-{$civicrm_version}-drupal.tar.gz";
    $civicrm_archive_file = tempnam(sys_get_temp_dir(), "drupal-civicrm-archive-");
    $civicrm_extract_path = tempnam(sys_get_temp_dir(), "drupal-civicrm-extract-");

    // Convert the extract path into a directory.
    $this->filesystem->remove($civicrm_extract_path);
    $this->filesystem->mkdir($civicrm_extract_path);

    try {
      $this->output("<info>Downloading CiviCRM {$civicrm_version} release...</info>");
      $this->filesystem->dumpFile($civicrm_archive_file, fopen($civicrm_archive_url, 'r'));

      $this->output("<info>Extracting CiviCRM {$civicrm_version} release...</info>");
      $extract_successful = (new \Archive_Tar($civicrm_archive_file, "gz"))->extract($civicrm_extract_path);
      if (!$extract_successful) {
        throw new \RuntimeException("Unable to extract: $civicrm_archive_file");
      }

      $this->output("<info>Copying missing files from CiviCRM release...</info>");

      $this->filesystem->mirror("{$civicrm_extract_path}/civicrm/packages", "{$civicrm_core_path}/packages");
      $this->filesystem->mirror("{$civicrm_extract_path}/civicrm/sql", "{$civicrm_core_path}/sql");

      $this->filesystem->dumpFile("{$civicrm_core_path}/civicrm-version.php", str_replace('Drupal', 'Drupal8', file_get_contents("{$civicrm_extract_path}/civicrm/civicrm-version.php")));

      $simple_copy_list = [
        'civicrm.config.php',
        'CRM/Core/I18n/SchemaStructure.php',
        'install/langs.php',
      ];
      foreach ($simple_copy_list as $file) {
        $this->filesystem->copy("{$civicrm_extract_path}/civicrm/{$file}", "{$civicrm_core_path}/{$file}");
      }
    }
    finally {
      if ($this->filesystem->exists($civicrm_archive_file)) {
        $this->filesystem->remove($civicrm_archive_file);
      }

      if ($this->filesystem->exists($civicrm_extract_path)) {
        $this->util->removeDirectoryRecursively($civicrm_extract_path);
      }
    }
  }

  /**
   * Download CiviCRM extensions based on configuration in 'extra'.
   */
  public function downloadCivicrmExtensions() {
    /** @var \Composer\Package\RootPackageInterface $package */
    $package = $this->composer->getPackage();
    $extra = $package->getExtra();

    if (!empty($extra['civicrm']['extensions'])) {
      foreach ($extra['civicrm']['extensions'] as $name => $info) {
        if (!is_array($info)) {
          $info = ['url' => $info];
        }
        if (!isset($info['patches']) || !is_array($info['patches'])) {
          $info['patches'] = [];
        }
        $this->downloadCivicrmExtension($name, $info['url'], $info['patches']);
      }
    }
  }

  /**
   * Download a single CiviCRM extension.
   *
   * @param string $name
   *   The extension name.
   * @param string $url
   *   The URL to the zip archive.
   * @param string[] $patches
   *   A list of patches to apply to the extension.
   */
  protected function downloadCivicrmExtension($name, $url, array $patches) {
    $extension_archive_file = tempnam(sys_get_temp_dir(), "drupal-civicrm-extension-");
    $this->output("<info>Downloading CiviCRM extension {$name} from {$url}...</info>");
    $this->filesystem->dumpFile($extension_archive_file, fopen($url, 'r'));

    $extension_path = $this->getCivicrmCorePath() . '/tools/extensions';
    $destination_path = "{$extension_path}/{$name}";

    // Remove any old copies of the extension laying around.
    if ($this->filesystem->exists($destination_path)) {
      $this->util->removeDirectoryRecursively($destination_path);
    }

    // Extract the zip archive (recording the first file to figure out what
    // path it extracts to).
    $firstFile = NULL;
    try {
      $zip = new \ZipArchive();
      $zip->open($extension_archive_file);
      $firstFile = $zip->getNameIndex(0);
      $zip->extractTo($extension_path);
      $zip->close();
    }
    finally {
      $this->filesystem->remove($extension_archive_file);
    }

    // If the extension directory wasn't named like the extension name, then
    // attempt to rename it.
    if (!$this->filesystem->exists($destination_path)) {
      $parts = explode('/', $firstFile);
      if (count($parts) > 1) {
        $this->filesystem->rename("{$extension_path}/{$parts[0]}", $destination_path);
      }
    }

    // If there are any patches for this extension.
    if (!empty($patches)) {
      foreach ($patches as $patch) {
        $this->output("|-> Applying patch: $patch");
        $process = new Process("patch -p1", $destination_path);
        $process->setInput(file_get_contents($patch));
        $process->mustRun();
      }
    }
  }

  /**
   * Syncs web assets from CiviCRM to the web root.
   */
  public function syncWebAssetsToWebRoot() {
    $source = $this->getCivicrmCorePath();
    $destination = './web/libraries/civicrm';
    $this->output("<info>Syncing CiviCRM web assets to /web/libraries/civicrm...</info>");

    $this->util->removeDirectoryRecursively($destination);

    $this->util->mirrorFilesWithExtensions($source, $destination, static::ASSET_EXTENSIONS);

    $this->util->removeDirectoryRecursively("{$destination}/tests");

    $this->filesystem->mirror("{$source}/extern", "{$destination}/extern");
    $this->filesystem->mirror("{$source}/packages/kcfinder", "{$destination}/packages/kcfinder");
    $this->filesystem->copy("{$source}/civicrm.config.php", "{$destination}/civicrm.config.php");

    $settings_location_php = <<<EOF
<?php

define('CIVICRM_CONFDIR', dirname(dirname(dirname(__FILE__))) . '/sites');
EOF;
    $this->filesystem->dumpFile("{$destination}/settings_location.php", $settings_location_php);
  }

}
