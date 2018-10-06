<?php

  $file = "users.csv";
  
  if(file_exists($file)){ // Check the existence of file
    $f = fopen($file, "r") or die("ERROR: Cannot open the file."); // Open file for reading or give error message if you can't open file
    $headers_read = 0;
    $field_no = 1; 

    while ($record = fgetcsv($f)) { // Loop through csv file 1 record at a time
      foreach($record as $field) {     // Loop through fields in current record
        if($headers_read == 3 ) {         // If you have read the headers name, surname & email
          $field = strtolower($field);       // then make fieldstring lowercase
          
          if($field_no < 3) {                // If field is firstname or lastname
            $field = ucwords($field);          // then capitalize it
            $field_no = $field_no + 1;         // and look at next field
          }
          else {                             // Else must be at email
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