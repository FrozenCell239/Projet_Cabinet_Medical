<?php
    function strikeOpen(){ //Opens only the strike.
        $fp = fopen("/dev/ttyACM0", "wb+");
        if(!$fp){
            echo "Erreur : impossible de communiquer avec la carte Arduino !";
        }
        else{
            fwrite($fp, 'A');
            fclose($fp);
        };
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
?>