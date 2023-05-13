<html lang="fr">
    <head>
        <!--Character encoding type declaration.-->
        <meta charset="utf-8">

        <!--Style sheets.-->
        <link rel="stylesheet" href="global.css"> <!--Customised style sheet.-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous"> <!--Bootstrap 5.2.3.-->

        <!--JS scripts.-->
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
        <script> //Handling patient deletion.
            $(function(){
                $('.remove').click(function(){
                    var id = $(this).closest('tr').attr('id');
                    if(confirm("Êtes-vous sûr(e) de vouloir retirer ce patient de la liste ? Cette action est irréversible et n'engage aucune autre responsabilité que la vôtre.")){
                        $.ajax({
                            url: 'delete.php?what=3&id=' + id,
                            type: 'GET',
                            success: function(data){
                                alert(data);
                                location.reload();
                            },
                            error: function(jqXHR, textStatus, errorThrown){
                                alert('Error: ' + textStatus + ' - ' + errorThrown);
                            }
                        });
                    };
                });
            });
        </script>

        <!--PHP scripts.-->
        <?php
            include('server.php');
            if(isset($_SESSION['user'])){$user = $_SESSION['user'];}
            else{header("Location: index.php");};
            if($user->getPrivilegeLevel() < 3){header("Location: main.php");};
        ?>

        <!--Others.-->
        <title>Gestion des patients</title>
        <link rel="icon" type="image/x-icon" href="../images/favicon.png"> <!--Favicon.-->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!--Permet l'adaptation de la page et la disposition de ses éléments à tous les terminaux.-->
    </head>
    <body>
        <header>
            <?= $navbar; ?>
        </header>
        <main>
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="mt-5 mb-3 d-flex justify-content-between">
                            <h2 class="pull-left">Liste des patients</h2>
                            <button class="btn" onclick='location.href="patient_add.php"'>Ajouter</button>
                        </div>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Prénom</th>
                                    <th>Nom</th>
                                    <th>Téléphone</th>
                                    <th>Numéro sécurité sociale</th>
                                    <th>Adresse</th>
                                    <th>Ville</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $patient_list_query = $conn->prepare("SELECT * FROM patients ORDER BY nom_patient ASC;");
                                    $patient_rdv_query = $conn->prepare("
                                        SELECT id_reservation, besoin, nom_personnel, prenom_personnel, nom_salle, date_heure
                                        FROM reservations
                                        INNER JOIN patients
                                        ON reservations.id_patient = patients.id_patient
                                        INNER JOIN personnel
                                        ON reservations.id_personnel = personnel.id_personnel
                                        INNER JOIN salles
                                        ON reservations.id_salle = salles.id_salle
                                        WHERE date_heure > NOW() AND patients.id_patient = ?
                                        ORDER BY reservations.date_heure ASC
                                    ;");
                                    $patient_list_query->execute();
                                    while($row = $patient_list_query->fetch()){
                                        echo(
                                            "<tr id='".$row['id_patient']."'>".
                                            "<td>".$row['prenom_patient']."</td>".
                                            "<td>".$row['nom_patient']."</td>".
                                            "<td>".$row['numero_patient']."</td>".
                                            "<td>".$row['numero_securite_sociale']."</td>".
                                            "<td>".$row['adresse_patient']."</td>".
                                            "<td>".$row['ville_patient']."</td>".
                                            "<td>".
                                            '<a class="btn btn-info btn-sm" href="patient_update.php?ptid_u='.$row['id_patient'].'">Modifier</a>'.
                                            "</td>".
                                            "</tr>".
                                            "<tr>".
                                            "<td colspan='7'>"
                                        );
                                        $patient_rdv_query->execute([$row['id_patient']]);
                                        if($patient_rdv_query->rowCount() == 0){echo "Aucun rendez-vous à venir.";}
                                        elseif($patient_rdv_query->rowCount() == 1){echo "Prochain rendez-vous :<br>";}
                                        else{echo "Prochains rendez-vous :<br>";};
                                        while($rdv_row = $patient_rdv_query->fetch()){
                                            echo(
                                                "<i>- Le ".$rdv_row['date_heure'].", avec ".
                                                $rdv_row['prenom_personnel']." ".$rdv_row['nom_personnel'].
                                                " dans la ".lcfirst($rdv_row['nom_salle']).". Besoin : ".lcfirst($rdv_row['besoin']).
                                                "</i><br>"
                                            );
                                        };
                                        echo "</td></tr>";
                                    };
                                    unset($row);
                                    unset($rdv_row);
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
                    </div>
                </div>
            </div>
        </main>
        <footer>
            
        </footer>
    </body>
</html>
<?php $conn = null; //Close the connection to the database. ?>