<?php

/**
* Pantheon:
* Populate needed variables based on the Pantheon environment.
*/

// This is the only variable in this file that should need to change between pantheon/civi sites.
$siteName = '-civicead8.pantheonsite.io';

// This assumes https.
$pantheonDomain = 'https://' . $_ENV['PANTHEON_ENVIRONMENT'] . $siteName;

// All Pantheon Environments.
if (defined('PANTHEON_ENVIRONMENT')) {
  // Extract Pressflow settings into a php object.
  $pressflow_settings = json_decode($_SERVER['PRESSFLOW_SETTINGS']);
// var_dump($pressflow_settings);
// Drupal Root Info
  $pantheon_conf = $pressflow_settings->conf;
  $pantheon_root_dir = "/srv/bindings/" . $pantheon_conf->pantheon_binding;
  $webRoot = $pantheon_root_dir . '/code/web';
  $fileRoot = $pantheon_root_dir . '/files';
  //$publicFileDir =   $webRoot . $pantheon_conf->file_directory_path;

  // DB Info
  $pantheon_db = $pressflow_settings->databases->default->default;
  $pantheon_db_string = $pantheon_db->driver . "://" . $pantheon_db->username . ":";
  $pantheon_db_string .= $pantheon_db->password . "@" . $pantheon_db->host . ":";
  $pantheon_db_string .= $pantheon_db->port . "/" . $pantheon_db->database . "?new_link=true";

  // Redis info.
  $redHost = $pantheon_conf->redis_client_host ;
  $redPass = $pantheon_conf->redis_client_password;
  $redPort = $pantheon_conf->redis_client_port;

}

// if (!empty($_SERVER['PRESSFLOW_SETTINGS'])) {
//   $env = json_decode($_SERVER['PRESSFLOW_SETTINGS'], TRUE);
//   if (!empty($env['conf']['pantheon_binding'])) {
//     $pantheon_db = $env['databases']['default']['default'];
//     $pantheon_conf = $env['conf'];
//
//     // Database Username and Password
//     $pantheon_db_string = $pantheon_db['driver'] . '://' . $pantheon_db['username'] . ':' . $pantheon_db['password'] . '@';
//     // Host
//     $pantheon_db_string .= 'dbserver.' . $pantheon_conf['pantheon_environment'] . '.' . $pantheon_conf['pantheon_site_uuid'] . '.drush.in' . ':' . $pantheon_db['port'];
//     // Database
//     $pantheon_db_string .= '/' . $pantheon_db['database'] . '?new_link=true';
//   }
// }

/*
 +--------------------------------------------------------------------+
 | CiviCRM version 5                                                  |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2018                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*/

/**
 * CiviCRM Configuration File.
 */
global $civicrm_root, $civicrm_setting, $civicrm_paths;

/**
 * Content Management System (CMS) Host:
 *
 * CiviCRM can be hosted in either Drupal 6 or 7, Joomla or WordPress.
 *
 * Settings for Backdrop CMS:
 *      define( 'CIVICRM_UF'        , 'Backdrop');
 *
 * Settings for Drupal 7.x:
 *      define( 'CIVICRM_UF'        , 'Drupal');
 *
 * Settings for Drupal 6.x:
 *      define( 'CIVICRM_UF'        , 'Drupal6');
 *
 * Settings for Joomla 1.7.x - 2.5.x:
 *      define( 'CIVICRM_UF'        , 'Joomla');
 *
 * Settings for WordPress 3.3.x:
 *      define( 'CIVICRM_UF'        , 'WordPress');
 *
 * You may have issues with images in CiviCRM. If this is the case, be sure
 * to update the CiviCRM Resource URL field to your CiviCRM root directory
 * (Administer::System Settings::Resource URLs).
 */
if (!defined('CIVICRM_UF')) {
  if (getenv('CIVICRM_UF')) {
    define('CIVICRM_UF', getenv('CIVICRM_UF'));
  }
  else {
    define('CIVICRM_UF', 'Drupal8');
  }
}

/**
 * Content Management System (CMS) Datasource:
 *
 * Update this setting with your CMS (Drupal, Backdrop CMS, or Joomla) database username, password, server and DB name.
 * If any of these contain a single quote or backslash, escape those characters with a backslash: \' and \\, respectively.
 *
 * Datasource (DSN) format:
 *      define( 'CIVICRM_UF_DSN', 'mysql://cms_db_username:cms_db_password@db_server/cms_database?new_link=true');
 */
