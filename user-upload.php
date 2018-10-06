<?php

  $file = "users.csv";
  
  if(file_exists($file)){ // Check the existence of file
    $f = fopen($file, "r") or die("ERROR: Cannot open the file."); // Open file for reading or give error message if you can't open file
    $headers_read = 0;

    while ($record = fgetcsv($f)) { // Loop through csv file 1 record at a time
      foreach($record as $field) {     // Loop through fields in current record
        if($headers_read == 3 ) {         // If you have read the headers name, surname & email
          echo $field . "\n";               // Print each field
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