<?php require_once("header.php"); ?>
<main>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="mt-5 mb-3 d-flex justify-content-between">
                    <h2 class="pull-left">Liste du personnel du cabinet</h2>
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
                            $intern_staff_list_query = $conn->prepare("
                                SELECT
                                    id_personnel,
                                    prenom_personnel,
                                    nom_personnel,
                                    identifiant,
                                    profession,
                                    mail,
                                    niveau_privilege
                                FROM personnel
                                WHERE niveau_privilege > 0
                            ;");
                            $doctor_rdv_query = $conn->prepare("
                                SELECT 
                                    id_reservation,
                                    prenom_patient,
                                    nom_patient,
                                    besoin,
                                    nom_salle,
                                    date_heure
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
                            $intern_staff_list_query->execute();
                            while($row = $intern_staff_list_query->fetch()){
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
                <div class="mt-5 mb-3 d-flex justify-content-between">
                    <h2 class="pull-left">Liste du personnel externe</h2>
                    <button class="btn" onclick='location.href="staff_add.php";'>Ajouter</button>
                </div>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Prénom</th>
                            <th>Nom</th>
                            <th>Profession</th>
                            <th>Mail</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $extern_staff_list_query = $conn->prepare("
                                SELECT
                                    id_personnel,
                                    prenom_personnel,
                                    nom_personnel,
                                    identifiant,
                                    profession,
                                    mail
                                FROM personnel
                                WHERE niveau_privilege = 0
                            ;");
                            $extern_staff_list_query->execute();
                            while($row = $extern_staff_list_query->fetch()){
                                echo(
                                    '<tr id="'.$row['id_personnel'].'">'.
                                    "<td>".$row['prenom_personnel']."</td>".
                                    "<td>".$row['nom_personnel']."</td>".
                                    "<td>".ucfirst($row['profession'])."</td>".
                                    "<td>".$row['mail']."</td>".
                                    "<td>".
                                    '<a class="btn btn-info btn-sm" href="staff_update.php?stfid_u='.$row['id_personnel'].'">Modifier</a>'.
                                    "</td>".
                                    "</tr>"
                                );
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