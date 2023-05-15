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
        <script>
            $(function(){ //Handling room deletion.
                $('.remove').click(function(){
                    var id = $(this).closest('button').attr('id');
                    if(confirm("Êtes-vous sûr(e) de vouloir retirer cette salle de la liste ? Cette action est irréversible et n'engage aucune autre responsabilité que la vôtre.")){
                        $.ajax({
                            url: 'delete.php?what=2&id=' + id,
                            type: 'GET',
                            success: function(data){
                                alert(data);
                                window.location.replace("rooms_manage.php");
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
            if(isset($_SESSION['user'])){$user = $_SESSION['user'];}
            else{header("Location: index.php");};
            if($user->getPrivilegeLevel() < 3){header("Location: main.php");};
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
                        value="<?= $room_info_row['nom_salle']; ?>"
                        required
                    />
                </div>
                <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" type="submit" name="room_update">Valider</button>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit" name="room_update_cancel">Annuler</button>
                <button id="<?= $_SESSION['u_room_id']; ?>" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded remove" type="submit" name="room_delete" style="float: right;">Supprimer</button>
            </form>
        </div>
    </body>
</html>
<?php $conn = null; //Close the connection to the database. ?>