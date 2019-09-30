<?php



class CRM_Utils_Common {
  private static $_singleton = NULL;

  private static $_resource_loaded = false;

  static function addResource() {
    if (self::$_resource_loaded || !self::is_public_page()) {
        return;
    }

    CRM_Core_Resources::singleton()->addStyleFile('com.upleaf.ceacisp', 'css/extra.css');
    self::$_resource_loaded = true;
  }

  static function is_public_page() {
    // Get the menu items.
    $args = explode('?', $_GET['q']);
    $path = $args[0];
    // Get the menu for above URL.
    $item = CRM_Core_Menu::get($path);

    // Check for public pages
    // If public page and civicrm public theme is set, apply civicrm public theme
    if (CRM_Utils_Array::value('is_public', $item)) {
      return true;
    }
    return false;
  }

}

