<?php
    //commands for the user
    //had to add the -d command to get the database information
    $shortopts = ("u:p:h:d:");
    $longopts  = array(
        "file:",     // CSV file directory
        "create_table",        // Creates table
        "dry_run", //runs code without updating database
        "help",          // Displays help
    );
    $options = getopt($shortopts, $longopts);
    //it all starts here
    ReadArguments($options);


    function ReadArguments($options){
        if (sizeof($options) == 0){
            print "Wrong usage. Use --help for detailed info about using the program. \n" ;
            exit;
        }

        /*checks if the --help or --create_table command has been called*/
        if(isset($options['help'])){
            help();
            exit;
                /*ends the script after the help info has been shown*/
        }
        //creates user table and ends the program
        elseif(isset($options['create_table'])) {
            CreateTable($options);
            exit;
        }
        //time to parse some CSV data
        if(isset($options['file'])){
            FileParsing($options);
        }
        
    }


    //function to check if there is a problem connecting to the database
    //returns a mysqli variable upon connection
    function LoginCheck($options){
        if($options['u'] and $options['p'] and $options['h'] and $options['d']){
            // Create connection
            $mysqli = new mysqli($options['h'], $options['u'], $options['p']);
            // Check connection
            if ($mysqli->connect_error) {
                die("Connection failed: " . $mysqli->connect_error);
                echo "Please check if the username, password, and host are correct\n";
                exit;
            } 
            else {
                echo "Connected to the server...\n";
                //checking connection to the database
                $mysqli = new mysqli($options['h'], $options['u'], $options['p'], $options['d']);
                if ($mysqli->connect_error) {
                    die("Connection failed: " . $mysqli->connect_error);
                    echo "Please check if the database exists\n";
                    exit;
                }
                else{
                    return $mysqli;
                }
                
            }
        }
        else{
            echo "Please enter username, password, and host correctly\n";
            exit;
        }
    }

    //checks if table user already exists, deletes it so it's ready for recreation
    function CheckIfTableExists($conn){
        $val = $conn->query('select 1 from `user` LIMIT 1');
        if($val !== FALSE){
            echo "Table already exists.\nRecreating table user...\n";
            $sql = $conn->query("DROP TABLE `user`");
        }
    }

    //creates table user
    function CreateTable($options){
        $conn = LoginCheck($options);
        mysqli_select_db($conn,$options['d']);
        CheckIfTableExists($conn);
        $sql = "CREATE TABLE user (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(30) NOT NULL,
            surname VARCHAR(30) NOT NULL,
            email VARCHAR(50) UNIQUE NOT NULL,
            reg_date TIMESTAMP
        )";
        if ($conn->query($sql) === TRUE) {
            echo "Table user created successfully!\n";
        } 
        else {
            echo "Error creating table.\nCheck if the database '". $options['d'] ."' exists and try again.\n ";
        }
        exit;
    }

    //checks if connection to the db works, gets data from the parseCVS function,
    //and calls the add to table function
    function FileParsing($options){
        $conn = LoginCheck($options); 
        $data = ParseCSV($options['file']);
        AddToTable($data, $conn, $options);
    }

    //opens the csv file and returns the parsed data
    function ParseCSV($location){
        if (!file_exists($location) ) {
            throw new Exception('File not found.');
            exit;
        }
        $csvFile = file($location);
        $data = [];
        foreach ($csvFile as $line) {
            $data[] = str_getcsv($line);
        }
        return $data;
    }


    //calls the validity functions and adds the parsed data to the table
    function AddToTable($data, $conn, $options){
        for($i = 1; $i < sizeof($data); $i++){
            if(ValidateEmail($data[$i][2])){
                    $name = NormalizeNames($data[$i][0]);
                    $surname = NormalizeNames($data[$i][1]);
                    $email = strtolower($data[$i][2]);
                    //if dry_run hasn't been set, sends data to database
                    if(!isset($options['dry_run'])){
                        $conn->query("INSERT INTO user(name,surname,email) 
                        VALUES( '". $name. "','".$surname."','".$email."')");            
                    }
                }
            else{
                print "email " . $data[$i][2] . " is invalid\n";
            }   
        }
    }


    //sets names and surnames to lower case and then the first letter to upper case
    //doing this to avoid giant regex functions with a lot of ifs
    function NormalizeNames($name){
        $name = strtolower($name);
        $name = ucwords($name);
        return $name;
    }

    //returns true when email is valid
    function ValidateEmail($email){
        if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
       }
       else{
           return false;
       }
    }

    /*prints detailed info about the argument usage*/
    function help(){
        print "--file [csv file name] – this is the name of the CSV to be parsed; \n";
        print "--create_table – this will cause the MySQL user table to be built (and no further action will be taken)  \n";
        print "--dry_run – this will be used with the --file directive in the instance that we want to run the script but not insert into the DB. All other functions will be executed, but the database won't
        be altered \n";
        print "-u – MySQL username \n";
        print "-p – MySQL password \n";
        print "-h – MySQL host \n";
        print "-d – MySQL database \n";
    }
?>