if (!defined('CIVICRM_UF_DSN') && CIVICRM_UF !== 'UnitTests') {
  define( 'CIVICRM_UF_DSN'           , $pantheon_db_string);
}

//

/**
 * CiviCRM Database Settings
 *
 * Database URL (CIVICRM_DSN) for CiviCRM Data:
 * Database URL format:
 *      define( 'CIVICRM_DSN', 'mysql://crm_db_username:crm_db_password@db_server/crm_database?new_link=true');
 *
 * Drupal and CiviCRM can share the same database, or can be installed into separate databases.
 * Backdrop CMS and CiviCRM can also share the same database, or can be installed into separate databases.
 *
 * EXAMPLE: Drupal/Backdrop and CiviCRM running in the same database...
 *      DB Name = cms, DB User = cms
 *      define( 'CIVICRM_DSN'         , 'mysql://cms:YOUR_PASSWORD@localhost/cms?new_link=true');
 *
 * EXAMPLE: Drupal/Backdrop and CiviCRM running in separate databases...
 *      CMS DB Name = cms, DB User = cms
 *      CiviCRM DB Name = civicrm, CiviCRM DB User = civicrm
 *      define( 'CIVICRM_DSN'         , 'mysql://civicrm:YOUR_PASSWORD@localhost/civicrm?new_link=true');
 *
 * If your username, password, server or DB name contain a single quote or backslash, escape those characters
 * with a backslash: \' and \\, respectively.
 *
 */
if (!defined('CIVICRM_DSN')) {
  if (CIVICRM_UF === 'UnitTests' && isset($GLOBALS['_CV']['TEST_DB_DSN'])) {
    define('CIVICRM_DSN', $GLOBALS['_CV']['TEST_DB_DSN']);
  }
  else {
    define('CIVICRM_DSN', $pantheon_db_string);
  }
}

/**
 * CiviCRM DSN Mode
 * Used to determine if you want CiviCRM to automatically change the dsn to mysqli if its avaliable.
 * Uncomment and edit below as necessary
 */
// define ('DB_DSN_MODE', 'auto');


/**
 * CiviCRM Logging Database
 *
 * Used to point to a different database to use for logging (if desired). If unset defaults to equal CIVICRM_DSN.
 * The CIVICRM_DSN user needs to have the rights to modify the below database schema and be able to write to it.
 */
if (!defined('CIVICRM_LOGGING_DSN')) {
  define('CIVICRM_LOGGING_DSN', CIVICRM_DSN);
}


global $civicrm_root;

// $civicrm_root = '/app/vendor/civicrm/civicrm-core';
$civicrm_root = $pantheon_root_dir . "/code/vendor/civicrm/civicrm-core";
if (!defined('CIVICRM_TEMPLATE_COMPILEDIR')) {
  define('CIVICRM_TEMPLATE_COMPILEDIR', $fileRoot . '/civicrm/templates_c/');
  // define( 'CIVICRM_TEMPLATE_COMPILEDIR', '/app/web/sites/default/files/civicrm/templates_c/');
}

/**
 * SMARTY Compile Check:
 *
 * This tells Smarty whether to check for recompiling or not. Recompiling
 * does not need to happen unless a template or config file is changed.
 * Typically you enable this during development, and disable for production.
 *
 * Related issue:
 * https://lab.civicrm.org/dev/core/issues/1073
 *
 */
//if (!defined('CIVICRM_TEMPLATE_COMPILE_CHECK')) {
//  define( 'CIVICRM_TEMPLATE_COMPILE_CHECK', FALSE);
//}


if (!defined('CIVICRM_UF_BASEURL')) {
  if (isset($_ENV['PANTHEON_ENVIRONMENT'])) {
    define( 'CIVICRM_UF_BASEURL'      , $pantheonDomain);
  } else {
    define( 'CIVICRM_UF_BASEURL'      , 'http://dev-civicea1.pantheonsite.io/');
  }
}

