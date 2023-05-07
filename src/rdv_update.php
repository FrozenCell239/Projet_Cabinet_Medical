<!DOCTYPE html>
<html lang="fr">
    <head>
        <!--Character encoding type declaration.-->
        <meta charset="utf-8">

        <!--Style sheets.-->
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            input:read-only{
                background-color: #dddddd;
            }
        </style>

        <!--JS scripts.-->
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>

        <!--PHP scripts.-->
        <?php
            include('server.php');
            if(!isset($_SESSION['profession'])){header("Location: index.php");};
            $rdv_info = $conn->prepare("
                SELECT
                    id_reservation,
                    patients.id_patient,
                    personnel.id_personnel,
                    salles.id_salle,
                    prenom_patient,
                    nom_patient,
                    besoin,
                    nom_personnel,
                    prenom_personnel,
                    nom_salle,
                    date_heure
                FROM reservations
                INNER JOIN patients
                ON reservations.id_patient = patients.id_patient
                INNER JOIN personnel
                ON reservations.id_personnel = personnel.id_personnel
                INNER JOIN salles
                ON reservations.id_salle = salles.id_salle
                WHERE id_reservation = ?
            ;");
            $rdv_info->execute([$_SESSION['u_rdv_id']]);
            $rdv_info_row = $rdv_info->fetch();
            $_SESSION['urdv_patient_id'] = $rdv_info_row['id_patient'];
        ?>

        <!--Others.-->
        <title>Mise à jour rendez-vous</title>
        <link rel="icon" type="image/x-icon" href="../images/favicon.png"> <!--Favicon.-->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!--Permet l'adaptation de la page et la disposition de ses éléments à tous les terminaux.-->
    </head>
    <body class="bg-gray-200 flex justify-center items-center h-screen">
        <div style="margin-top: 250px;" class="bg-white rounded-xl p-8 rounded shadow-md">
            <h1 class="text-2xl font-bold mb-6">Mise à jour rendez-vous</h1>
            <form action="server.php" method="post">
                <div class="mb-6">
                    <label class="block font-bold mb-2" for="urdv_patient_name">Prénom</label>
                    <input
                        class="block border border-gray-400 p-2 w-full"
                        type="text"
                        name="urdv_patient_name"
                        value="<?= $rdv_info_row['prenom_patient']; ?>"
                        readonly
                    />
                </div>
                <div class="mb-6">
                    <label class="block font-bold mb-2" for="urdv_patient_last_name">Nom</label>
                    <input
                        class="block border border-gray-400 p-2 w-full"
                        type="text"
                        name="urdv_patient_last_name"
                        value="<?= $rdv_info_row['nom_patient']; ?>"
                        readonly
                    />
                </div>
                <div class="mb-6">
                    <label class="block font-bold mb-2" for="urdv_patient_need">Besoin</label>
                    <input
                        class="block border border-gray-400 p-2 w-full"
                        type="text"
                        name="urdv_patient_need"
                        value="<?= $rdv_info_row['besoin']; ?>"
                        required
                    />
                </div>
                <div class="mb-6">
                    <label class="block font-bold mb-2" for="urdv_room">Salle</label>
                    <select
                        class="block border border-gray-400 p-2 w-full"
                        name="urdv_room"
                        required
                    >
                        <option value="<?= $rdv_info_row['id_salle']; ?>"><?= $rdv_info_row['nom_salle']; ?></option>
                        <?php
                            $room_select_query = $conn->prepare("SELECT id_salle, nom_salle FROM salles WHERE id_salle != ?;");
                            $room_select_query->execute([$rdv_info_row['id_salle']]);
                            while($room_select_row = $room_select_query->fetch()){
                                echo(
                                    '<option value="'.$room_select_row['id_salle'].'">'.
                                    $room_select_row['nom_salle'].
                                    "</option>"
                                );
                            };
                        ?>
                    </select>
                </div>
                <div class="mb-6">
                    <label class="block font-bold mb-2" for="urdv_doctor">Médecin</label>
                    <select
                        class="block border border-gray-400 p-2 w-full"
                        name="urdv_doctor"
                        required
                    >
                        <option value="<?= $rdv_info_row['id_personnel']; ?>"><?= $rdv_info_row['prenom_personnel']." ".$rdv_info_row['nom_personnel']; ?></option>
                        <?php
                            $doctor_select_query = $conn->prepare("SELECT id_personnel, prenom_personnel, nom_personnel FROM personnel WHERE id_personnel != ? AND profession !='secretaire';");
                            $doctor_select_query->execute([$rdv_info_row['id_personnel']]);
                            while($doctor_select_row = $doctor_select_query->fetch()){
                                echo(
                                    '<option value="'.$doctor_select_row['id_personnel'].'">'.
                                    $doctor_select_row['prenom_personnel']." ".$doctor_select_row['nom_personnel'].
                                    "</option>"
                                );
                            };
                        ?>
                    </select>
                </div>
                <div class="mb-6">
                    <label class="block font-bold mb-2" for="urdv_datetime">Date et heure</label>
                    <input
                        class="block border border-gray-400 p-2 w-full"
                        type="datetime-local"
                        name="urdv_datetime"
                        value="<?= $rdv_info_row['date_heure']; ?>"
                        required
                    />
                    <script>
                        document.getElementsByName("urdv_datetime")[0].setAttribute("min", new Date().toISOString().slice(0, 16));
                    </script>
                </div>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit" name="rdv_update">Valider</button>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit" name="rdv_update_cancel">Annuler</button>
            </form>
        </div>
    </body>
</html>
<?php $conn = null; //Close the connection to the database. ?>