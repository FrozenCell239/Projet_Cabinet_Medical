<?php include("header.php"); ?>
<div class="bg-white rounded-xl p-8 rounded shadow-md">
    <h1 class="text-2xl font-bold mb-6">Ajout d'un patient</h1>
    <form action="patient_add.php" method="post">
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
<?php include("footer.php"); ?>