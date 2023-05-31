<?php
    include('header.php');
    $room_info = $conn->prepare("SELECT nom_salle FROM salles WHERE id_salle=?;");
    $room_info->execute([$_SESSION['u_room_id']]);
    $room_info_row = $room_info->fetch();
?>
<div class="bg-white rounded-xl p-8 shadow-md">
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
<?php include("footer.php"); ?>