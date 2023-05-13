<!DOCTYPE html>
<html lang="fr">
    <head>
        <!--Character encoding type declaration.-->
        <meta charset="utf-8">

        <!--Style sheets.-->
        <script src="https://cdn.tailwindcss.com"></script>

        <!--JS scripts.-->
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>

        <!--PHP scripts.-->
        <?php
            include('server.php');
            if(isset($_SESSION['user'])){$user = $_SESSION['user'];}
            else{header("Location: index.php");};
            if($user->getPrivilegeLevel() < 3){header("Location: main.php");};
        ?>

        <!--Others.-->
        <title>Ajout d'un patient</title>
        <link rel="icon" type="image/x-icon" href="../images/favicon.png"> <!--Favicon.-->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!--Permet l'adaptation de la page et la disposition de ses éléments à tous les terminaux.-->
    </head>
    <body class="bg-gray-200 flex justify-center items-center h-screen" style="margin-top:100px; margin-bottom: 100px;">
        <div class="bg-white rounded-xl p-8 rounded shadow-md">
            <h1 class="text-2xl font-bold mb-6">Ajout d'un patient</h1>
            <form action="server.php" method="post">
                <div class="mb-6">
                    <label class="block font-bold mb-2" for="new_patient_name">Prénom</label>
                    <input
                        class="block border border-gray-400 p-2 w-full"
                        type="text"
                        name="new_patient_name"
                        maxlength="42"
                        required
                    />
                </div>
                <div class="mb-6">
                    <label class="block font-bold mb-2" for="new_patient_last_name">Nom</label>
                    <input
                        class="block border border-gray-400 p-2 w-full"
                        type="text"
                        name="new_patient_last_name"
                        maxlength="42"
                        required
                    />
                </div>
                <div class="mb-6">
                    <label class="block font-bold mb-2" for="new_patient_number">Téléphone</label>
                    <input
                        class="block border border-gray-400 p-2 w-full"
                        type="tel"
                        name="new_patient_number"
                        pattern="(01|02|03|04|05|06|07|08|09)[ \.\-]?[0-9]{2}[ \.\-]?[0-9]{2}[ \.\-]?[0-9]{2}[ \.\-]?[0-9]{2}"
                        maxlength="14"
                        required
                    />
                </div>
                <div class="mb-6">
                    <label class="block font-bold mb-2" for="new_patient_ssn">Numéro sécurité sociale</label>
                    <input
                        class="block border border-gray-400 p-2 w-full"
                        type="tel"
                        name="new_patient_ssn"
                        pattern="[12][ \.\-]?[0-9]{2}[ \.\-]?(0[1-9]|[1][0-2])[ \.\-]?([0-9]{2}|2A|2B)[ \.\-]?[0-9]{3}[ \.\-]?[0-9]{3}[ \.\-]?[0-9]{2}}"
                        maxlength="21"
                        required
                    />
                </div>
                <div class="mb-6">
                    <label class="block font-bold mb-2" for="new_patient_address">Adresse</label>
                    <input
                        class="block border border-gray-400 p-2 w-full"
                        type="text"
                        name="new_patient_address"
                        maxlength="42"
                        required
                    />
                </div>
                <div class="mb-6">
                    <label class="block font-bold mb-2" for="new_patient_town">Ville</label>
                    <input
                        class="block border border-gray-400 p-2 w-full"
                        type="text"
                        name="new_patient_town"
                        maxlength="42"
                        required
                    />
                </div>
                <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" type="submit" name="patient_register">Valider</button>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit" onclick="location.href='patients_manage.php';" name="patient_register_cancel">Annuler</button>
            </form>
        </div>
    </body>
</html>
<?php $conn = null; //Close the connection to the database. ?>