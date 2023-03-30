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
        $value = sha1($_GET['dc']);
        $check_query = "SELECT mdp_code FROM code_visiophone WHERE mdp_code='$value';";
    };
    if(isset($_GET['rt'])){
        $value = sha1($_GET['rt']);
        $check_query = "SELECT id_badge, mdp_badge, actif FROM badges_visiophone WHERE mdp_badge='$value';";
    };
    $check_query_result = mysqli_query($conn, $check_query);
    $row = mysqli_fetch_array($check_query_result, MYSQLI_ASSOC);
    if(!isset($row['mdp_code']) && !isset($row['mdp_badge'])){
        if(isset($_GET['rt'])){enlog($_GET['rt']." is a wrong tag !\n");};
        if(isset($_GET['dc'])){enlog($_GET['dc']." is a wrong doorcode !\n");};
    };
    if(isset($row['mdp_badge'])){
        if($row['actif'] == 0){
            enlog("Warning : the deactivated tag with number ".$_GET['rt']." just have been used !".PHP_EOL);
        };
        if($row['actif'] == 1){
            enlog("Door opened with badge number ".$_GET['rt'].".".PHP_EOL);
            echo '$';
        };
    };
    if(isset($row['mdp_code'])){
        enlog("Door opened with doorcode (".$_GET['dc'].").".PHP_EOL);
        echo '$';
    };
    unset($value);
    unset($check_query);
    unset($check_query_result);
    $conn->close();
?>
