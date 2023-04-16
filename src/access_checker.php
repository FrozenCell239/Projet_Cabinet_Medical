<?php
    date_default_timezone_set('Europe/Paris');

    function enlog($LOG){ //Push some logs into a text file and to the Arduino board.
        echo "* ".$LOG; //Sends the log to the Arduino board.
        $fp = fopen("log.txt", "a"); //Opens the log text file in "append" mode.
        fwrite($fp, date(DATE_RFC2822)." : ".$LOG); //Pushes the date and time of the log message and its content.
        fclose($fp); //Closes the log text file.
    };

    $conn = new mysqli("localhost:3307", "root", "", "cabinet"); //Connection to the database.

    if($conn->connect_error){ //If the connection to database fails...
        die("Connection failed. : ".$conn->connect_error); //...then display the error...
        enlog("Connection failed."); //...and logs an error message.
    };
    if(isset($_GET['dc'])){ //Determines the SQL query for checking in case a doorcode is typed.
        $value = sha1($_GET['dc']);
        $check_query = "SELECT mdp_code FROM code_visiophone WHERE mdp_code='$value';";
    };
    if(isset($_GET['rt'])){ //Determines the SQL query for checking in case a tag is detected.
        $value = base64_encode($_GET['rt']);
        $check_query = "SELECT id_badge, mdp_badge, actif FROM badges_visiophone WHERE mdp_badge='$value';";
    };
    $check_query_result = mysqli_query($conn, $check_query); //Sends the SQL query to the database.
    $row = mysqli_fetch_array($check_query_result, MYSQLI_ASSOC); //Checking if the doorcode/tag received from Arduino exists in the database.
    if(!isset($row['mdp_code']) && !isset($row['mdp_badge'])){ //If doorcode/tag was wrong.
        if(isset($_GET['rt'])){enlog($_GET['rt']." is a wrong tag !\n");}; //...then logs an error message with the tag number.
        if(isset($_GET['dc'])){enlog($_GET['dc']." is a wrong doorcode !\n");};  //...then logs an error message with the typed doorcode.
    };
    if(isset($row['mdp_badge'])){ //If the tag detected exists in the database.
        if($row['actif'] == 0){ //If the detected tag is deactivated...
            enlog("Warning : the deactivated tag with number ".$_GET['rt']." just have been used !".PHP_EOL); //...then logs an error message with the tag number.
        };
        if($row['actif'] == 1){ //If the detected tag is not deactivated...
            enlog("Door opened with badge number ".$_GET['rt'].".".PHP_EOL); //...then logs a message with the number tag number...
            echo '$'; //...and send the order to open the door.
        };
    };
    if(isset($row['mdp_code'])){ //If the typed doorcode is right...
        enlog("Door opened with doorcode (".$_GET['dc'].").".PHP_EOL); //...then logs a message with the typed doorcode...
        echo '$'; //...and send the order to open the door.
    };
    $_GET = array(); //Resets the array that contained received values.
    unset($value); //Unsets the variable that contained the doorcode/tag number received from Arduino.
    unset($check_query); //Unsets the variable that contained the SQL query.
    mysqli_free_result($check_query_result); //Free the variable that contained the result of the SQL query.
    $conn->close(); //Closes the connection to database
?>