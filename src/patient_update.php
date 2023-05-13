<!DOCTYPE html>
<html lang="fr">
    <head>
        <!--Character encoding type declaration.-->
        <meta charset="utf-8">

        <!--Style sheets.-->
        <script src="https://cdn.tailwindcss.com"></script>

        <!--JS scripts.-->
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
        <script>
            $(function(){ //Handling patient deletion.
                $('.remove').click(function(){
                    var id = $(this).closest('button').attr('id');
                    if(confirm("Êtes-vous sûr(e) de vouloir retirer ce patient de la liste ? Cette action est irréversible et n'engage aucune autre responsabilité que la vôtre.")){
                        $.ajax({
                            url: 'delete.php?what=3&id=' + id,
                            type: 'GET',
                            success: function(data){
                                alert(data);
                                window.location.replace("patients_manage.php");
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
            if(!isset($_SESSION['u_patient_id'])){header("Refresh: 0; url=patients_manage.php");};
            if(isset($_SESSION['user'])){$user = $_SESSION['user'];}
            else{header("Location: index.php");};
            if($user->getPrivilegeLevel() < 3){header("Location: main.php");};
            $patient_info = $conn->prepare("SELECT prenom_patient, nom_patient, numero_patient, numero_securite_sociale, adresse_patient, ville_patient FROM patients WHERE id_patient=?;");
            $patient_info->execute([$_SESSION['u_patient_id']]);
            $patient_info_row = $patient_info->fetch();
        ?>

        <!--Others.-->
        <title>Mise à jour informations patient</title>
        <link rel="icon" type="image/x-icon" href="../images/favicon.png"> <!--Favicon.-->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!--Permet l'adaptation de la page et la disposition de ses éléments à tous les terminaux.-->
    </head>
    <body class="bg-gray-200 flex justify-center items-center h-screen">
        <div class="bg-white rounded-xl p-8 rounded shadow-md">
            <h1 class="text-2xl font-bold mb-6">Mise à jour informations patient</h1>
            <form action="server.php" method="post">
                <div class="mb-6">
                    <label class="block font-bold mb-2" for="u_patient_name">Prénom</label>
                    <input
                        class="block border border-gray-400 p-2 w-full"
                        type="text"
                        name="u_patient_name"
                        maxlength="42"
                        value="<?= $patient_info_row['prenom_patient']; ?>"
                        required
                    />
                </div>
                <div class="mb-6">
                    <label class="block font-bold mb-2" for="u_patient_last_name">Nom</label>
                    <input
                        class="block border border-gray-400 p-2 w-full"
                        type="text"
                        name="u_patient_last_name"
                        maxlength="42"
                        value="<?= $patient_info_row['nom_patient']; ?>"
                        required
                    />
                </div>
                <div class="mb-6">
                    <label class="block font-bold mb-2" for="u_patient_number">Téléphone</label>
                    <input
                        class="block border border-gray-400 p-2 w-full"
                        type="tel"
                        name="u_patient_number"
                        pattern="(01|02|03|04|05|06|07|08|09)[ \.\-]?[0-9]{2}[ \.\-]?[0-9]{2}[ \.\-]?[0-9]{2}[ \.\-]?[0-9]{2}"
                        maxlength="14"
                        value="<?= $patient_info_row['numero_patient']; ?>"
                        required
                    />
                </div>
                <div class="mb-6">
                    <label class="block font-bold mb-2" for="u_patient_ssn">Numéro sécurité sociale</label>
                    <input
                        class="block border border-gray-400 p-2 w-full"
                        type="tel"
                        name="u_patient_ssn"
                        pattern="[12][ \.\-]?[0-9]{2}[ \.\-]?(0[1-9]|[1][0-2])[ \.\-]?([0-9]{2}|2A|2B)[ \.\-]?[0-9]{3}[ \.\-]?[0-9]{3}[ \.\-]?[0-9]{2}}"
                        maxlength="21"
                        value="<?= $patient_info_row['numero_securite_sociale']; ?>"
                        required
                    />
                </div>
                <div class="mb-6">
                    <label class="block font-bold mb-2" for="u_patient_address">Adresse</label>
                    <input
                        class="block border border-gray-400 p-2 w-full"
                        type="text"
                        name="u_patient_address"
                        maxlength="42"
                        value="<?= $patient_info_row['adresse_patient']; ?>"
                        required
                    />
                </div>
                <div class="mb-6">
                    <label class="block font-bold mb-2" for="u_patient_town">Ville</label>
                    <input
                        class="block border border-gray-400 p-2 w-full"
                        type="text"
                        name="u_patient_town"
                        maxlength="42"
                        value="<?= $patient_info_row['ville_patient']; ?>"
                        required
                    />
                </div>
                <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" type="submit" name="patient_update">Valider</button>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit" onclick="location.href='patients_manage.php';" name="patient_update_cancel">Annuler</button>
                
            </form><button id="<?= $_SESSION['u_patient_id']; ?>" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded remove" type="submit" name="patient_delete" style="float: right;">Supprimer</button>
        </div>
    </body>
</html>
<?php $conn = null; //Close the connection to the database. ?>