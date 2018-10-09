<?php
  function validate_email($email) {
    $pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";
    return preg_match($pattern, $email);
  }

  function validate_name($name) {
    $pattern = "/^([a-zA-Z']+)$/";
    return preg_match($pattern, $name);
  }

  $shortopts  = "";
  $shortopts .= "u:";  // For handling -u postgreSQL directive
  $shortopts .= "p:";  // For handling -p postgreSQL directive
  $shortopts .= "h:";  // For handling -h postgreSQL directive
  
  $longopts  = array(
    "file:",        // For handling --file commandline directives
    "create_table", // For handling --create_table commandline directives
    "dry_run",      // For handling --dry_run commandline directives
  );
  $options = getopt($shortopts, $longopts);
  //var_dump($options);

  $file = "users.csv"; // Default csv file is users.csv
  if ( !empty( $options['file'] ) ) { // If different csv file given
    $file = $options['file'];           // use that instead of the default
  }
  //echo "file = " . $file ."\n";       // Show csv file to be used

  // Code to prevent the error "PHP Notice:  Undefined index: create_table"
  // DOES NOT WORK
  /*
  $create_table_and_more = true;
  if ( $options['create_table'] === false ) {
    $create_table_and_more = false;
  }
  else if ( $options['create_table'] === NULL ){
    $create_table_and_more = true;
  }
  */

  $username = "root"; // Default username is root
  if ( !empty( $options['u'] ) ) { // If different username given
    $username = $options['u'];        // use that instead of the default
  }
  echo "username = " . $username ."\n"; 
/*
  $password = ""; // Default   is 
  if ( !empty( $options[''] ) ) { // If different   given
    $ = $options[''];           // use that instead of the default
  }
  //echo " = " . $ ."\n"; 

  $host = ""; // Default   is 
  if ( !empty( $options[''] ) ) { // If different   given
    $ = $options[''];           // use that instead of the default
  }
  //echo " = " . $ ."\n"; 
*/
  //$dbh = pg_connect("host=localhost dbname=test user=root password=root"); // attempt a connection to database
  $connect_vars = "host=localhost dbname=test user=" . $username . " password=root";
  
  $dbh = pg_connect($connect_vars); // attempt a connection to database

  if (!$dbh) {
    die("Error in database connection: " . pg_last_error());
  }
  else {
    echo "Connection to database opened & ok.\n";
    echo "\n";

    // Create database table called users
    $sql = "CREATE TABLE IF NOT EXISTS users ( 
      id serial PRIMARY KEY,
      name text NOT NULL,
      surname text NOT NULL,
      email text NOT NULL UNIQUE 
    )";
    pg_exec($dbh, $sql) or die(pg_errormessage());

    //if ($options['create_table'] !== NULL) { // If --create_table directive given
    //if (!empty( $options['create_table']) ) { // If --create_table directive given
    //if ($create_table_and_more === false) {
    if ($options['create_table'] === false) { // If --create_table directive given
      echo "Created table only\n";
    }
    else {
      echo "Create & populate table\n";

      if ( file_exists($file) ) { // Check the existence of file
        $f = fopen($file, "r") or die ("ERROR: Cannot open the file."); // Open file for reading or give error message if you can't open file
        $headers_read = 0;
        $field_no = 1;
        $name = "";
        $surname = "";
        $email = "";
        $name_valid = false;
        $surname_valid = false;
        $email_valid = false;
    
        while ( $record = fgetcsv($f) ) { // Loop through csv file 1 record at a time
          foreach ($record as $field)     {  // Loop through fields in current record
            if ($headers_read === 3 )       {   // If you have read the headers name, surname & email
              $field = strtolower( trim($field) ); // then remove spaces from fieldstring and make it lowercase
              
              if ($field_no < 3) {                 // If field is name or surname
                $field = ucwords($field);             // then capitalize it
    
                if ($field_no === 1) {                // If field is name
                  if ( !validate_name($field) ) {        // check if name is invalid
                    $name_valid = false;
                    echo "INVALID NAME GIVEN!\n";
                  }
                  else {                              // Else name is valid
                    $name_valid = true;
                    $name = $field;                      // so store it in $name 
                  }
                }
                else {                             // Else field is surname
                  if ( !validate_name($field) ) {     // check if surname is invalid
                    $surname_valid = false;
                    echo "INVALID SURNAME GIVEN!\n";
                  }
                  else {                              // Else surname is valid
                    $surname_valid = true;
                    $surname = $field;                   // so store it in $surname 
                  }
                }
                
                $field_no = $field_no + 1;          // and look at next field
              }
              else {                               // Else must be at email
                if ( !validate_email($field) ) {      // Check for invalid email address
                  $email_valid = false;
                  echo "INVALID EMAIL ADDRESS GIVEN!\n";
                }
                else {                                // Else email is valid
                  $email_valid = true;
                  $email = $field;                       // so store it in $email 
                }
                $field_no = 1;                        // look at first field from next record   
              }
              echo $field . "\n";                  // Print each field
    
              if($field_no === 1) {                // If you just printed an email
                if ($name_valid && $surname_valid && $email_valid) {
                  echo "Name, surname, & email are valid\n";
                  
                  if ($options['dry_run'] === false) {     // dry_run directive given
                    echo "Dry run for database insert\n";
                  }
                  else {                                   // It's not a dry run
                    // Since name, surname & email are valid,
                    // you can insert them into the database
      
                    // Escape strings in input data
                    $name    = pg_escape_string($name);
                    $surname = pg_escape_string($surname);
                    $email   = pg_escape_string($email);
      
                    // Execute query
                    $sql    = "INSERT INTO users (name, surname, email) VALUES('$name', '$surname', '$email')";
                    $result = pg_query($dbh, $sql);
      
                    if (!$result) {
                      echo "\n";
                      echo "Error in SQL insert query: " . pg_last_error();
                      echo "\n";
                    }
                    else {
                      echo "Data successfully inserted!\n";
                    }
                                
                    // Free memory
                    pg_free_result($result);
                  }
                }
    
                echo "\n";                                 // print an extra blank line to separate records
              }
            }
            else {                              // else read in "Headers" name, surname & email
              $headers_read = $headers_read + 1;
            }
          }
        }
        fclose($f);                       // Close input csv file
    
        if ($options['dry_run'] === NULL) {
          // If not a dry run
          // Check if you can read from database before dropping it
          // execute query
          $sql    = "SELECT * FROM users";
          $result = pg_query($dbh, $sql);
      
          if (!$result) {
            die("Error in SQL retrieve query: " . pg_last_error());
          }
          else {
            echo "Reading entries from database table:\n";

            // Iterate over result set
            // print each row
            while ($row = pg_fetch_array($result)) {
              echo "ID: " . $row[0] . "\n";
              echo "NAME: " . $row[1] . "\n";
              echo "SURNAME: " . $row[2] . "\n";
              echo "EMAIL: " . $row[3] . "\n";
              echo "\n";
            }
          }
      
          // Free memory
          pg_free_result($result);
        }
            
        // Drop database table called users
        echo "Attempting to drop database table...\n";
        $sql = "DROP TABLE users";
        pg_exec($dbh, $sql) or die(pg_errormessage());
      }
      else {
        echo "ERROR: File does not exist.";
      }
    }

    pg_close($dbh);                           // close connection to database
    echo "Connection to database closed\n";
  }
  
?>