<?php
    $shortopts = ("u:p:h:d:");
    $longopts  = array(
        "file:",     // CSV file directory
        "create_table",        // Creates table
        "dry_run", //runs code without updating database
        "help",          // Displays help
    );
    $options = getopt($shortopts, $longopts);


    ReadArguments($options);


    function ReadArguments($options){
        if (sizeof($options) == 0){
            print "Wrong usage. Use --help for detailed info about using the program. \n" ;
            exit;
        }

        /*checks if the --help or --create_table command has been called*/
        foreach($options as $key => $value) {
            if(isset($options['help'])){
                help();
                exit;
                /*ends the script after the help info has been shown*/
            }
            elseif(isset($options['create_table'])) {
                CreateTable($options);
                exit;
            }
        }
    }

    function LoginCheck($options){
        if($options['u'] and $options['p'] and $options['h'] and $options['d']){
            // Create connection
            $mysqli = new mysqli($options['h'], $options['u'], $options['p']);
            // Check connection
            if ($mysqli->connect_error) {
                die("Connection failed: " . $conn->connect_error);
                echo "Please check if the username, password, and host are correct\n";
                exit;
            } 
            else {
                echo "Connected successfully\n";
                return $mysqli;
            }
        }
        else{
            echo "Please enter username, password, and host correctly\n";
            exit;
        }
    }

    function CheckIfTableExists($conn){
        $val = $conn->query('select 1 from `Users` LIMIT 1');
        if($val !== FALSE){
            echo "Table Users already exists\n";
            exit;
        }
    }

    function CreateTable($options){
        $conn = LoginCheck($options);
        mysqli_select_db($conn,$options['d']);
        CheckIfTableExists($conn);
        $sql = "CREATE TABLE Users (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(30) NOT NULL,
            surname VARCHAR(30) NOT NULL,
            email VARCHAR(50) UNIQUE NOT NULL,
            reg_date TIMESTAMP
        )";
        if ($conn->query($sql) === TRUE) {
            echo "Table Users created successfully";
        } 
        else {
            echo "Error creating table.\nCheck if the database '". $options['d'] ."' exists and try again.\n ";
        }
        exit;
    }

    function ParseCSV($location){
        $csvFile = file($location);
        $data = [];
        foreach ($csvFile as $line) {
            $data[] = str_getcsv($line);

        }
        return $data;
    }

    function ValidateEmail(){

    }

    /*prints detailed info about the argument usage*/
    function help(){
        print "--file [csv file name] – this is the name of the CSV to be parsed; \n";
        print "--create_table – this will cause the MySQL users table to be built (and no further action will be taken)  \n";
        print "--dry_run – this will be used with the --file directive in the instance that we want to run the script but not insert into the DB. All other functions will be executed, but the database won't
        be altered \n";
        print "-u – MySQL username \n";
        print "-p – MySQL password \n";
        print "-h – MySQL host \n";
    }



    $email = "oieoieoie";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Invalid email format"; 
    }


?>