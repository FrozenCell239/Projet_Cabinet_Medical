<?php
    //include("server.php");

    /*$patient_rdv_query = $conn->prepare("
        SELECT id_reservation, besoin, nom_personnel, prenom_personnel, nom_salle, date_heure
        FROM reservations
        INNER JOIN patients
        ON reservations.id_patient = patients.id_patient
        INNER JOIN personnel
        ON reservations.id_personnel = personnel.id_personnel
        INNER JOIN salles
        ON reservations.id_salle = salles.id_salle
        WHERE date_heure > NOW()
        ORDER BY reservations.date_heure ASC
    ;"); // AND patients.id_patient = ?
    $patient_rdv_query->execute();
    while($row = $patient_rdv_query->fetch()){
        var_dump($row);
    };*/
    /*switch(basename($_SERVER['PHP_SELF'])){
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
    }
    else{
        echo "<b>nope</b><br>";
    };*/
?>
<br>
<?= basename($_SERVER['PHP_SELF'])."<br>"; ?>
<?= $_SERVER['PHP_SELF']."<br>"; ?>
<?= $_SERVER['DOCUMENT_ROOT']."<br>"; ?>
<?= basename(__DIR__); ?>