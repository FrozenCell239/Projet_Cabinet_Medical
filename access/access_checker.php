<?php
    date_default_timezone_set('Europe/Paris');

    function enlog($LOG){
        echo "* ".$LOG;
        $fp = fopen("log.txt", "a");
        fwrite($fp, date(DATE_RFC2822)." : ".$LOG);
        fclose($fp);
    };

    $conn = new mysqli("localhost:3307", "root", "", "cabinet");

    if($conn->connect_error){
        die("Connection failed. : ".$conn->connect_error);
        enlog("Connection failed.");
    };
    if(isset($_GET['dc'])){
        $value = $_GET['dc'];
        $check_query = "SELECT mdp_code FROM code_visiophone WHERE mdp_code=SHA1('$value');";
    };
    if(isset($_GET['rt'])){
        $value = $_GET['rt'];
        $check_query = "SELECT mdp_badge, actif FROM badges_visiophone WHERE mdp_badge='$value';";
    };
    $check_query_result = mysqli_query($conn, $check_query);
    $row = mysqli_fetch_array($check_query_result, MYSQLI_ASSOC);
    if(!isset($row['mdp_code']) && !isset($row['mdp_badge'])){
        if(isset($_GET['rt'])){};
        enlog("Wrong doorcode or tag !\n");
    };
    if(isset($row['mdp_badge'])){
        if($row['actif'] == 0){
            enlog("Warning : a deactivated tag just have been used !".PHP_EOL);
        };
        if($row['actif'] == 1){
            enlog("Door opened with badge number ".$row['mdp_badge'].".".PHP_EOL);
            echo '$';
        };
    };
    if(isset($row['mdp_code'])){
        enlog("Door opened with doorcode (".$row['mdp_code'].").".PHP_EOL);
        echo '$';
    };
    unset($value);
    unset($check_query);
    unset($check_query_result);
    $conn->close();
?>
