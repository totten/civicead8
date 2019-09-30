<?php

/**
 * CyberSource payment gateway integration
 * @package CRM
 * @Source code and original idea taken from Jason Yee. Modified, upgraded and fixed by Victor Forsythe
 * (support@upleaf.com) for newer versions of civicrm.
 * www.upleaf.com
 */

require_once 'CRM/Core/Payment.php';
require_once 'packages/CyberSource/HOP.php';
require_once 'packages/CyberSource/csError.php';

class nz_co_fuzion_cybersource extends CRM_Core_Payment {
  static protected $_mode = null;
  static protected $_params = array();
  static private $_singleton = null;
  /**
   * @param string $mode the mode of operation: live or test
   * @return void
   */
  function __construct($mode, &$paymentProcessor) {
    $this->_mode = $mode;
    $this->_paymentProcessor = $paymentProcessor;
    $this->_processorName = ts('CyberSource');
    $this->_currency = 'usd';
    $this->_transactionType = 'sale';
  }

  /**
   * @param  array $params assoc array of input parameters for this transaction
   * @return array the result in a nice formatted array (or an error object)
   */
 static function &singleton( $mode, &$paymentProcessor ) {
        $processorName = $paymentProcessor['name'];
        if (self::$_singleton[$processorName] === null ) {
            self::$_singleton[$processorName] = new nz_co_fuzion_cybersource( $mode, $paymentProcessor );
        }
        return self::$_singleton[$processorName];
    }

