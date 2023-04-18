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
        <script> //Handling staff member deletion.
            $(function(){
                $('.remove').click(function(){
                    var id = $(this).closest('tr').attr('id');
                    if(confirm("Êtes-vous sûr(e) de vouloir retirer ce personnel de la liste ? Cette action est irréversible et n'engage aucune autre responsabilité que la vôtre.")){
                        $.ajax({
                            url: 'delete.php?what=1&id=' + id,
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
            if($_SESSION['admin'] == 0 || !isset($_SESSION['profession'])){header("Location: index.php");};
        ?>

        <!--Others.-->
        <title>Gestion du personnel</title>
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
                            <h2 class="pull-left">Liste du personnel interne</h2>
                            <button class="btn" data-bs-toggle="collapse" data-bs-target="#add_form"><i class="bi bi-plus"></i> Ajouter</button>
                        </div>
                        <div id="add_form" class="collapse">
                            <hr>
                            <form action="staff_manage.php" method="post">
                                <h3>> Ajouter un personnel</h3>
                                <input type="text" name="new_staff_name" placeholder="Prénom" required/><br>
                                <input type="text" name="new_staff_last_name" placeholder="Nom" required/><br>
                                <input type="text" name="new_staff_profession" placeholder="Profession" required/><br>
                                <input type="text" name="new_staff_user_login" placeholder="Identifiant" required/><br>
                                <input type="password" name="new_staff_password" placeholder="Mot de passe" required/><br>
                                <input type="password" name="new_staff_confirm_password" placeholder="Confirmation du mot de passe" required/><br>
                                <input type="checkbox" name="new_staff_admin"> Administrateur<br>
                                <button type="submit" name="staff_register">Ajouter</button>
                            </form>
                            <hr>
                        </div>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Prénom</th>
                                    <th>Nom</th>
                                    <th>Identifiant</th>
                                    <th>Profession</th>
                                    <th>Administrateur</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $staff_list_query = $conn->prepare("SELECT id_personnel, prenom_personnel, nom_personnel, identifiant, profession, admin FROM personnel");
                                    $doctor_rdv_query = $conn->prepare("
                                        SELECT id_reservation, prenom_patient, nom_patient, besoin, nom_salle, date_heure
                                        FROM reservations
                                        INNER JOIN patients
                                        ON reservations.id_patient = patients.id_patient
                                        INNER JOIN personnel
                                        ON reservations.id_personnel = personnel.id_personnel
                                        INNER JOIN salles
                                        ON reservations.id_salle = salles.id_salle
                                        WHERE date_heure > NOW() AND personnel.id_personnel = ?
                                        ORDER BY reservations.date_heure ASC
                                    ;");
                                    $staff_list_query->execute();
                                    while($row = $staff_list_query->fetch()){
                                        if($row['admin']){$is_admin = "Oui";}
                                        else{$is_admin = "Non";};
                                        echo(
                                            '<tr id="'.$row['id_personnel'].'">'.
                                            "<td>".$row['prenom_personnel']."</td>".
                                            "<td>".$row['nom_personnel']."</td>".
                                            "<td>".$row['identifiant']."</td>".
                                            "<td>".$row['profession']."</td>".
                                            "<td>$is_admin</td>".
                                            "<td>".
                                            '<button class="btn btn-danger btn-sm remove">Supprimer</button>'.
                                            "</td>".
                                            "</tr>".
                                            "<tr>".
                                            "<td colspan='6'>"
                                        );
                                        $doctor_rdv_query->execute([$row['id_personnel']]);
                                        if($doctor_rdv_query->rowCount() == 0){echo "Aucun rendez-vous à venir.";}
                                        elseif($doctor_rdv_query->rowCount() == 1){echo "Prochain rendez-vous :<br>";}
                                        else{echo "Prochains rendez-vous :<br>";};
                                        while($rdv_row = $doctor_rdv_query->fetch()){
                                            echo(
                                                "<i>- Le ".$rdv_row['date_heure'].", avec ".
                                                $rdv_row['prenom_patient']." ".$rdv_row['nom_patient'].
                                                " dans la ".lcfirst($rdv_row['nom_salle']).". Besoin. : ".lcfirst($rdv_row['besoin']).
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
                        <hr>
                    </div>
                </div>
            </div>
        </main>
        <footer>
            
        </footer>
    </body>
</html>
<?php $conn = null; //Close the connection to the database. ?>