/**
 * Define any CiviCRM Settings Overrides per http://wiki.civicrm.org/confluence/display/CRMDOC/Override+CiviCRM+Settings
 *
 * Uncomment and edit the below as appropriate.
 */

 // Override the Temporary Files directory.
 $civicrm_setting['Directory Preferences']['uploadDir'] = $pantheon_conf->file_temporary_path;

 // Override the custom files upload directory.
 $civicrm_setting['Directory Preferences']['customFileUploadDir'] = $fileRoot . '/civicrm/custom';

 // Override the images directory.
 $civicrm_setting['Directory Preferences']['imageUploadDir'] = $fileRoot . '/civicrm/persist/contribute' ;

 // Override the custom templates directory.
 $civicrm_setting['Directory Preferences']['customTemplateDir'] = $fileRoot . '/civicrm/custom_tpl_47';

 // Override the Custom php path directory.
 $civicrm_setting['Directory Preferences']['customPHPPathDir'] = $fileRoot . '/civicrm/custom_php';

 // Override the extensions directory.
 $civicrm_setting['Directory Preferences']['extensionsDir'] =   $webRoot . '/extensions';

 // Override the resource url
 $civicrm_setting['URL Preferences']['userFrameworkResourceURL'] = '[cms.root]/libraries/civicrm';

 // Override the Image Upload URL (System Settings > Resource URLs)
 // $civicrm_setting['URL Preferences']['imageUploadURL'] = 'http://example.com/example-image-upload-url';

 // Override the Custom CiviCRM CSS URL
 // $civicrm_setting['URL Preferences']['customCSSURL'] = 'http://example.com/example-css-url' ;

 // Override the extensions resource URL
 $civicrm_setting['URL Preferences']['extensionsURL'] = $pantheonDomain . '/extensions';

 // Disable display of Community Messages on home dashboard
 // $civicrm_setting['CiviCRM Preferences']['communityMessagesUrl'] = false;

 // Disable automatic download / installation of extensions
 // $civicrm_setting['Extension Preferences']['ext_repo_url'] = false;

 // set triggers to be managed offline per CRM-18212
 // $civicrm_setting['CiviCRM Preferences']['logging_no_trigger_permission'] = 1;

 // Override the CMS root path defined by cmsRootPath.
 define('CIVICRM_CMSDIR', $webRoot);

 // Opt-out of announcements by the CiviCRM core team for releases, paid services, events, etc. Separate each preferred optout category with a comma:
 //   "offers": paid service offers
 //   "asks": requests for donations or membership signup/renewal to CiviCRM
 //   "releases": major release announcements
 //   "events": announcements of local/national upcoming events
 // $civicrm_setting['CiviCRM Preferences']['communityMessagesUrl'] = 'https://alert.civicrm.org/alert?prot=1&ver={ver}&uf={uf}&sid={sid}&lang={lang}&co={co}&optout=offers,asks';


/**
 * If you are using any CiviCRM script in the bin directory that
 * requires authentication, then you also need to set this key.
 * We recommend using a 16-32 bit alphanumeric/punctuation key.
 * More info at http://wiki.civicrm.org/confluence/display/CRMDOC/Command-line+Script+Configuration
 */
if (!defined('CIVICRM_SITE_KEY')) {
  define( 'CIVICRM_SITE_KEY', '94a906b101ec1e9516519b209e8096a0');
}

/**
 * Enable this constant, if you want to send your email through the smarty
 * templating engine(allows you to do conditional and more complex logic)
 *
 */
if (!defined('CIVICRM_MAIL_SMARTY')) {
  define( 'CIVICRM_MAIL_SMARTY', 0 );
}

/**
 * This setting logs all emails to a file. Useful for debugging any mail (or civimail) issues.
 * Enabling this setting will not send any email, ensure this is commented out in production
 * The CIVICRM_MAIL_LOG is a debug option which disables MTA (mail transport agent) interaction.
 * You must disable CIVICRM_MAIL_LOG before CiviCRM will talk to your MTA.
 */
// if (!defined('CIVICRM_MAIL_LOG')) {
// define( 'CIVICRM_MAIL_LOG', '/app/web/sites/default/files/civicrm/templates_c//mail.log');
// }

/**
 * This setting will only work if CIVICRM_MAIL_LOG is defined.  Mail will be logged and then sent.
 */
//if (!defined('CIVICRM_MAIL_LOG_AND_SEND')) {
//  define( 'CIVICRM_MAIL_LOG_AND_SEND', 1);
//}


if (!defined('CIVICRM_DOMAIN_ID')) {
  define( 'CIVICRM_DOMAIN_ID', 1);
}

