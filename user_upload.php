<?php
    ReadArguments($argv, $argc);


    function ReadArguments($arguments, $NumberOfArguments){
        /*checks if the --help command has been called*/
        for ($i = 1; $i <= $NumberOfArguments -1; ++$i) {
            if($arguments[$i] == "--help"){
                help();
                exit;
                /*ends the script after the help info has been shown*/
            }
        }
    }


    /*prints detailed info about the possible script arguments*/
    function help(){
        print "--file [csv file name] – this is the name of the CSV to be parsed; \n";
        print "--create_table – this will cause the MySQL users table to be built (and no further action will be taken)  \n";
        print "--dry_run – this will be used with the --file directive in the instance that we want to run the script but not insert into the DB. All other functions will be executed, but the database won't
        be altered \n";
        print "-u – MySQL username \n";
        print "-p – MySQL password \n";
        print "-h – MySQL host \n";
    }


    readCSV();



    $email = "oieoieoie";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Invalid email format"; 
    }

function readCSV(){
    $csvFile = file('users.csv');
    $data = [];
    foreach ($csvFile as $line) {
        $data[] = str_getcsv($line);
    }

}
?>