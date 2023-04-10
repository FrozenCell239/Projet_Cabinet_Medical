<!DOCTYPE html>
<html lang="fr">
    <head>
        <!--Character encoding type declaration.-->
        <meta charset="utf-8">

        <!--Style sheets.-->
        <link rel="stylesheet" href="global.css"> <!--Customised style sheet.-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous"> <!--Bootstrap 5.2.3.-->
        <!--script src="https://cdn.tailwindcss.com"></script-->

        <!--JS scripts.-->
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

        <!--PHP scripts.-->
        <?php
            include('server.php');
            if(!isset($_SESSION['profession'])){header("Location: index.php");};
            //if($_SESSION['admin'] == 1){include('serial_command.php');}; Currently unused.
            //if($_SESSION['admin'] == 1){include('access/command.php');};
        ?>

        <!--Others.-->
        <title>Tableau de bord</title>
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
                        <h1>Connecté(e) en tant que <b><?php echo $_SESSION['name']." ".$_SESSION['last_name']."</b>, poste ".$_SESSION['profession']; ?>.</h1>
                        <hr>
                        <?php if($_SESSION['admin'] == 1){ //Interface propre à la secrétaire. ?>
                        <form action="main.php" method="post">
                            <button type="submit" name="door_unlock">Déverrouiller la porte</button>
                        </form>
                        <form action="main.php" method="post">
                            <button type="submit" name="door_open">Ouvrir la porte</button>
                        </form>
                        <?php
                            };
                            if(isset($_POST['door_unlock'])){
                                unset($_POST['door_unlock']);
                                //strikeOpen();
                            };
                            if(isset($_POST['door_open'])){
                                unset($_POST['door_open']);
                                //doorOpen();
                            };
                        ?>
                        <?php if($_SESSION['profession'] != 'secretaire'){ //Interface propre aux médecins. ?>
                            <div class="mt-5 mb-3 d-flex justify-content-between">
                                <h2 class="pull-left">Vos prochains rendez-vous</h2>
                            </div>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Patient</th>
                                        <th>Besoin</th>
                                        <th>Salle</th>
                                        <th>Date et heure</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $rdv_query = "
                                            SELECT id_reservation, prenom_patient, nom_patient, besoin, nom_salle, date_heure
                                            FROM reservations
                                            INNER JOIN personnel
                                            ON reservations.id_personnel = personnel.id_personnel
                                            INNER JOIN patients
                                            ON reservations.id_patient = patients.id_patient
                                            INNER JOIN salles
                                            ON reservations.id_salle = salles.id_salle
                                            WHERE personnel.id_personnel = '".$_SESSION['user_id']."'
                                            ORDER BY reservations.date_heure ASC
                                        ;";
                                        $rdv_query_result = mysqli_query($conn, $rdv_query);

                                        while($rdv_row = mysqli_fetch_array($rdv_query_result)){
                                            echo(
                                                '<tr id="'.$rdv_row['id_reservation'].'">'.
                                                "<td>".$rdv_row['prenom_patient']." ".$rdv_row['nom_patient']."</td>".
                                                "<td>".$rdv_row['besoin']."</td>".
                                                "<td>".$rdv_row['nom_salle']."</td>".
                                                "<td>".$rdv_row['date_heure']."</td>".
                                                "</tr>"
                                            );
                                        };
                                        mysqli_free_result($rdv_query_result); //Free result set.
                                    ?>
                                </tbody>
                            </table>
                        <?php }; ?>
                        <hr>
                    </div>
                </div>
            </div>
        </main>
        <footer>
            
        </footer>
    </body>
</html>