/**
 * Setting to define the environment in which this CiviCRM instance is running.
 * Note the setting here must be value from the option group 'Environment',
 * (see Administration > System Settings > Option Groups, Options beside Environment)
 * which by default has three option values: 'Production', 'Staging', 'Development'.
 * NB: defining a value for environment here prevents it from being set
 * via the browser.
 */
// $civicrm_setting['domain']['environment'] = 'Production';

/**
 * Settings to enable external caching using a cache server.  This is an
 * advanced feature, and you should read and understand the documentation
 * before you turn it on. We cannot store these settings in the DB since the
 * config could potentially also be cached and we need to avoid an infinite
 * recursion scenario.
 *
 * @see http://civicrm.org/node/126
 */

/**
 * If you have a cache server configured and want CiviCRM to make use of it,
 * set the following constant.  You should only set this once you have your cache
 * server up and working, because CiviCRM will not start up if your server is
 * unavailable on the host and port that you specify. By default CiviCRM will use
 * an in-memory array cache
 *
 * To use the php extension memcache  use a value of 'Memcache'
 * To use the php extension memcached use a value of 'Memcached'
 * To use the php extension apc       use a value of 'APCcache'
 * To use the php extension redis     use a value of 'Redis'
 * To not use any caching (not recommended), use a value of 'NoCache'
 *
 */
if (!defined('CIVICRM_DB_CACHE_CLASS')) {
  if (isset($_ENV['PANTHEON_ENVIRONMENT'])) {
    define('CIVICRM_DB_CACHE_CLASS', 'Redis');
  } else {
  define('CIVICRM_DB_CACHE_CLASS', 'ArrayCache');
  }
}

/**
 * Change this to the IP address of your cache server if it is not on the
 * same machine (Unix).
 */
if (!defined('CIVICRM_DB_CACHE_HOST')) {
  if (CIVICRM_DB_CACHE_CLASS === 'Redis') {
    define('CIVICRM_DB_CACHE_HOST', $redHost);
  } else {
    define('CIVICRM_DB_CACHE_HOST', '');
  }
}

/**
 * Change this if you are not using the standard port for your cache server.
 *
 * The standard port for Memcache & APCCache is 11211. For Redis it is 6379.
 */
if (!defined('CIVICRM_DB_CACHE_PORT')) {
  if (CIVICRM_DB_CACHE_CLASS === 'Redis') {
    define('CIVICRM_DB_CACHE_PORT', $redPort);
  } else {
    define('CIVICRM_DB_CACHE_PORT', 11211);
  }
}

/**
 * Change this if your cache server requires a password (currently only works
 * with Redis)
 */
if (!defined('CIVICRM_DB_CACHE_PASSWORD')) {
  if (CIVICRM_DB_CACHE_CLASS === 'Redis') {
    define('CIVICRM_DB_CACHE_PASSWORD', $redPass );
  } else {
    define('CIVICRM_DB_CACHE_PASSWORD', '' );
  }
}

/**
 * Items in cache will expire after the number of seconds specified here.
 * Default value is 3600 (i.e., after an hour)
 */
if (!defined('CIVICRM_DB_CACHE_TIMEOUT')) {
  define('CIVICRM_DB_CACHE_TIMEOUT', 3600 );
}

/**
 * If you are sharing the same cache instance with more than one CiviCRM
 * database, you will need to set a different value for the following argument
 * so that each copy of CiviCRM will not interfere with other copies.  If you only
 * have one copy of CiviCRM, you may leave this set to ''.  A good value for
 * this if you have two servers might be 'server1_' for the first server, and
 * 'server2_' for the second server.
 */
if (!defined('CIVICRM_DB_CACHE_PREFIX')) {
  define('CIVICRM_DB_CACHE_PREFIX', '');
}

/**
 * The cache system traditionally allowed a wide range of cache-keys, but some
 * cache-keys are prohibited by PSR-16.
 */
if (!defined('CIVICRM_PSR16_STRICT')) {
  define('CIVICRM_PSR16_STRICT', FALSE);
}

/**
 * If you have multilingual site and you are using the "inherit CMS language"
 * configuration option, but wish to, for example, use fr_CA instead of the
 * default fr_FR (for French), set one or more of the constants below to an
 * appropriate regional value.
 */
