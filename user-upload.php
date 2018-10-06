<?php

  $file = "users.csv";
  
  if(file_exists($file)){ // Check the existence of file
    $f = fopen($file, "r") or die("ERROR: Cannot open the file."); // Open file for reading or give error message if you can't open file
  
    while ($record = fgetcsv($f)) { // Loop through csv file 1 record at a time
      foreach($record as $field) {     // Loop through fields in current record
        echo $field . "\n";               // Print each field
      }
    }
    fclose($f);
  }
  else {
    echo "ERROR: File does not exist.";
  }

?>