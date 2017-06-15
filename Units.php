<?PHP
######################################################
#                                                    #
#                Forms To Go 4.5.4                   #
#             http://www.bebosoft.com/               #
#                                                    #
######################################################

######################################################
#                                                    #
#                  UNREGISTERED COPY                 #
#              Forms To Go is shareware              #
#            Please register the software            #
#              http://www.bebosoft.com/              #
#                                                    #
######################################################




define('kOptional', true);
define('kMandatory', false);

define('kStringRangeFrom', 1);
define('kStringRangeTo', 2);
define('kStringRangeBetween', 3);
        
define('kYes', 'yes');
define('kNo', 'no');




error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('track_errors', true);

function DoStripSlashes($fieldValue)  { 
// temporary fix for PHP6 compatibility - magic quotes deprecated in PHP6
 if ( function_exists( 'get_magic_quotes_gpc' ) && get_magic_quotes_gpc() ) { 
  if (is_array($fieldValue) ) { 
   return array_map('DoStripSlashes', $fieldValue); 
  } else { 
   return trim(stripslashes($fieldValue)); 
  } 
 } else { 
  return $fieldValue; 
 } 
}

function RemoveEndOfLine(&$item, $key) {
// Temporary fix for the FILE_IGNORE_NEW_LINES flag in PHP 5.0.0
 $item = str_replace( chr( 13 ), "", $item );
 $item = str_replace( chr( 10 ), "", $item );
}

function FilterCChars($theString) {
 return preg_replace('/[\x00-\x1F]/', '', $theString);
}

function CheckString($value, $low, $high, $mode, $limitAlpha, $limitNumbers, $limitEmptySpaces, $limitExtraChars, $optional) {

 $regEx = '';

 if ($limitAlpha == kYes) {
  $regExp = 'A-Za-z';
 }
 
 if ($limitNumbers == kYes) {
  $regExp .= '0-9'; 
 }
 
 if ($limitEmptySpaces == kYes) {
  $regExp .= ' '; 
 }

 if (strlen($limitExtraChars) > 0) {
 
  $search = array('\\', '[', ']', '-', '$', '.', '*', '(', ')', '?', '+', '^', '{', '}', '|', '/');
  $replace = array('\\\\', '\[', '\]', '\-', '\$', '\.', '\*', '\(', '\)', '\?', '\+', '\^', '\{', '\}', '\|', '\/');

  $regExp .= str_replace($search, $replace, $limitExtraChars);

 }

 if ( (strlen($regExp) > 0) && (strlen($value) > 0) ){
  if (preg_match('/[^' . $regExp . ']/', $value)) {
   return false;
  }
 }

 if ( (strlen($value) == 0) && ($optional === kOptional) ) {
  return true;
 } elseif ( (strlen($value) >= $low) && ($mode == kStringRangeFrom) ) {
  return true;
 } elseif ( (strlen($value) <= $high) && ($mode == kStringRangeTo) ) {
  return true;
 } elseif ( (strlen($value) >= $low) && (strlen($value) <= $high) && ($mode == kStringRangeBetween) ) {
  return true;
 } else {
  return false;
 }

}


function CheckEmail($email, $optional) {
 if ( (strlen($email) == 0) && ($optional === kOptional) ) {
  return true;
  } elseif ( preg_match("/^([\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+\.)*[\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+@((((([a-z0-9]{1}[a-z0-9\-]{0,62}[a-z0-9]{1})|[a-z])\.)+[a-z]{2,6})|(\d{1,3}\.){3}\d{1,3}(\:\d{1,5})?)$/i", $email) == 1 ) {
  return true;
 } else {
  return false;
 }
}


