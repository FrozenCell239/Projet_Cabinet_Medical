<?php
    switch(basename($_SERVER['PHP_SELF'])){
        case 'test.php':{
            ?>
            test 1
            <?php
            break;
        };
        case 'test_manage.php':{
            ?>
            test 2
            <?php
            break;
        };
    };
    if(strpos(basename($_SERVER['PHP_SELF']), "manage") !== false){
        echo "<b>manage</b><br>";
    };
?>