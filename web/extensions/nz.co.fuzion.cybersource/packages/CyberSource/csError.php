<?php

/* csError.php
 * This file is just a function for CyberSource error code lookups
 */
function csError($nvp) {
// define CyberSource error codes as listed in:
//  http://apps.cybersource.com/library/documentation/sbc/SOP_UG/SOP_UG.pdf
$cs_codes = array(
  '102' => array(
    'csError' => 'Your transaction has been declined',
    'csMessage' => 'Please check your credit card number and expiry date were entered correctly.',
  ),
  '150' => array(
    'csError' => 'Error: General System Failure.',
    'csMessage' => 'An error occured. Please try again later.',
  ),
  '151' => array(
    'csError' => 'Error: The request was received, but a server time-out occurred. This error does not include time-outs between the client and the server.',
    'csMessage' => 'An error occured. Please contact the site administrator to verify your order.',
  ),
  '152' => array(
    'csError' => 'Error: The request was received, but a service did not finish running in time.',
    'csMessage' => 'An error occured. Please contact the site administrator to verify your order.',
  ),
  '200' => array(
    'csError' => 'The authorization request was approved by the issuing bank but declined by CyberSource because it did not pass the Address Verification Service (AVS) check.',
    'csMessage' => 'Your address did not pass verification.  Please check your address information.',
  ),
  '201' => array(
    'csError' => 'The issuing bank has questions about the request. You cannot receive an authorization code in the API reply, but you may receive one verbally by calling the processor.',
    'csMessage' => 'Your bank has questions regarding this order. Please call your bank to authorize this transaction.',
  ),
  '202' => array(
    'csError' => 'Expired card.',
    'csMessage' => 'The card you submitted has expired. Please use a valid card.',
  ),
  '203' => array(
    'csError' => 'The card was declined. No other information provided by the issuing bank.',
    'csMessage' => 'The card you submitted was declined. Please use a valid card.',
  ),
  '204' => array(
    'csError' => 'Insufficient funds in the account.',
    'csMessage' => 'The card you submitted has insufficient funds. Please use another card.',
  ),
  '205' => array(
    'csError' => 'The card was stolen or lost.',
    'csMessage' => 'The card you submitted was reported lost or stolen. Please use a valid card.',
  ),
  '207' => array(
    'csError' => 'The issuing bank was unavailable.',
    'csMessage' => 'The issuing bank was unavailable. Please try again later.',
  ),
  '208' => array(
    'csError' => 'The card is inactive or not authorized for card-not-present transactions.',
    'csMessage' => 'The card you submitted is inactive. Please use a valid card.',
  ),
  '210' => array(
    'csError' => 'The credit limit for the card has been reached.',
    'csMessage' => 'The card you submitted has reached its limit. Please use another card.',
  ),
  '211' => array(
    'csError' => 'The card verification number is invalid.',
    'csMessage' => 'The card verification number is invalid. Please check the verification number and try again or use another card.',
  ),
  '220' => array(
    'csError' => 'The processor declined the request based on a general issue with the customer’s account.',
    'csMessage' => 'The card you submitted was declined. Please use another card.',
  ),
  '221' => array(
    'csError' => 'The customer matched an entry on the processor’s negative file.',
    'csMessage' => 'The card you submitted was declined. Please use another card.',
  ),
  '222' => array(
    'csError' => 'The customer’s bank account is frozen.',
    'csMessage' => 'The card you submitted was declined. Please use another card.',
  ),
  '230' => array(
    'csError' => 'The authorization request was approved by the issuing bank but declined by CyberSource because it did not pass the card verification number check.',
    'csMessage' => 'The card verification number is invalid. Please check the verification number and try again or use another card.',
  ),
  '231' => array(
    'csError' => 'The account number is invalid.',
    'csMessage' => 'The card you submitted was declined. Please use another card.',
  ),
  '232' => array(
    'csError' => 'The card type is not accepted by the payment processor.',
    'csMessage' => 'The card you submitted was declined. Please use another card.',
  ),
  '233' => array(
    'csError' => 'The processor declined the request based on an issue with the request itself.',
    'csMessage' => 'The card you submitted was declined. Please use another card.',
  ),
  '234' => array(
    'csError' => 'There is a problem with your CyberSource merchant configuration.',
    'csMessage' => 'An error occured. Please contact the site administrator.',
  ),
  '236' => array(
    'csError' => 'A processor failure occurred.',
    'csMessage' => 'An error occured. Please contact the site administrator.',
  ),
  '240' => array(
    'csError' => 'The card type is invalid or does not correlate with the credit card number.',
    'csMessage' => 'The card you submitted was declined. Please use another card.',
  ),
  '250' => array(
    'csError' => 'The request was received, but a time-out occurred with the payment processor.',
    'csMessage' => 'An error occured. Please contact the site administrator.',
  ),
  '475' => array(
    'csError' => 'The customer is enrolled in payer authentication.',
    'csMessage' => 'An error occured. Please contact the site administrator.',
  ),
  '476' => array(
    'csError' => 'The customer cannot be authenticated.',
    'csMessage' => 'An error occured. Please contact the site administrator.',
  ),
  '520' => array(
    'csError' => 'The authorization request was approved by the issuing bank but declined by CyberSource based on your Smart Authorization settings.',
    'csMessage' => 'The card you submitted was declined. Please verify your card and billing information and try again or use another card.',
  ),
);

// required fields that may generate errors if missing
$cs_fields = array(
  'billTo_city' => 'billing address city',
  'billTo_country' => 'billing address country',
  'billTo_email' => 'customer\'s email address',
  'billTo_firstName' => 'customer\'s first name',
  'billTo_lastName' => 'customer\'s last name',
  'billTo_postalCode' => 'billing address zip code or postal code',
  'billTo_state' => 'billing address state or province',
  'billTo_street1' => 'billing street address',
  'amount' => 'sale amount',
  'card_accountNumber' => 'credit card number',
  'card_cardType' => 'credit card type',
  'card_cvNumber' => 'card verification number (CVV2, CVC or CID)',
  'card_expirationMonth' => 'card expiration month',
  'card_expirationYear' => 'card expiration year',
);  

// $nvp is the array of name-value pairs sent in the CyberSource response

  // no error, exit here
  if ($nvp['decision'] == 'ACCEPT') return false; 
  // if we have an existing  code, return the messages
  if (is_array($cs_codes[$nvp['ccAuthReply_reasonCode']])) {
    return $cs_codes[$nvp['ccAuthReply_reasonCode']];
  }

  // check if we have missing fields
  foreach ($nvp as $field => $value) {
    if (strpos($field, 'MissingField') !== false && $cs_fields[$field]) {
      return array(
        'csError' => 'Transaction is missing information: ' . $cs_fields[$field] . ' (' . $field . ')',
        'csMessage' => 'Your order information was incomplete. Please verify your ' . $cs_fields[$field],
      );
    }
  }

  return array(
    'csError' => 'An unknown error occured',
    'csMessage' => 'An unknown error occured. Please contact the site administrator to complete your purchase.'
  );

}