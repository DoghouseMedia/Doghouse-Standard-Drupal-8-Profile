<?php

/**
 * @file
 * Contains \DrupalProject\composer\ScriptHandler.
 */

namespace DoghouseProfile\composer;

use Composer\Script\Event;
use Symfony\Component\Filesystem\Filesystem;

class CleanProfile {

  public static function deleteCoreFiles(Event $event) {
    $fs = new Filesystem();
    $dirs = [
      'vendor',
      'sites',
      'core',
      '.csslintrc',
      '.editorconfig',
      '.eslintignore',
      '.eslintrc',
      '.gitattributes',
      '.htaccess',
      'autoload.php',
      'index.php',
      'robots.txt',
      'update.php',
      'web.config',
    ];

    try {
      $fs->remove($dirs);
    }
    catch (IOExceptionInterface $e) {
      echo sprintf('An error occurred while removing the directory %s', $e->getPath());
    }
  }
}
