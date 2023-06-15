<?php include("header.php"); ?>
<main>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 mt-4">
                <h1>Connecté(e) en tant que <b><?= $user->getFullName()."</b>, poste ".$user->getProfession(); ?>.</h1>
                <hr>
                <?php if($user->getPrivilegeLevel() >= 2){ //Interface propre aux administrateurs. ?>
                <h2>Caméras de surveillance</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-4">
                <div class="card" style="width: 18rem;">
                    <img src="./../img/bliss.png" class="card-img-top" alt="Caméra interphone">
                    <div class="card-body">
                        <h5 class="card-title">Interphone</h5>
                        <form action="main.php" method="post">
                            <button class="btn btn-primary" type="submit" name="door_unlock">Déverrouiller la porte</button>
                        </form>
                        <form action="main.php" method="post">
                            <button class="btn btn-primary" type="submit" name="door_open">Ouvrir la porte</button>
                        </form>
                    </div>
                </div>
                
            </div>
            <div class="col-md-4 col-sm-4 col-xs-4">
                <div class="card" style="width: 18rem;">
                    <img src="./../img/bliss.png" class="card-img-top" alt="Caméra salle d'attente">
                    <div class="card-body">
                        <h5 class="card-title">Salle d'attente</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-4">
                <div class="card" style="width: 18rem;">
                    <img src="./../img/bliss.png" class="card-img-top" alt="Caméra couloir">
                    <div class="card-body">
                        <h5 class="card-title">Couloir</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <?php
                    };
                    if(isset($_POST['door_unlock'])){doorControl('%');};
                    if(isset($_POST['door_open'])){doorControl('#');};
                    $_POST = array();
                    if($user->getPrivilegeLevel() == 2){echo "<hr>";};
                    if($user->getPrivilegeLevel() == 1 || $user->getPrivilegeLevel() == 2){ //Interface propre aux médecins. ?>
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
                                $rdv_query = $conn->prepare("
                                    SELECT id_reservation, prenom_patient, nom_patient, besoin, nom_salle, date_heure
                                    FROM reservations
                                    INNER JOIN personnel
                                    ON reservations.id_personnel = personnel.id_personnel
                                    INNER JOIN patients
                                    ON reservations.id_patient = patients.id_patient
                                    INNER JOIN salles
                                    ON reservations.id_salle = salles.id_salle
                                    WHERE date_heure > NOW() AND personnel.id_personnel = ?
                                    ORDER BY reservations.date_heure ASC
                                ;");
                                $rdv_query->execute([$user->getID()]);
                                while($rdv_row = $rdv_query->fetch()){
                                    echo(
                                        '<tr id="'.$rdv_row['id_reservation'].'">'.
                                        "<td>".$rdv_row['prenom_patient']." ".$rdv_row['nom_patient']."</td>".
                                        "<td>".$rdv_row['besoin']."</td>".
                                        "<td>".$rdv_row['nom_salle']."</td>".
                                        "<td>".$rdv_row['date_heure']."</td>".
                                        "</tr>"
                                    );
                                };
                                unset($rdv_row);
                            ?>
                        </tbody>
                    </table>
                <?php }; ?>
            </div>
        </div>
    </div>
</main>
<?php include("footer.php"); ?>