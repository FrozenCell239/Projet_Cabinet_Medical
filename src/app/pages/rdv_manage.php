<?php require_once("header.php"); ?>
<main>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div id="old_rdv_display" class="collapse mt-5">
                    <h3>Anciens rendez-vous</h3>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Besoin</th>
                                <th>Médecin</th>
                                <th>Salle</th>
                                <th>Date et heure</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $old_rdv_query = $conn->prepare("
                                    SELECT id_reservation, prenom_patient, nom_patient, besoin, nom_personnel, prenom_personnel, nom_salle, date_heure
                                    FROM reservations
                                    INNER JOIN patients
                                    ON reservations.id_patient = patients.id_patient
                                    INNER JOIN personnel
                                    ON reservations.id_personnel = personnel.id_personnel
                                    INNER JOIN salles
                                    ON reservations.id_salle = salles.id_salle
                                    WHERE date_heure < NOW()
                                    ORDER BY reservations.date_heure ASC
                                ;");
                                $old_rdv_query->execute();
                                while($old_rdv_row = $old_rdv_query->fetch()){
                                    echo(
                                        '<tr id="'.$old_rdv_row['id_reservation'].'">'.
                                        "<td>".$old_rdv_row['prenom_patient']." ".$old_rdv_row['nom_patient']."</td>".
                                        "<td>".$old_rdv_row['besoin']."</td>".
                                        "<td>".$old_rdv_row['prenom_personnel']." ".$old_rdv_row['nom_personnel']."</td>".
                                        "<td>".$old_rdv_row['nom_salle']."</td>".
                                        "<td>".$old_rdv_row['date_heure']."</td>".
                                        "</tr>"
                                    );
                                };
                                unset($old_rdv_row);
                            ?>
                        </tbody>
                    </table>
                    <hr>
                </div>
                <div class="mt-5 mb-3 d-flex justify-content-between">
                    <h2 class="pull-left">Rendez-vous à venir</h2>
                    <button class="btn" onclick='location.href="rdv_add.php";'>Ajouter</button>
                    <button class="btn" data-bs-toggle="collapse" data-bs-target="#old_rdv_display">Afficher les anciens rendez-vous</button>
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
                            $rdv_query = $conn->prepare("
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
                            ;");
                            $rdv_query->execute();
                            while($rdv_row = $rdv_query->fetch()){
                                echo(
                                    '<tr id="'.$rdv_row['id_reservation'].'">'.
                                    "<td>".$rdv_row['prenom_patient']." ".$rdv_row['nom_patient']."</td>".
                                    "<td>".$rdv_row['besoin']."</td>".
                                    "<td>".$rdv_row['prenom_personnel']." ".$rdv_row['nom_personnel']."</td>".
                                    "<td>".$rdv_row['nom_salle']."</td>".
                                    "<td>".$rdv_row['date_heure']."</td>".
                                    "<td>".
                                    '<a class="btn btn-info btn-sm" href="rdv_update.php?rdvid_u='.$rdv_row['id_reservation'].'">Modifier</a>'.
                                    "</td>".
                                    "</tr>"
                                );
                            };
                            unset($rdv_row);
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<?php require_once("footer.php"); ?>