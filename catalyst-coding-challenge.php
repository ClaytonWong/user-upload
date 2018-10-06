<?php

  $file = "users.csv";

  
  if(file_exists($file)){ // Check the existence of file
    // Reading the entire file into a string
    $content = file_get_contents($file) or die("ERROR: Cannot open the file.");

    // Display the file content 
    echo $content;

  } 
  else {
    echo "ERROR: File does not exist.";
  }
?>

