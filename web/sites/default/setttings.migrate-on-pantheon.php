<?php
/**
 * Get secrets from secrets file.
 *
 * @param array $requiredKeys  List of keys in secrets file that must exist.
 */


  $databases['upgrade']['default'] = array (
  'database' => 'ceaD7',
  'username' => 'admin',
  'password' => 'Thin1buoy2',
  'host' => 'ceacivi-instance-1.c5gdwtf3odmd.us-east-1.rds.amazonaws.com',
  'port' => 3306,
  'driver' => 'mysql',
  'prefix' => '',
  );

  $databases['migrate']['default'] = $databases['upgrade']['default'];
  $databases['drupal_7']['default'] = $databases['upgrade']['default'];
