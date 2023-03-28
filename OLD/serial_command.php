<?php
    function strikeOpen(){ //Opens only the strike.
        $url = "http://192.168.1.177/?status='$'"; // IP address of the Arduino board
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_exec($ch);
        curl_close($ch);
        #Peut-être passer là-dedans si le réseau est HS.
        //$fp = fopen("/dev/ttyACM0", "wb+");
        //if(!$fp){
        //    echo "Erreur : impossible de communiquer avec la carte Arduino !";
        //}
        //else{
        //    fwrite($fp, 'A');
        //    fclose($fp);
        //};
    };
    function doorOpen(){ //Opens both the strike and the door.
        $fp = fopen("/dev/ttyACM0", "wb+");
        if(!$fp){
            echo "Erreur : impossible de communiquer avec la carte Arduino !";
        }
        else{
            fwrite($fp, 'B');
            fclose($fp);
        };
    };
    /*function doorControl($ORDER){
        $url = "http://192.168.1.177/?status=".$ORDER; // IP address of the Arduino board
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_exec($ch);
        curl_close($ch);
    };*/
?>