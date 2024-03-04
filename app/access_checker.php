<?php
    require_once('./pages/server.php');

    if(isset($_GET['dc'])){ //Determines the SQL query for checking in case a doorcode is typed.
        $value = sha1($_GET['dc']);
        $check_query = $conn->prepare("SELECT prenom_personnel, nom_personnel FROM personnel WHERE code_porte=?;");
    };
    if(isset($_GET['rt'])){ //Determines the SQL query for checking in case a tag is detected.
        $value = base64_encode($_GET['rt']);
        $check_query = $conn->prepare("SELECT prenom_personnel, nom_personnel FROM personnel WHERE numero_badge=?;");
    };
    if(!isset($check_query)){ //Redirects to main page when no query is prepared because someone tried to access this page manually.
        header("Location: main.php");
    };
    $check_query->execute([$value]); //Executes the SQL query.
    $row = $check_query->fetch(); //Checking if the doorcode/tag received from Arduino exists in the database.
    if($row === false){ //If doorcode/tag was wrong...
        if(isset($_GET['rt'])){enlog("Attention : le badge n°".$_GET['rt']." n'est pas un badge répertorié !", true);}; //...then logs an error message with the tag number.
        if(isset($_GET['dc']) && $_GET['dc'] != ''){enlog("Attention : ".$_GET['dc']." n'est pas un code de porte répertorié !", true);};  //...then logs an error message with the typed doorcode.
        if(isset($_GET['dc']) && $_GET['dc'] == ''){enlog("Attention : aucun code de porte n'a été saisi !", true);};  //...then logs an error message stating that sharp key has been pressed while no doorcode has been typed.
    }
    else{ //Else, the doorcode/tag exists.
        if(isset($_GET['rt'])){ //If the tag detected exists in the database...
            enlog("Porte devérrouilée par ".$row['prenom_personnel'].' '.$row['nom_personnel']." depuis l'extérieur avec le badge n°".$_GET['rt'].".", true); //...then logs a message with the number tag number...
        };
        if(isset($_GET['dc'])){ //If the typed doorcode is right...
            enlog("Porte devérrouilée par ".$row['prenom_personnel'].' '.$row['nom_personnel']." depuis l'extérieur avec le code de porte \"".$_GET['dc']."\".", true); //...then logs a message with the typed doorcode...
        };
        echo '%'; //...and send the order to open the door.
    };
    $conn = null; //Closes the connection to database
?>