function CheckValueList_units_validation($ValuesFromField, $ValidationType, $Optional) {

 $ValuesListFromText[] = '3092';


 $SelectedValues = 0;

 if ( !is_array( $ValuesFromField ) ) {
  if ( strlen( $ValuesFromField) > 0 ) {
   $ValuesFromField = array( $ValuesFromField );
  } else {
   $ValuesFromField = array();
  }
 }

 foreach ( $ValuesFromField as $ValuesFromField_Key => $ValuesFromField_Value ) {
  foreach ( $ValuesListFromText as $ValuesListFromText_Key => $ValuesListFromText_Value ) {  
   if ( strcasecmp( $ValuesFromField_Value, $ValuesListFromText_Value ) == 0 ) {
    $SelectedValues++;
    break;
   }
  } 
  reset( $ValuesListFromText );
 }

 if ( ( count( $ValuesFromField ) == 0 ) && ( $Optional === kOptional ) ) {
  return true;
 } elseif ( ( $ValidationType == 1 ) && ( $SelectedValues > 0 ) ) {
  return true;
 } elseif ( ( $ValidationType == 2 ) && ( $SelectedValues == count( $ValuesListFromText ) ) ) {
  return true;
 } elseif ( ( $ValidationType == 3 ) && ( $SelectedValues == 0 ) ) {
  return true;
 } else {
  return false;
 }
 
}



if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
 $clientIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
 $clientIP = $_SERVER['REMOTE_ADDR'];
}

$FTGvalidation = DoStripSlashes( $_POST['validation'] );
$FTGunits_name = DoStripSlashes( $_POST['units_name'] );
$FTGunits_email = DoStripSlashes( $_POST['units_email'] );
$FTGunits_message = DoStripSlashes( $_POST['units_message'] );
$FTGunits_validation = DoStripSlashes( $_POST['units_validation'] );
$FTGsubmit = DoStripSlashes( $_POST['submit'] );
$FTGreset = DoStripSlashes( $_POST['reset'] );



$validationFailed = false;

# Fields Validations


if (!CheckString($FTGunits_name, 1, 30, kStringRangeBetween, kNo, kNo, kNo, '', kMandatory)) {
 $FTGErrorMessage['units_name'] = 'Please enter your name (up to&Prime;0 characters).';
 $validationFailed = true;
}

if (!CheckEmail($FTGunits_email, kMandatory)) {
 $FTGErrorMessage['units_email'] = 'Please enter a valid email address.';
 $validationFailed = true;
}

if (!CheckString($FTGunits_message, 1, 200, kStringRangeBetween, kNo, kNo, kNo, '', kMandatory)) {
 $FTGErrorMessage['units_message'] = 'Please enter your message (up to&prime;00 characters).';
 $validationFailed = true;
}

if (!CheckValueList_units_validation($FTGunits_validation, 1, kMandatory)) {
 $FTGErrorMessage['units_validation'] = '';
 $validationFailed = true;
}



# Include message in error page and dump it to the browser

if ($validationFailed === true) {

 $errorPage = '<html><head><meta http-equiv="content-type" content="text/html; charset=utf-8" /><title>Error</title></head><body>Errors found: <!--VALIDATIONERROR--></body></html>';


 $errorList = @implode("<br />\n", $FTGErrorMessage);
 $errorPage = str_replace('<!--VALIDATIONERROR-->', $errorList, $errorPage);



 echo $errorPage;

}

if ( $validationFailed === false ) {

 # Email to Form Owner
  
 $emailSubject = FilterCChars("Enquiry from Masonic Hall Radlett website - Units Form");
  
 $emailBody = "validation : $FTGvalidation\n"
  . "units name : $FTGunits_name\n"
  . "units email : $FTGunits_email\n"
  . "units message : $FTGunits_message\n"
  . "units validation : $FTGunits_validation\n"
  . "submit : $FTGsubmit\n"
  . "reset : $FTGreset\n"
  . "";
  $emailTo = 'GAK <gakolthammer@ntlworld.com>';
   
  $emailFrom = FilterCChars("$FTGunits_email");
   
  $emailHeader = "From: $emailFrom\n"
   . "MIME-Version: 1.0\n"
   . "Content-type: text/plain; charset=\"UTF-8\"\n"
   . "Content-transfer-encoding: 8bit\n";
   
  mail($emailTo, $emailSubject, $emailBody, $emailHeader);
  
  
  # Include message in the success page and dump it to the browser

$successPage = '<html><head><meta http-equiv="content-type" content="text/html; charset=utf-8" /><title>Success</title></head><body>Thank you for submitting you enquiry, it will be reviewed soon.</body></html>';


echo $successPage;

}

?>