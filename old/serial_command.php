<?php
    /*
        This was used to send order to Arduino board over serial cable before we get an Ethernet shield.
        Please, note that this works only on GNU/Linux distros and not on Windows.
    */
    function doorControl($ORDER){
        $fp = fopen("/dev/ttyACM0", "wb+"); //Opens the connection. //You might have to modify the path depending on your Linux distro, note that we use a Debian 11 server.
        if(!$fp){ //If connection fails for some reason...
            echo "Erreur : impossible de communiquer avec la carte Arduino !"; //...display an error message.
        }
        else{
            fwrite($fp, $ORDER); //Sends the order to the Arduino board.
            fclose($fp); //Closes the connection.
        };
    };
?>