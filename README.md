## What is user-upload.php and what does it do? ##
user-upload.php is a PHP program that can parse a csv file and insert
data the from the csv file into a Postgresql database table called "users".

As it reads the file it will display the fields from the csv file and
give and error message if a field is invalid.
After the database table is populated, it will read from the database
table, output the contents then drop the table.

## The csv files ##
The csv file has a header followed by the records. The fields are
in the following order: name, surname then email.

### Requirements ###
The program will only insert a record if the fields fufil the following
requirements:

* name only uses letters a to z
* surname only uses letters a to z
* email is an email address in a valid format
* email is a unique email address

## Assumptions ##
I assume the program will work on Ubuntu 16.04 and any other Linux distros
derived from it. I don't expect it to work on Windows or Mac.

## How to use ##
"The following directives can used with user-upload.php
    
  --file [csv file name] – this is the name of the CSV to be parsed
  --create_table – this will cause the PostgreSQL users table to be 
    built (and no further action will be taken)
  --dry_run – this will be used with the --file directive in the
    instance that we want to run the script but not insert into
    the DB. All other functions will be executed, but the database
    won't be altered.
   -u – PostgreSQL username
   -p – PostgreSQL password
   -h – PostgreSQL host
  --help – which will output the above list of directives with details.
     
for example, typing in:

      php user-upload.php --file user2.csv

will parse user2.csv, create and populate a table, use the default
username, password & host, and drop the table.

## Why I wrote user-upload.php and who is it for ##
I wrote this code as part of a coding challenge when I applied for a job at
Catalyst IT in Melbourne Australia around early October 2018.

## Resources I used to do this PHP coding challenge: ##

* https://www.sitepoint.com/working-with-files-in-php/

  To find out how to work with files.

* http://tizag.com/phpT/php-string-strtoupper-strtolower.php

  To find out how to change strings to lower case and capitalize.

* http://fr.php.net/manual/en/function.filter-var.php
* https://stackoverflow.com/questions/13447539/php-preg-match-with-email-validation-issue

  To find out how to validate emails properly, especially the 'phil@open.edu.au' 'not valid' problem

* https://www.techrepublic.com/blog/how-do-i/how-do-i-use-php-with-postgresql/

  To find out how to work with Posgresql databases in PHP

* http://forums.devshed.com/postgresql-help-21/creating-table-postgresql-using-php-19274.html

  To find out how to create postgresql tables

* https://www.w3schools.com/PHP/func_misc_die.asp

  To find out about 'die' function in PHP

* https://secure.php.net/manual/en/function.getopt.php
* https://www.sitepoint.com/php-command-line-1-3/

  To find out about script commandline directives

* https://stackoverflow.com/questions/14097897/how-to-fix-notice-undefined-index-in-php-form-action
* https://secure.php.net/manual/en/function.isset.php
* https://stackoverflow.com/questions/40288959/getopt-return-php-notice-undefined-index

  To find out how to fix 'Notice: Undefined index:' problem