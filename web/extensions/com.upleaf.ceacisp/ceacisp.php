<?php


function ceacisp_civicrm_config(&$config) {
  static $configured = FALSE;
  if ($configured) return;
  $configured = TRUE;

  $template =& CRM_Core_Smarty::singleton();

  $extRoot = dirname( __FILE__ ) . DIRECTORY_SEPARATOR;
  $extDir = $extRoot . 'templates';

  if ( is_array( $template->template_dir ) ) {
    array_unshift( $template->template_dir, $extDir );
  } else {
    $template->template_dir = array( $extDir, $template->template_dir );
  }

  $include_path = $extRoot . PATH_SEPARATOR . get_include_path( );
  set_include_path( $include_path );
}

/**
 * Implementation of hook_civicrm_validate
 *
 */

function ceacisp_civicrm_validateForm($formName, &$fields, &$files, &$form, &$errors) {
  if ($formName == 'CRM_Contribute_Form_Contribution_Main') {

    // get the qfKey from the form variables
    $values = $form->getVar('_submitValues');
    if (!empty($values['custom_216']) && !empty($values['custom_217'])) {
      $deliveryDate = strtotime($values['custom_216']);
      $returnDate  = strtotime($values['custom_217']);
      $dateDiff = round(abs($returnDate - $deliveryDate) / 86400 );
      if ($deliveryDate > $returnDate) {
        $errors['custom_217'] = 'Return date must be greater than delivery date.';
      } else if (!empty($dateDiff) && $dateDiff > 14) {
        $errors['custom_217'] = 'Return date should be less than 10 working days from delivery date';
      } 
    }
  }

}


/**
 * Implementation of hook_civicrm_pageRun
 */
function ceacisp_civicrm_pageRun(&$page) {
  // Display the helptab: include the files in the head and body
  CRM_Utils_Common::addResource();
}

/**
 * Implementation of hook_civicrm_pageRun
 */
function ceacisp_civicrm_buildForm(&$form) {
  // Extract the page Menu
  // Check if is_public, return
  CRM_Utils_Common::addResource();
}
