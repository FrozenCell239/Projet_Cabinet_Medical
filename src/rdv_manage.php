<html lang="fr">
    <head>
        <!--Character encoding type declaration.-->
        <meta charset="utf-8">

        <!--Style sheets.-->
        <link rel="stylesheet" href="global.css"> <!--Customised style sheet.-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous"> <!--Bootstrap 5.2.3.-->
        <style>
            .wrapper{
                width: 700px;
                margin: 0 auto;
            }
            table tr td:last-child{width: 120px;}
        </style>

        <!--JS scripts.-->
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
        <script>
            var today = new Date().toISOString().slice(0, 16);

            document.getElementsByName("rdv_datetime")[0].min = today;
        </script>

        <!--PHP scripts.-->
        <?php
            include('server.php');
            if($_SESSION['admin'] == 0 || !isset($_SESSION['profession'])){header("Location: index.php");};
        ?>

        <!--Others.-->
        <title>Gestion des rendez-vous</title>
        <link rel="icon" type="image/x-icon" href="../images/favicon.png"> <!--Favicon.-->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!--Permet l'adaptation de la page et la disposition de ses éléments à tous les terminaux.-->
    </head>
    <body>
        <header>
            <?php echo $navbar; ?>
        </header>
        <main>
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="mt-5 mb-3 d-flex justify-content-between">
                            <h2 class="pull-left">Rendez-vous à venir</h2>
                            <button class="btn" data-bs-toggle="collapse" data-bs-target="#add_form">Ajouter</button>
                        </div>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Besoin</th>
                                    <th>Médecin</th>
                                    <th>Salle</th>
                                    <th>Date et heure</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $rdv_query = "
                                        SELECT id_reservation, prenom_patient, nom_patient, besoin, nom_personnel, prenom_personnel, nom_salle, date_heure
                                        FROM reservations
                                        INNER JOIN patients
                                        ON reservations.id_patient = patients.id_patient
                                        INNER JOIN personnel
                                        ON reservations.id_personnel = personnel.id_personnel
                                        INNER JOIN salles
                                        ON reservations.id_salle = salles.id_salle
                                        WHERE date_heure > NOW()
                                        ORDER BY reservations.date_heure ASC
                                    ;";
                                    $rdv_query_result = mysqli_query($conn, $rdv_query);

                                    while($rdv_row = mysqli_fetch_array($rdv_query_result)){
                                        echo(
                                            '<tr id="'.$rdv_row['id_reservation'].'">'.
                                            "<td>".$rdv_row['prenom_patient']." ".$rdv_row['nom_patient']."</td>".
                                            "<td>".$rdv_row['besoin']."</td>".
                                            "<td>".$rdv_row['prenom_personnel']." ".$rdv_row['nom_personnel']."</td>".
                                            "<td>".$rdv_row['nom_salle']."</td>".
                                            "<td>".$rdv_row['date_heure']."</td>".
                                            "<td>".
                                            '<button class="btn btn-danger btn-sm remove">Action</button>'.
                                            "</td>".
                                            "</tr>"
                                        );
                                    };
                                    mysqli_free_result($rdv_query_result); //Free result set.
                                ?>
                            </tbody>
                        </table>
                        <!--div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Avertissement</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Êtes-vous sûr(e) de vouloir supprimer cet utilisateur ? Cette action est irréversible et n\'engage aucune autre responsabilité que la vôtre.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <button type="button" class="btn btn-primary">Confirmer</button>
                                    </div>
                                </div>
                            </div>
                        </div-->
                        <hr>
                        <div id="add_form" class="collapse">
                            <form action="rdv_manage.php" method="post">
                                <h2>Créer un rendez-vous</h2>
                                <input type="text" name="patient_name" placeholder="Prénom patient" required/><br>
                                <input type="text" name="patient_last_name" placeholder="Nom patient" required/><br>
                                <input type="text" name="patient_need" placeholder="Besoin patient" required/><br>
                                <select name="doctor_select" required>
                                    <option value="">-- Choisir un médecin --</option>
                                    <?php
                                        $doctor_select_query = "SELECT id_personnel, prenom_personnel, nom_personnel, profession FROM personnel;";
                                        $doctor_select_query_result = mysqli_query($conn, $doctor_select_query);
                                        while($doctor_select_row = mysqli_fetch_array($doctor_select_query_result)){
                                            if($doctor_select_row['profession'] != 'secretaire'){
                                                echo(
                                                    '<option value="'.$doctor_select_row['id_personnel'].'">'.
                                                    $doctor_select_row['prenom_personnel']." ".
                                                    $doctor_select_row['nom_personnel']." (".
                                                    $doctor_select_row['profession'].
                                                    ")</option>"
                                                );
                                            };
                                        };
                                    ?>
                                </select><br>
                                <select name="room_select" required>
                                    <option value="">-- Choisir une salle --</option>
                                    <?php
                                        $room_select_query = "SELECT id_salle, nom_salle FROM salles;";
                                        $room_select_query_result = mysqli_query($conn, $room_select_query);
                                        while($room_select_row = mysqli_fetch_array($room_select_query_result)){
                                            echo(
                                                '<option value="'.$room_select_row['id_salle'].'">'.
                                                $room_select_row['nom_salle'].
                                                "</option>"
                                            );
                                        };
                                    ?>
                                </select><br>
                                <input type="datetime-local" name="rdv_datetime" required/><br>
                                <script>
                                    document.getElementsByName("rdv_datetime")[0].setAttribute("min", new Date().toISOString().slice(0, 16));
                                </script>
                                <button type="submit" name="rdv_register">Créer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <footer>
            <?php mysqli_close($conn); //Close the connection to the database. ?>
        </footer>
    </body>
</html>
