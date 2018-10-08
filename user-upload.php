<?php
  function validate_email($email) {
    $pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";
    return preg_match($pattern, $email);
  }

  function validate_name($name) {
    $pattern = "/^([a-z']+)$/";
    return preg_match($pattern, $name);
  }

  $file = "users.csv";
    
  $dbh = pg_connect("host=localhost dbname=test user=root password=root"); // attempt a connection to database

  if (!$dbh) {
    die("Error in database connection: " . pg_last_error());
  }
  else {
    echo "Connection to database opened & ok.\n";

    // Create database table called users
    $sql = "CREATE TABLE IF NOT EXISTS users ( 
      id serial PRIMARY KEY,
      name text NOT NULL,
      surname text NOT NULL,
      email text NOT NULL UNIQUE 
    )";
    pg_exec($dbh, $sql) or die(pg_errormessage());
  }

  if (file_exists($file)) { // Check the existence of file
    $f = fopen($file, "r") or die ("ERROR: Cannot open the file."); // Open file for reading or give error message if you can't open file
    $headers_read = 0;
    $field_no = 1; 

    while ($record = fgetcsv($f)) { // Loop through csv file 1 record at a time
      foreach ($record as $field)   {  // Loop through fields in current record
        if ($headers_read === 3 )      {   // If you have read the headers name, surname & email
          $field = strtolower( trim($field) );// then remove spaces from fieldstring and make it lowercase
          
          if ($field_no < 3) {               // If field is firstname or lastname
            if (!validate_name($field)) {       // check if valid name or surname
              echo "INVALID NAME OR SURNAME GIVEN!\n";
            }
            
            $field = ucwords($field);           // then capitalize it
            $field_no = $field_no + 1;          // and look at next field
          }
          else {                             // Else must be at email
            if ( !validate_email($field) ) {    // Check for invalid email address
              echo "INVALID EMAIL ADDRESS GIVEN!\n";
            }
            $field_no = 1;                     // so look at first field from next record   
          }
          echo $field . "\n";                // Print each field

          if($field_no === 1) { // If you just printed an email
            echo "\n";             // print an extra blank line to separate records
          }
        }
        else {                            // else read in "Headers" name, surname & email
          $headers_read = $headers_read + 1;
        }
      }
    }
    fclose($f); // Close input csv file

    // Drop database table called users
    $sql = "DROP TABLE users";
    pg_exec($dbh, $sql) or die(pg_errormessage());

    pg_close($dbh);  // close connection to database
    echo "Connection to database closed\n";
  }
  else {
    echo "ERROR: File does not exist.";
  }

?>