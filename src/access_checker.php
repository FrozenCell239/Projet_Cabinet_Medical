<?php
    include('server.php');

    if(isset($_GET['dc'])){ //Determines the SQL query for checking in case a doorcode is typed.
        $value = sha1($_GET['dc']);
        $check_query = $conn->prepare("SELECT mdp_code FROM code_visiophone WHERE mdp_code=?;");
    };
    if(isset($_GET['rt'])){ //Determines the SQL query for checking in case a tag is detected.
        $value = base64_encode($_GET['rt']);
        $check_query = $conn->prepare("SELECT id_badge, mdp_badge, actif FROM badges_visiophone WHERE mdp_badge=?;");
    };
    if(!isset($check_query)){ //Happens when no query is prepared because someone tried to access this page manually.
        header("Location: main.php");
    };
    $check_query->execute([$value]); //Executes the SQL query.
    $row = $check_query->fetch(); //Checking if the doorcode/tag received from Arduino exists in the database.
    if(!isset($row['mdp_code']) && !isset($row['mdp_badge'])){ //If doorcode/tag was wrong.
        if(isset($_GET['rt'])){enlog("Warning : ".$_GET['rt']." is a wrong tag !\n", true);}; //...then logs an error message with the tag number.
        if(isset($_GET['dc'])){enlog("Warning : ".$_GET['dc']." is a wrong doorcode !\n", true);};  //...then logs an error message with the typed doorcode.
    };
    if(isset($row['mdp_badge'])){ //If the tag detected exists in the database.
        if($row['actif'] == 0){ //If the detected tag is deactivated...
            enlog("Warning : the deactivated tag with number ".$_GET['rt']." just have been used !".PHP_EOL, true); //...then logs an error message with the tag number.
        };
        if($row['actif'] == 1){ //If the detected tag is not deactivated...
            enlog("Door opened with badge number ".$_GET['rt'].".".PHP_EOL, true); //...then logs a message with the number tag number...
            echo '$'; //...and send the order to open the door.
        };
    };
    if(isset($row['mdp_code'])){ //If the typed doorcode is right...
        enlog("Door opened with doorcode (".$_GET['dc'].").".PHP_EOL, true); //...then logs a message with the typed doorcode...
        echo '$'; //...and send the order to open the door.
    };
    unset($value); //Unsets the variable that contained the doorcode/tag number received from Arduino.
    unset($check_query); //Unsets the variable that contained the SQL query.
    $conn = null; //Closes the connection to database
?>
