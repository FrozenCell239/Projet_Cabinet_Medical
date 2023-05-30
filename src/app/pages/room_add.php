<?php include("header.php"); ?>
<div class="bg-white rounded-xl p-8 rounded shadow-md">
    <h1 class="text-2xl font-bold mb-6">Ajout d'une salle</h1>
    <form action="server.php" method="post">
        <div class="mb-6">
            <label class="block font-bold mb-2" for="new_room_name">Nom de la salle</label>
            <input
                class="block border border-gray-400 p-2 w-full"
                type="text"
                name="new_room_name"
                maxlength="42"
                required
            />
        </div>
        <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" type="submit" name="room_register">Valider</button>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit" onclick="location.href='rooms_manage.php';" name="room_register_cancel">Annuler</button>
    </form>
</div>
<?php include("footer.php"); ?>