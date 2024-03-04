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
    if(strpos(basename($_SERVER['PHP_SELF']), "manage")!== false){
        echo "<b>manage</b><br>";
    }
    else{
        echo "<b>nope</b><br>";
    };
?>
<br>
<?= basename($_SERVER['PHP_SELF'])."<br>"; ?>
<?= $_SERVER['PHP_SELF']."<br>"; ?>
<?= $_SERVER['DOCUMENT_ROOT']."<br>"; ?>
<?= basename(__DIR__); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test</title>
</head>
<body>
    <form action="" method="post">
<input type="radio" name="new_staff_access_type" value="doorcode"> Option 1
<input type="radio" name="new_staff_access_type" value="tag"> Option 2

<input type="text" name="new_staff_access_code" disabled>

<script>
    // Get references to the radio button and text input
    var access_type = document.getElementsByName("new_staff_access_type");
    var code_input = document.getElementsByName("new_staff_access_code")[0];
    var doorcode_attributes = {
        "placeholder" : "Example 1",
        "maxlength" : "8",
        "pattern" : "[A-D0-9*]{4,8}",
        "oninvalid" : "setCustomValidity('4 à 8 caractères. Les caracèters autorisés sont 0 à 9, A à D, et *.')",
        "oninput" : "setCustomValidity('')"
    };
    var tag_attributes = {
        "placeholder": "Example 2",
        "maxlength" : "12",
        "pattern" : "[1-9]",
        "oninvalid" : "setCustomValidity('12 caractères maximum allant de 1 à 9. Ne pas noter les zéros et les espaces.')",
        "oninput" : "setCustomValidity('')"
    };

    for(var i = 0; i < access_type.length; i++){
        access_type[i].addEventListener("change", function(){
            code_input.removeAttribute("disabled");
            if(this.value === "doorcode"){
                for(var attr in doorcode_attributes){
                    code_input.setAttribute(attr, doorcode_attributes[attr]);
                };
            }
            else{
                for(var attr in tag_attributes){
                    code_input.setAttribute(attr, tag_attributes[attr]);
                };
                code_input.removeAttribute("disabled");
            };
        });
    };
</script>
</form>
</body>
</html> */
$x = 12;
$list = [
    1,
    2,
    ($x == 1) ? 3 : 'F',
    4
];
var_dump($list);
//$list[(count($list) - 1)] = 'F';
//var_dump($list);
?>