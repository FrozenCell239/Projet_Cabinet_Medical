<!DOCTYPE html>
<html lang="fr">
    <head>
        <!--Character encoding type declaration.-->
        <meta charset="utf-8">

        <!--Style sheets.-->
        <link rel="stylesheet" href="global.css"> <!--Customised style sheet.-->
        <script src="https://cdn.tailwindcss.com"></script>

        <!--JS scripts.-->
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>

        <!--PHP scripts.-->
        <?php
            include('server.php');
            if(!isset($_SESSION['profession'])){header("Location: index.php");};
            $patient_info = $conn->prepare("SELECT prenom_patient, nom_patient, numero_patient FROM patients WHERE id_patient=?;");
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
                        value="<?php echo $patient_info_row['prenom_patient']; ?>"
                        required
                    />
                </div>
                <div class="mb-6">
                    <label class="block font-bold mb-2" for="u_patient_last_name">Nom</label>
                    <input
                        class="block border border-gray-400 p-2 w-full"
                        type="text"
                        name="u_patient_last_name"
                        value="<?php echo $patient_info_row['nom_patient']; ?>"
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
                        value="<?php echo $patient_info_row['numero_patient']; ?>"
                        required
                    />
                </div>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit" name="patient_update">Valider</button>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit" name="patient_update_cancel">Annuler</button>
            </form>
        </div>
    </body>
</html>
<?php $conn = null; //Close the connection to the database. ?>