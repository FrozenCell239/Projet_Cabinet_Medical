<?php require_once("header.php"); ?>
<main>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="mt-5 mb-3 d-flex justify-content-between">
                    <h2 class="pull-left">Liste des patients</h2>
                    <button class="btn" onclick='location.href="patient_add.php";'>Ajouter</button>
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
            </div>
        </div>
    </div>
</main>
<?php require_once("footer.php"); ?>