  function doDirectPayment(&$params) {
    // store the submitted data
    foreach ($params as $field => $value) {
      $this->_setParam($field, $value);
    }

    // setup the the field mapping for what we need to submit
    $postFields = array();
    $cybersourceFields = $this->_getCyberSourceFields();
    // Set up our call for hook_civicrm_paymentProcessor,
    CRM_Utils_Hook::alterPaymentProcessorParams($this, $params, $cybersourceFields);

    // map the data to post fields
    foreach ($cybersourceFields as $field => $value) {
      $postFields[] = $field . '=' . urlencode($value);
    }

    // run the cURL
    $submit = curl_init($this->_paymentProcessor['url_site']);
    curl_setopt($submit, CURLOPT_VERBOSE, 0);
    curl_setopt($submit, CURLOPT_POST, true);
    curl_setopt($submit, CURLOPT_POSTFIELDS, implode('&', $postFields));
    curl_setopt($submit, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($submit, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($submit, CURLOPT_NOPROGRESS, 1);
    curl_setopt($submit, CURLOPT_FOLLOWLOCATION, 0);
    $response = curl_exec($submit);

    // handle the response
    if (!$response) return self::error(curl_errno($submit), curl_error($submit));
    // close cURL
    curl_close($submit);
    // response parsing code taken from ubercart's cybersource code
    if (preg_match_all('`name=".+" value=".+"`', $response, $pairs) > 0) {
      for ($i = 0; $i < count($pairs[0]); $i++) {
        list($name, $value) = explode('" value="', substr($pairs[0][$i], 6, strlen($pairs[0][$i]) - 7));
        $nvp[$name] = $value;
      }

      if ($nvp['decision'] == 'ACCEPT') {
        $params['trxn_id'] = $nvp['requestID'];//'trxn_id' is varchar(255) field. returned value is length 37
        $params['trxn_result_code'] = $nvp['reconciliationID'] ;
        return $params;
      }
      else {
        // did this because we may want to pass the original CyberSource error message to the logs/admin
        $messages = csError($nvp);
        return self::error('CS' . $nvp['ccAuthReply_reasonCode'], $messages['csMessage']);
      }
    } // end sorting name value pairs
    // no response given
    else {
      return self::error('No Response', 'CyberSource failed to send a response.');
    }

  } // end doDirectPayment

  function _getCyberSourceFields() {
    $fields = array();
    // contact info
    $fields['billTo_firstName'] = $this->_getParam('billing_first_name');
    $fields['billTo_lastName'] = $this->_getParam('billing_last_name');
    $fields['billTo_street1'] = $this->_getParam('street_address');
    $fields['billTo_city'] = $this->_getParam('city');
    $fields['billTo_state'] = $this->_getParam('state_province');
    $fields['billTo_postalCode'] = $this->_getParam('postal_code');
    $fields['billTo_country'] = $this->_getParam('country');
    $fields['billTo_email'] = $this->_getParam('email');

    // card info
    if ($this->_getParam('credit_card_type') == 'Visa') { $fields['card_cardType'] = '001'; }
    elseif ($this->_getParam('credit_card_type') == 'MasterCard') { $fields['card_cardType'] = '002'; }
    elseif ($this->_getParam('credit_card_type') == 'Amex') { $fields['card_cardType'] = '003'; }
    elseif ($this->_getParam('credit_card_type') == 'Discover') { $fields['card_cardType'] = '004'; }

    $fields['card_accountNumber'] = $this->_getParam('credit_card_number');
    $fields['card_cvNumber'] = $this->_getParam('cvv2');
    $fields['card_expirationMonth'] = str_pad($this->_getParam('month'), 2, '0', STR_PAD_LEFT);
    $fields['card_expirationYear'] = $this->_getParam('year');

    // Prep CyberSource info
    // this is copied from the HOP.php InsertSignature3()
    $amount = ($this->_getParam('amount'))? $this->_getParam('amount') : '0.00';
    $orderPage_transactionType = $this->_transactionType;
    $currency = $this->_currency;
    $merchantID = $this->getMerchantID();
    $timestamp = cybersouce_hop_getmicrotime();
    $data = $merchantID . $amount . $currency . $timestamp . $orderPage_transactionType;
    $pub =  $this->getSharedSecret();
    $serialNumber = $this->getSerialNumber();
    $pub_digest = cybersource_hop_hopHash($data, $pub);

    // set CyberSource info
    $fields['orderPage_transactionType'] = $this->_transactionType;
    $fields['amount'] = $amount;
    $fields['currency'] = $this->_currency;
    $fields['orderPage_timestamp'] = $timestamp;
    $fields['merchantID'] = $merchantID;
    $fields['orderPage_signaturePublic'] = $pub_digest;
    $fields['orderPage_version'] = '4';
    $fields['orderPage_serialNumber'] = $serialNumber;

    // other transaction info
    $fields['orderNumber'] = $this->_getParam('invoiceID');
    return $fields;
  } // end _getCyberSourceFields

  /**
   * Get the value of a field if set
   *
   * @param string $field the field
   * @return mixed value of the field, or empty string if the field is
   * not set
   */
  function _getParam($field) {
    return CRM_Utils_Array::value($field, $this->_params, '');
  }

  /**
   * Set a field to the specified value.  Value must be a scalar (int,
   * float, string, or boolean)
   *
   * @param string $field
   * @param mixed $value
   * @return bool false if value is not a scalar, true if successful
   */
  function _setParam($field, $value) {
    if (!is_scalar($value)) {
      return false;
    } else {
      $this->_params[$field] = $value;
      return true;
    }
  }

  /**
   * need this because it's declared as abstract in parent class
   */
  function checkConfig() {
    $errorMsg = array();
    if ( empty( $this->_paymentProcessor['user_name'] ) ) {
      $errorMsg[] = ' ' . ts( 'ssl_merchant_id is not set for this payment processor' );
    }

    if ( empty( $this->_paymentProcessor['url_site'] ) ) {
      $errorMsg[] = ' ' . ts( 'URL is not set for this payment processor' );
    }

    if ( ! empty( $errorMsg ) ) {
      return implode( '<p>', $errorMsg );
      } else {
        return null;
      }
  }

  function &error($errorCode = null, $errorMessage = null) {
    $e = &CRM_Core_Error::singleton();
    if ($errorCode) {
      $e->push($errorCode, 0, null, $errorMessage);
    } else {
      $e->push(9001, 0, null, 'Unknown System Error.');
    }
    return $e;
  }

  function getMerchantID(){
    return $this->_paymentProcessor['user_name'];
  }

  function getSerialNumber(){
    return $this->_paymentProcessor['signature_label'];
  }

  function getSharedSecret(){
    return $this->_paymentProcessor['password'];
  }
} // end class