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


  // function to valid phone number
  // must only contain 0-9, spaces, and ()-.
  // this function is helpful because it validates valid phone numbers 
  function has_valid_phone_format($value) {
    
    return preg_match('%^\(?\d{3}\)?[- ]?\d{3}[- ]?\d{4}$%', $value);
  }

  // function to validate user and make suer that it only contains
  // characters: A-Z, a-z, 0-9, and _
  function has_valid_username_format($value) {
    return preg_match('%^[a-zA-Z0-9_]+$%', $value);
  }

  // has_valid_email_format('test@test.com')
  // Validates that email addresses contain only whitelisted characters: 
  // A-Z, a-z, 0-9, and @._-.  
  function has_valid_email_format($value) {
    // Custom part added: Improved function that not only checks for the characters allowed
    // specified above but it also checks that email ends with a '.' plus more than 2 characters
    return preg_match('%^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$%', $value);
  }

  // Custom validation
  // Validates the state code is exactly two characters long and
  // that contains only upper case A-Z
  function has_valid_code($value) {
    return preg_match('%^[A-Z]{2}$%', $value);
  }

  // Custmo validation
  // checks that the name only conatins upper or lower case
  // letters a-z, A-Z. At least two characters long
  // Can be used for users, salespeople, and states/terriroties
  function has_valid_name($value) {
    return preg_match('%^[a-zA-Z]{2,}$%', $value);
  }

  // Custom validation
  // Validation to check if the position of the territory is
  // an integer. It cannot contain any letters or special chars
  function has_valid_position($value){
    return preg_match('%^\d+$%', $value);
  }
?>