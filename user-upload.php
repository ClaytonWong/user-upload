<?php
  function validate_email($email) {
    $pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";
    return preg_match($pattern, $email);
  }

  $file = "users.csv";
  
  if (file_exists($file)) { // Check the existence of file
    $f = fopen($file, "r") or die ("ERROR: Cannot open the file."); // Open file for reading or give error message if you can't open file
    $headers_read = 0;
    $field_no = 1; 

    while ($record = fgetcsv($f)) { // Loop through csv file 1 record at a time
      foreach ($record as $field)   {  // Loop through fields in current record
        if ($headers_read === 3 )      {   // If you have read the headers name, surname & email
          //$field = strtolower($field);       // then make fieldstring lowercase
          $field = strtolower( trim($field) );

          if ($field_no < 3) {               // If field is firstname or lastname
            $field = ucwords($field);           // then capitalize it
            $field_no = $field_no + 1;          // and look at next field
          }
          else {                             // Else must be at email
            //if (!filter_var($field, FILTER_VALIDATE_EMAIL)) {
            //if (!filter_var(trim($field), FILTER_VALIDATE_EMAIL)) { // Check for invalid email address
            if ( !validate_email($field) ) {
            //if ( !validate_email( trim($field) ) ) {
                echo "INVALID EMAIL ADDRESS GIVEN!\n";
            }
            $field_no = 1;                     // so look at first field from next record   
          }
          echo $field . "\n";                // Print each field
        }
        else {                            // else read in "Headers" name, surname & email
          $headers_read = $headers_read + 1;
        }
      }
    }
    fclose($f);
  }
  else {
    echo "ERROR: File does not exist.";
  }

?>