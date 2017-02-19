<?php

  // is_blank('abcd')
  function is_blank($value='') {
    return !isset($value) || trim($value) == '';
  }

  // has_length('abcd', ['min' => 3, 'max' => 5])
  function has_length($value, $options=array()) {
    $length = strlen($value);
    if(isset($options['max']) && ($length > $options['max'])) {
      return false;
    } elseif(isset($options['min']) && ($length < $options['min'])) {
      return false;
    } elseif(isset($options['exact']) && ($length != $options['exact'])) {
      return false;
    } else {
      return true;
    }
  }

  // has_valid_email_format('test@test.com')
  // Validates that email addresses contain only whitelisted characters: 
  // A-Z, a-z, 0-9, and @._-.
  function has_valid_email_format($value) {
    // Improve function to check if the first part of the email contains
    // the permited characters. Checks if it contains a '@' character and
    // if the email ends with '.' plus more than 2 characters
    return preg_match('%^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$%', $value);
  }

  // function to valid phone number
  // must only contain 0-9, spaces, and ()-.
  // this function is helpful because it validates valid phone numbers 
  function has_valid_phone_format($value){
    
    return preg_match('%^\(?\d{3}\)?[- ]?\d{3}[- ]?\d{4}$%', $value);
  }

?>
