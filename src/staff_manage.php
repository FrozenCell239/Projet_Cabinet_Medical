<?php include("header.php"); ?>
<main>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="mt-5 mb-3 d-flex justify-content-between">
                    <h2 class="pull-left">Liste du personnel</h2>
                    <button class="btn" onclick='location.href="staff_add.php";'>Ajouter</button>
                </div>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Prénom</th>
                            <th>Nom</th>
                            <th>Identifiant</th>
                            <th>Profession</th>
                            <th>Mail</th>
                            <th>Catégorie de personnel</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $staff_list_query = $conn->prepare("SELECT id_personnel, prenom_personnel, nom_personnel, identifiant, profession, mail, niveau_privilege FROM personnel");
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
                                echo(
                                    '<tr id="'.$row['id_personnel'].'">'.
                                    "<td>".$row['prenom_personnel']."</td>".
                                    "<td>".$row['nom_personnel']."</td>".
                                    "<td>".$row['identifiant']."</td>".
                                    "<td>".ucfirst($row['profession'])."</td>".
                                    "<td>".$row['mail']."</td>".
                                    "<td>".$privilege_levels[$row['niveau_privilege']]."</td>".
                                    "<td>".
                                    '<a class="btn btn-info btn-sm" href="staff_update.php?stfid_u='.$row['id_personnel'].'">Modifier</a>'.
                                    "</td>".
                                    "</tr>".
                                    "<tr>".
                                    "<td colspan='7'>"
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
            </div>
        </div>
    </div>
</main>
<?php include("footer.php"); ?>