// define('CIVICRM_LANGUAGE_MAPPING_FR', 'fr_CA');
// define('CIVICRM_LANGUAGE_MAPPING_EN', 'en_CA');
// define('CIVICRM_LANGUAGE_MAPPING_ES', 'es_MX');
// define('CIVICRM_LANGUAGE_MAPPING_PT', 'pt_BR');
// define('CIVICRM_LANGUAGE_MAPPING_ZH', 'zh_TW');

/**
 * Native gettext improves performance of localized CiviCRM installations
 * significantly. However, your host must enable the locale (language).
 * On most GNU/Linux, Unix or MacOSX systems, you may view them with
 * the command line by typing: "locale -a".
 *
 * On Debian or Ubuntu, you may reconfigure locales with:
 * # dpkg-reconfigure locales
 *
 * For more information:
 * http://wiki.civicrm.org/confluence/x/YABFBQ
 */
// if (!defined('CIVICRM_GETTEXT_NATIVE')) {
// define('CIVICRM_GETTEXT_NATIVE', 1);
// }

/**
 * Define how many times to retry a transaction when the DB hits a deadlock
 * (ie. the database is locked by another transaction). This is an
 * advanced setting intended for high-traffic databases & experienced developers/ admins.
 */
define('CIVICRM_DEADLOCK_RETRIES', 3);

/**
 * Enable support for multiple locks.
 *
 * This is a transitional setting. When enabled sites with mysql 5.7.5+ or equivalent
 * MariaDB can improve their DB conflict management.
 *
 * There is no known or expected downside or enabling this (and definite upside).
 * The setting only exists to allow sites to manage change in their environment
 * conservatively for the first 3 months.
 *
 * See https://github.com/civicrm/civicrm-core/pull/13854
 */
 // define('CIVICRM_SUPPORT_MULTIPLE_LOCKS', TRUE);

/**
 * Configure MySQL to throw more errors when encountering unusual SQL expressions.
 *
 * If undefined, the value is determined automatically. For CiviCRM tarballs, it defaults
 * to FALSE; for SVN checkouts, it defaults to TRUE.
 */
// if (!defined('CIVICRM_MYSQL_STRICT')) {
// define('CIVICRM_MYSQL_STRICT', TRUE );
// }

/**
 * Specify whether the CRM_Core_BAO_Cache should use the legacy
 * direct-to-SQL-mode or the interim PSR-16 adapter.
 */
// define('CIVICRM_BAO_CACHE_ADAPTER', 'CRM_Core_BAO_Cache_Psr16');

if (CIVICRM_UF === 'UnitTests') {
  if (!defined('CIVICRM_CONTAINER_CACHE')) define('CIVICRM_CONTAINER_CACHE', 'auto');
  if (!defined('CIVICRM_MYSQL_STRICT')) define('CIVICRM_MYSQL_STRICT', true);
}

/**
 *
 * Do not change anything below this line. Keep as is
 *
 */

$include_path = '.'           . PATH_SEPARATOR .
                $civicrm_root . PATH_SEPARATOR .
                $civicrm_root . DIRECTORY_SEPARATOR . 'packages' . PATH_SEPARATOR .
                get_include_path( );
if ( set_include_path( $include_path ) === false ) {
   echo "Could not set the include path<p>";
   exit( );
}

if (!defined('CIVICRM_CLEANURL')) {
  if ( function_exists('variable_get') && variable_get('clean_url', '0') != '0') {
    define('CIVICRM_CLEANURL', 1 );
  }
  elseif ( function_exists('config_get') && config_get('system.core', 'clean_url') != 0) {
    define('CIVICRM_CLEANURL', 1 );
  }
  else {
    define('CIVICRM_CLEANURL', 0);
  }
}

// force PHP to auto-detect Mac line endings
ini_set('auto_detect_line_endings', '1');

// make sure the memory_limit is at least 64 MB
$memLimitString = trim(ini_get('memory_limit'));
$memLimitUnit   = strtolower(substr($memLimitString, -1));
$memLimit       = (int) $memLimitString;
switch ($memLimitUnit) {
    case 'g': $memLimit *= 1024;
    case 'm': $memLimit *= 1024;
    case 'k': $memLimit *= 1024;
}
if ($memLimit >= 0 and $memLimit < 134217728) {
    ini_set('memory_limit', '128M');
}

require_once 'CRM/Core/ClassLoader.php';
CRM_Core_ClassLoader::singleton()->register();
