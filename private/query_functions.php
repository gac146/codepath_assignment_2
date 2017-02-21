<?php

  //
  // COUNTRY QUERIES
  //

  // Find all countries, ordered by name
  function find_all_countries() {
    global $db;
    $sql = "SELECT * FROM countries ORDER BY name ASC;";
    $country_result = db_query($db, $sql);
    return $country_result;
  }

  //
  // STATE QUERIES
  //

  // Find all states, ordered by name
  function find_all_states() {
    global $db;
    $sql = "SELECT * FROM states ";
    $sql .= "ORDER BY name ASC;";
    $state_result = db_query($db, $sql);
    return $state_result;
  }

  // Find all states, ordered by name
  function find_states_for_country_id($country_id=0) {
    global $db;
    $country_id = mysqli_real_escape_string($db, $country_id);
    $sql = "SELECT * FROM states ";
    $sql .= "WHERE country_id='" . $country_id . "' ";
    $sql .= "ORDER BY name ASC;";
    $state_result = db_query($db, $sql);
    return $state_result;
  }

  // Find state by ID
  function find_state_by_id($id=0) {
    global $db;
    $id = mysqli_real_escape_string($db, $id);
    $sql = "SELECT * FROM states ";
    $sql .= "WHERE id='" . $id . "';";
    $state_result = db_query($db, $sql);
    return $state_result;
  }

  function validate_state($state, $errors=array()) {
    // TODO add validations
    if (is_blank($state['name'])) {
      $errors[] = "State name cannot be blank.";
    } elseif (!has_length($state['name'], array('min' => 2, 'max' => 255))) {
      $errors[] = "State name must be between 2 and 255 characters.";
    } elseif(!has_valid_name($state['name'])) {
      $errors[] = "State name must contain only characters: A-Z and/or a-z.";
    }

    if (is_blank($state['code'])) {
      $errors[] = "State code cannot be blank.";
    } elseif (!has_length($state['code'], array('min' => 2, 'max' => 2))) {
      $errors[] = "State code must be extacly 2 characters.";
    } elseif(!has_valid_code($state['code'])) {
      $errors[] = "State code must be of valid format. Only characters A-Z allowed.";
    }

    return $errors;
  }

  // Add a new state to the table
  // Either returns true or an array of errors
  function insert_state($state) {
    global $db;
    $state['country_id'] = 1;

    $errors = validate_state($state);
    if (!empty($errors)) {
      return $errors;
    }

    $sql = "INSERT INTO states ";
    $sql .= "(name, code, country_id) ";
    $sql .= "VALUES (";
    $sql .= "'" . mysqli_real_escape_string($db, $state['name']) . "', ";
    $sql .= "'" . mysqli_real_escape_string($db, $state['code']) . "', ";
    $sql .= "'" . $state['country_id'] . "'";
    $sql .= ");";

    // For INSERT statments, $result is just true/false
    $result = db_query($db, $sql);
    if($result) {
      return true;
    } else {
      // The SQL INSERT statement failed.
      // Just show the error, not the form
      echo db_error($db);
      db_close($db);
      exit;
    }
  }

  // Edit a state record
  // Either returns true or an array of errors
  function update_state($state) {
    global $db;

    $errors = validate_state($state);
    if (!empty($errors)) {
      return $errors;
    }

    $sql = "UPDATE states SET ";
    $sql .= "name='" . mysqli_real_escape_string($db, $state['name']) . "', ";
    $sql .= "code='" . mysqli_real_escape_string($db, $state['code']) . "', ";
    $sql .= "country_id='" . mysqli_real_escape_string($db, $state['country_id']) . "' ";
    $sql .= "WHERE id='" . mysqli_escape_string($db, $state['id']) . "' ";
    $sql .= "LIMIT 1;";

    // For update_state statments, $result is just true/false
    $result = db_query($db, $sql);
    if($result) {
      return true;
    } else {
      // The SQL UPDATE statement failed.
      // Just show the error, not the form
      echo db_error($db);
      db_close($db);
      exit;
    }
  }

  //
  // TERRITORY QUERIES
  //

  // Find all territories, ordered by state_id
  function find_all_territories() {
    global $db;
    $sql = "SELECT * FROM territories ";
    $sql .= "ORDER BY state_id ASC, position ASC;";
    $territory_result = db_query($db, $sql);
    return $territory_result;
  }

  // Find all territories whose state_id (foreign key) matches this id
  function find_territories_for_state_id($state_id=0) {
    global $db;
    $state_id = mysqli_real_escape_string($db, $state_id);
    $sql = "SELECT * FROM territories ";
    $sql .= "WHERE state_id='" . $state_id . "' ";
    $sql .= "ORDER BY position ASC;";
    $territory_result = db_query($db, $sql);
    return $territory_result;
  }

  // Find territory by ID
  function find_territory_by_id($id=0) {
    global $db;
    $id = mysqli_real_escape_string($db, $id);
    $sql = "SELECT * FROM territories ";
    $sql .= "WHERE id='" . $id . "';";
    $territory_result = db_query($db, $sql);
    return $territory_result;
  }

  function validate_territory($territory, $errors=array()) {
    
    // TODO add validations
    if (is_blank($territory['name'])) {
      $errors[] = "Territory name cannot be blank.";
    } elseif (!has_length($territory['name'], array('min' => 2, 'max' => 255))) {
      $errors[] = "Territory name must be between 2 and 255 characters.";
    } elseif(!has_valid_name($territory['name'])) {
      $errors[] = "Territory name must contain only characters: A-Z and/or a-z.";
    }

    if (is_blank($territory['position'])) {
      $errors[] = "Territory position cannot be blank.";
    } elseif (!has_length($territory['position'], array('min' => 1, 'max' => 255))) {
      $errors[] = "Territory position must be a number greater than 1.";
    } elseif(!has_valid_position($territory['position'])) {
      $errors[] = "Territory position must contain only number: 0-9";
    }

    return $errors;
  }

  // Add a new territory to the table
  // Either returns true or an array of errors
  function insert_territory($territory) {
    global $db;

    $errors = validate_territory($territory);
    if (!empty($errors)) {
      return $errors;
    }

    $sql = "INSERT INTO territories ";
    $sql .= "(name, state_id, position) ";
    $sql .= "VALUES (";
    $sql .= "'" . mysqli_real_escape_string($db, $territory['name']) . "', ";
    $sql .= "'" . mysqli_real_escape_string($db, $territory['state_id']) . "', ";
    $sql .= "'" . mysqli_real_escape_string($db, $territory['position']) . "'";
    $sql .= ");";

    // For INSERT statments, $result is just true/false
    $result = db_query($db, $sql);
    if($result) {
      return true;
    } else {
      // The SQL INSERT territoryment failed.
      // Just show the error, not the form
      echo db_error($db);
      db_close($db);
      exit;
    }
  }

  // Edit a territory record
  // Either returns true or an array of errors
  function update_territory($territory) {
    global $db;

    $errors = validate_territory($territory);
    if (!empty($errors)) {
      return $errors;
    }

    $sql = "UPDATE territories SET ";
    $sql .= "name='" . mysqli_real_escape_string($db, $territory['name']) . "', ";
    $sql .= "state_id='" . mysqli_real_escape_string($db, $territory['state_id']) . "', ";
    $sql .= "position='" . mysqli_real_escape_string($db, $territory['position']) . "' ";
    $sql .= "WHERE id='" . mysqli_escape_string($db, $territory['id']) . "' ";
    $sql .= "LIMIT 1;";

    // For update_territory statments, $result is just true/false
    $result = db_query($db, $sql);
    if($result) {
      return true;
    } else {
      // The SQL UPDATE territoryment failed.
      // Just show the error, not the form
      echo db_error($db);
      db_close($db);
      exit;
    }
  }

  //
  // SALESPERSON QUERIES
  //

  // Find all salespeople, ordered last_name, first_name
  function find_all_salespeople() {
    global $db;
    $sql = "SELECT * FROM salespeople ";
    $sql .= "ORDER BY last_name ASC, first_name ASC;";
    $salespeople_result = db_query($db, $sql);
    return $salespeople_result;
  }

  // To find salespeople, we need to use the join table.
  // We LEFT JOIN salespeople_territories and then find results
  // in the join table which have the same territory ID.
  function find_salespeople_for_territory_id($territory_id=0) {
    global $db;
    $territory_id = mysqli_real_escape_string($db, $territory_id);
    $sql = "SELECT * FROM salespeople ";
    $sql .= "LEFT JOIN salespeople_territories
              ON (salespeople_territories.salesperson_id = salespeople.id) ";
    $sql .= "WHERE salespeople_territories.territory_id='" . $territory_id . "' ";
    $sql .= "ORDER BY last_name ASC, first_name ASC;";
    $salespeople_result = db_query($db, $sql);
    return $salespeople_result;
  }

  // Find salesperson using id
  function find_salesperson_by_id($id=0) {
    global $db;
    $id = mysqli_real_escape_string($db, $id);
    $sql = "SELECT * FROM salespeople ";
    $sql .= "WHERE id='" . $id . "';";
    $salespeople_result = db_query($db, $sql);
    return $salespeople_result;
  }

  function validate_salesperson($salesperson, $errors=array()) {
    // TODO add validations
    if (is_blank($salesperson['first_name'])) {
      $errors[] = "First name cannot be blank.";
    } elseif (!has_length($salesperson['first_name'], array('min' => 2, 'max' => 255))) {
      $errors[] = "First name must be between 2 and 255 characters.";
    } elseif(!has_valid_name($salesperson['first_name'])) {
      $errors[] = "First name must contain only characters: A-Z and/or a-z.";
    }

    if (is_blank($salesperson['last_name'])) {
      $errors[] = "Last name cannot be blank.";
    } elseif (!has_length($salesperson['last_name'], array('min' => 2, 'max' => 255))) {
      $errors[] = "Last name must be between 2 and 255 characters.";
    } elseif(!has_valid_name($salesperson['last_name'])) {
      $errors[] = "Last name must contain only characters: A-Z and/or a-z.";
    }

    if (is_blank($salesperson['phone'])) {
      $errors[] = "Phone number cannot be blank.";
    } elseif (!has_valid_phone_format($salesperson['phone'])) {
      $errors[] = "Phone number must be a valid format. Only characters: 0-9, spaces, and () - allowed.";
    }

    if (is_blank($salesperson['email'])) {
      $errors[] = "Email cannot be blank.";
    } elseif (!has_valid_email_format($salesperson['email'])) {
      echo has_valid_email_format($salesperson['email']);
      $errors[] = "Email must be a valid format. Only characters: A-Z, a-z, 0-9, and @._- allowed.";
    }

    return $errors;
  }

  // Add a new salesperson to the table
  // Either returns true or an array of errors
  function insert_salesperson($salesperson) {
    global $db;

    $errors = validate_salesperson($salesperson);
    if (!empty($errors)) {
      return $errors;
    }
    
    //updating phone number to correct format after checking for errors to enter in database
    $salesperson['phone'] = format_phone_number($salesperson['phone']);

    $sql = "INSERT INTO salespeople ";
    $sql .= "(first_name, last_name, phone, email) ";
    $sql .= "VALUES (";
    $sql .= "'" . mysqli_real_escape_string($db, $salesperson['first_name']) . "', ";
    $sql .= "'" . mysqli_real_escape_string($db, $salesperson['last_name']) . "', ";
    $sql .= "'" . mysqli_real_escape_string($db, $salesperson['phone']) . "', ";
    $sql .= "'" . mysqli_real_escape_string($db, $salesperson['email']) . "'";
    $sql .= ");";

    // For INSERT statments, $result is just true/false
    $result = db_query($db, $sql);
    if($result) {
      return true;
    } else {
      // The SQL INSERT statement failed.
      // Just show the error, not the form
      echo db_error($db);
      db_close($db);
      exit;
    }
  }

  // Edit a salesperson record
  // Either returns true or an array of errors
  function update_salesperson($salesperson) {
    global $db;

    $errors = validate_salesperson($salesperson);

    if (!empty($errors)) {
      return $errors;
    }

    //updating phone number to correct format after checking for errors
    $salesperson['phone'] = format_phone_number($salesperson['phone']);

    $sql = "UPDATE salespeople SET ";
    $sql .= "first_name='" . mysqli_real_escape_string($db, $salesperson['first_name']) . "', ";
    $sql .= "last_name='" . mysqli_real_escape_string($db, $salesperson['last_name']) . "', ";
    $sql .= "phone='" . mysqli_real_escape_string($db, $salesperson['phone']) . "', ";
    $sql .= "email='" . mysqli_real_escape_string($db, $salesperson['email']) . "' ";
    $sql .= "WHERE id='" . mysqli_real_escape_string($db, $salesperson['id']) . "' ";
    $sql .= "LIMIT 1;";

    // For update_salesperson statments, $result is just true/false
    $result = db_query($db, $sql);
    if($result) {
      return true;
    } else {
      // The SQL UPDATE statement failed.
      // Just show the error, not the form
      echo db_error($db);
      db_close($db);
      exit;
    }
  }

  // To find territories, we need to use the join table.
  // We LEFT JOIN salespeople_territories and then find results
  // in the join table which have the same salesperson ID.
  function find_territories_by_salesperson_id($id=0) {
    global $db;
    $id = mysqli_real_escape_string($db, $id);
    $sql = "SELECT * FROM territories ";
    $sql .= "LEFT JOIN salespeople_territories
              ON (territories.id = salespeople_territories.territory_id) ";
    $sql .= "WHERE salespeople_territories.salesperson_id='" . $id . "' ";
    $sql .= "ORDER BY territories.name ASC;";
    $territories_result = db_query($db, $sql);
    return $territories_result;
  }

  //
  // USER QUERIES
  //

  // Find all users, ordered last_name, first_name
  function find_all_users() {
    global $db;
    $sql = "SELECT * FROM users ";
    $sql .= "ORDER BY last_name ASC, first_name ASC;";
    $users_result = db_query($db, $sql);
    return $users_result;
  }

  // Find user using id
  function find_user_by_id($id=0) {
    global $db;
    $id = mysqli_real_escape_string($db, $id);
    $sql = "SELECT * FROM users WHERE id='" . $id . "' LIMIT 1;";
    $users_result = db_query($db, $sql);
    return $users_result;
  }

  function validate_user($user, $errors=array()) {
    if (is_blank($user['first_name'])) {
      $errors[] = "First name cannot be blank.";
    } elseif (!has_length($user['first_name'], array('min' => 2, 'max' => 255))) {
      $errors[] = "First name must be between 2 and 255 characters.";
    } elseif(!has_valid_name($user['first_name'])) {
      $errors[] = "First name must contain only characters: A-Z and/or a-z.";
    }

    if (is_blank($user['last_name'])) {
      $errors[] = "Last name cannot be blank.";
    } elseif (!has_length($user['last_name'], array('min' => 2, 'max' => 255))) {
      $errors[] = "Last name must be between 2 and 255 characters.";
    } elseif(!has_valid_name($user['last_name'])) {
      $errors[] = "Last name must contain only characters: A-Z and/or a-z.";
    }

     if (is_blank($user['username'])) {
      $errors[] = "Username cannot be blank.";
    } elseif (!has_length($user['username'], array('max' => 255))) {
      $errors[] = "Username must be less than 255 characters.";
    } elseif(!has_valid_username_format($user['username'])) {
      $errors[] = "Username must be of valid format. Only characters: A-Z, a-z, 0-9, and _ allowed.";
    } 

    if (is_blank($user['email'])) {
      $errors[] = "Email cannot be blank.";
    } elseif (!has_valid_email_format($user['email'])) {
      $errors[] = "Email must be a valid format. Only characters: A-Z, a-z, 0-9, and @._- allowed.";
    }
   
    return $errors;
  }

  // Add a new user to the table
  // Either returns true or an array of errors
  function insert_user($user) {
    global $db;

    $errors = validate_user($user);

    //checking for uniqueness of user name on new user
    /*if(!is_unique_new('username', 'users', $user['username'])){
      $errors[] = "Username already taken. Please enter a different user name.";
    }*/

    if (!empty($errors)) {
      return $errors;
    }

    $created_at = date("Y-m-d H:i:s");
    $sql = "INSERT INTO users ";
    $sql .= "(first_name, last_name, email, username, created_at) ";
    $sql .= "VALUES (";
    $sql .= "'" . mysqli_escape_string($db, $user['first_name']) . "', ";
    $sql .= "'" . mysqli_escape_string($db, $user['last_name']) . "', ";
    $sql .= "'" . mysqli_escape_string($db, $user['email']) . "', ";
    $sql .= "'" . mysqli_escape_string($db, $user['username']) . "', ";
    $sql .= "'" . mysqli_escape_string($db, $created_at) . "'";
    $sql .= ");";
    // For INSERT statments, $result is just true/false
    $result = db_query($db, $sql);
    if($result) {
      return true;
    } else {
      // The SQL INSERT statement failed.
      // Just show the error, not the form
      echo db_error($db);
      db_close($db);
      exit;
    }
  }

  // Edit a user record
  // Either returns true or an array of errors
  function update_user($user) {
    global $db;

    $errors = validate_user($user);

    if (!empty($errors)) {
      return $errors;
    }

    $sql = "UPDATE users SET ";
    $sql .= "first_name='" . mysqli_escape_string($db, $user['first_name']) . "', ";
    $sql .= "last_name='" . mysqli_escape_string($db, $user['last_name']) . "', ";
    $sql .= "email='" . mysqli_escape_string($db, $user['email']) . "', ";
    $sql .= "username='" . mysqli_escape_string($db, $user['username']) . "' ";
    $sql .= "WHERE id='" . mysqli_escape_string($db, $user['id']) . "' ";
    $sql .= "LIMIT 1;";
    // For update_user statments, $result is just true/false
    $result = db_query($db, $sql);
    if($result) {
      return true;
    } else {
      // The SQL UPDATE statement failed.
      // Just show the error, not the form
      echo db_error($db);
      db_close($db);
      exit;
    }
  }

   // Edit a user record
  // Either returns true or an array of errors
  function delete_user($id) {
    global $db;

    $id = mysqli_real_escape_string($db, $id);
    $sql = "DELETE FROM users WHERE id='" . $id . "' LIMIT 1;";
    
    // Checking if deletion was succesful
    $result = db_query($db, $sql);
    if($result) {
      return true;
    } else {
      // The SQL DELETE statement failed.
      // Showing the error
      echo db_error($db);
      db_close($db);
      exit;
    }
  }

?>
