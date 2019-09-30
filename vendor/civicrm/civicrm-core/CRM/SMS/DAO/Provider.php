<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2019
 *
 * Generated from xml/schema/CRM/SMS/Provider.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:a24ea48b652eba8827a5275a127df61e)
 */

/**
 * Database access object for the Provider entity.
 */
class CRM_SMS_DAO_Provider extends CRM_Core_DAO {

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_sms_provider';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  public static $_log = FALSE;

  /**
   * SMS Provider ID
   *
   * @var int unsigned
   */
  public $id;

  /**
   * Provider internal name points to option_value of option_group sms_provider_name
   *
   * @var string
   */
  public $name;

  /**
   * Provider name visible to user
   *
   * @var string
   */
  public $title;

  /**
   * @var string
   */
  public $username;

  /**
   * @var string
   */
  public $password;

  /**
   * points to value in civicrm_option_value for group sms_api_type
   *
   * @var int unsigned
   */
  public $api_type;

  /**
   * @var string
   */
  public $api_url;

  /**
   * the api params in xml, http or smtp format
   *
   * @var text
   */
  public $api_params;

  /**
   * @var boolean
   */
  public $is_default;

  /**
   * @var boolean
   */
  public $is_active;

  /**
   * Which Domain is this sms provider for
   *
   * @var int unsigned
   */
  public $domain_id;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_sms_provider';
    parent::__construct();
  }

  /**
   * Returns foreign keys and entity references.
   *
   * @return array
   *   [CRM_Core_Reference_Interface]
   */
  public static function getReferenceColumns() {
    if (!isset(Civi::$statics[__CLASS__]['links'])) {
      Civi::$statics[__CLASS__]['links'] = static::createReferenceColumns(__CLASS__);
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'domain_id', 'civicrm_domain', 'id');
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'links_callback', Civi::$statics[__CLASS__]['links']);
    }
    return Civi::$statics[__CLASS__]['links'];
  }

  /**
   * Returns all the column names of this table
   *
   * @return array
   */
  public static function &fields() {
    if (!isset(Civi::$statics[__CLASS__]['fields'])) {
      Civi::$statics[__CLASS__]['fields'] = [
        'id' => [
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('SMS Provider ID'),
          'description' => ts('SMS Provider ID'),
          'required' => TRUE,
          'where' => 'civicrm_sms_provider.id',
          'table_name' => 'civicrm_sms_provider',
          'entity' => 'Provider',
          'bao' => 'CRM_SMS_BAO_Provider',
          'localizable' => 0,
        ],
        'name' => [
          'name' => 'name',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('SMS Provider Name'),
          'description' => ts('Provider internal name points to option_value of option_group sms_provider_name'),
          'maxlength' => 64,
          'size' => CRM_Utils_Type::BIG,
          'where' => 'civicrm_sms_provider.name',
          'table_name' => 'civicrm_sms_provider',
          'entity' => 'Provider',
          'bao' => 'CRM_SMS_BAO_Provider',
          'localizable' => 0,
        ],
        'title' => [
          'name' => 'title',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('SMS Provider Title'),
          'description' => ts('Provider name visible to user'),
          'maxlength' => 64,
          'size' => CRM_Utils_Type::BIG,
          'where' => 'civicrm_sms_provider.title',
          'table_name' => 'civicrm_sms_provider',
          'entity' => 'Provider',
          'bao' => 'CRM_SMS_BAO_Provider',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
        ],
        'username' => [
          'name' => 'username',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('SMS Provider Username'),
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'where' => 'civicrm_sms_provider.username',
          'table_name' => 'civicrm_sms_provider',
          'entity' => 'Provider',
          'bao' => 'CRM_SMS_BAO_Provider',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
        ],
        'password' => [
          'name' => 'password',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('SMS Provider Password'),
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'where' => 'civicrm_sms_provider.password',
          'table_name' => 'civicrm_sms_provider',
          'entity' => 'Provider',
          'bao' => 'CRM_SMS_BAO_Provider',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
        ],
        'api_type' => [
          'name' => 'api_type',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('SMS Provider API'),
          'description' => ts('points to value in civicrm_option_value for group sms_api_type'),
          'required' => TRUE,
          'where' => 'civicrm_sms_provider.api_type',
          'table_name' => 'civicrm_sms_provider',
          'entity' => 'Provider',
          'bao' => 'CRM_SMS_BAO_Provider',
          'localizable' => 0,
          'html' => [
            'type' => 'Select',
          ],
        ],
        'api_url' => [
          'name' => 'api_url',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('SMS Provider API URL'),
          'maxlength' => 128,
          'size' => CRM_Utils_Type::HUGE,
          'where' => 'civicrm_sms_provider.api_url',
          'table_name' => 'civicrm_sms_provider',
          'entity' => 'Provider',
          'bao' => 'CRM_SMS_BAO_Provider',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
        ],
        'api_params' => [
          'name' => 'api_params',
          'type' => CRM_Utils_Type::T_TEXT,
          'title' => ts('SMS Provider API Params'),
          'description' => ts('the api params in xml, http or smtp format'),
          'where' => 'civicrm_sms_provider.api_params',
          'table_name' => 'civicrm_sms_provider',
          'entity' => 'Provider',
          'bao' => 'CRM_SMS_BAO_Provider',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
        ],
        'is_default' => [
          'name' => 'is_default',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => ts('SMS Provider is Default?'),
          'where' => 'civicrm_sms_provider.is_default',
          'default' => '0',
          'table_name' => 'civicrm_sms_provider',
          'entity' => 'Provider',
          'bao' => 'CRM_SMS_BAO_Provider',
          'localizable' => 0,
          'html' => [
            'type' => 'CheckBox',
          ],
        ],
        'is_active' => [
          'name' => 'is_active',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => ts('SMS Provider is Active?'),
          'where' => 'civicrm_sms_provider.is_active',
          'default' => '0',
          'table_name' => 'civicrm_sms_provider',
          'entity' => 'Provider',
          'bao' => 'CRM_SMS_BAO_Provider',
          'localizable' => 0,
          'html' => [
            'type' => 'CheckBox',
          ],
        ],
        'domain_id' => [
          'name' => 'domain_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('SMS Domain'),
          'description' => ts('Which Domain is this sms provider for'),
          'where' => 'civicrm_sms_provider.domain_id',
          'table_name' => 'civicrm_sms_provider',
          'entity' => 'Provider',
          'bao' => 'CRM_SMS_BAO_Provider',
          'localizable' => 0,
          'FKClassName' => 'CRM_Core_DAO_Domain',
          'pseudoconstant' => [
            'table' => 'civicrm_domain',
            'keyColumn' => 'id',
            'labelColumn' => 'name',
          ]
        ],
      ];
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'fields_callback', Civi::$statics[__CLASS__]['fields']);
    }
    return Civi::$statics[__CLASS__]['fields'];
  }

  /**
   * Return a mapping from field-name to the corresponding key (as used in fields()).
   *
   * @return array
   *   Array(string $name => string $uniqueName).
   */
  public static function &fieldKeys() {
    if (!isset(Civi::$statics[__CLASS__]['fieldKeys'])) {
      Civi::$statics[__CLASS__]['fieldKeys'] = array_flip(CRM_Utils_Array::collect('name', self::fields()));
    }
    return Civi::$statics[__CLASS__]['fieldKeys'];
  }

  /**
   * Returns the names of this table
   *
   * @return string
   */
  public static function getTableName() {
    return self::$_tableName;
  }

  /**
   * Returns if this table needs to be logged
   *
   * @return bool
   */
  public function getLog() {
    return self::$_log;
  }

  /**
   * Returns the list of fields that can be imported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &import($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'sms_provider', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of fields that can be exported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &export($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'sms_provider', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of indices
   *
   * @param bool $localize
   *
   * @return array
   */
  public static function indices($localize = TRUE) {
    $indices = [];
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }

}
