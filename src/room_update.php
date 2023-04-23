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
            $room_info = $conn->prepare("SELECT nom_salle FROM salles WHERE id_salle=?;");
            $room_info->execute([$_SESSION['u_room_id']]);
            $room_info_row = $room_info->fetch();
        ?>

        <!--Others.-->
        <title>Mise à jour informations salle</title>
        <link rel="icon" type="image/x-icon" href="../images/favicon.png"> <!--Favicon.-->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!--Permet l'adaptation de la page et la disposition de ses éléments à tous les terminaux.-->
    </head>
    <body class="bg-gray-200 flex justify-center items-center h-screen">
        <div class="bg-white rounded-xl p-8 rounded shadow-md">
            <h1 class="text-2xl font-bold mb-6">Mise à jour informations salle</h1>
            <form action="server.php" method="post">
                <div class="mb-6">
                    <label class="block font-bold mb-2" for="u_room_name">Nom de la salle</label>
                    <input
                        class="block border border-gray-400 p-2 w-full"
                        type="text"
                        name="u_room_name"
                        value="<?php echo $room_info_row['nom_salle']; ?>"
                        required
                    />
                </div>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit" name="room_update">Valider</button>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit" name="room_update_cancel">Annuler</button>
            </form>
        </div>
    </body>
</html>
<?php $conn = null; //Close the connection to the database. ?>