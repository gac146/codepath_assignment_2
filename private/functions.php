<?php

  function h($string="") {
    return htmlspecialchars($string);
  }

  function u($string="") {
    return urlencode($string);
  }

  function raw_u($string="") {
    return rawurlencode($string);
  }

  function redirect_to($location) {
    header("Location: " . $location);
    exit;
  }

  function is_post_request() {
    return $_SERVER['REQUEST_METHOD'] == 'POST';
  }

  function display_errors($errors=array()) {
    $output = '';
    if (!empty($errors)) {
      $output .= "<div class=\"errors\">";
      $output .= "Please fix the following errors:";
      $output .= "<ul>";
      foreach ($errors as $error) {
        $output .= "<li>{$error}</li>";
      }
      $output .= "</ul>";
      $output .= "</div>";
    }
    return $output;
  }

  //formats phone number to be inserted into the database
  //useful function to have all phopne numbers in the database 
  // under the same format
  function format_phone_number($value){

    $phone = '';
    if (preg_match('%^\(?(\d{3})\)?[- ]?(\d{3})[- ]?(\d{4})$%', $value, $matches)){
      $phone = $matches[1];
      $phone .= '-';
      $phone .= $matches[2];
      $phone .= '-';
      $phone .= $matches[3];
    }

    echo $phone;

    return $phone;
  }

?>
