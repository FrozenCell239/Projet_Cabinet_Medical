<?php include("header.php"); ?>
<div style="margin-top: 300px;" class="bg-white rounded-xl p-8 shadow-md">
    <h1 class="text-2xl font-bold mb-6">Créer un rendez-vous</h1>
    <form action="server.php" method="post">
        <div class="mb-6">
            <label class="block font-bold mb-2" for="patient_name">Prénom</label>
            <input
                class="block border border-gray-400 p-2 w-full"
                type="text"
                name="patient_name"
                maxlength="42"
                required
            />
        </div>
        <div class="mb-6">
            <label class="block font-bold mb-2" for="patient_last_name">Nom</label>
            <input
                class="block border border-gray-400 p-2 w-full"
                type="text"
                name="patient_last_name"
                maxlength="42"
                required
            />
        </div>
        <div class="mb-6">
            <label class="block font-bold mb-2" for="patient_need">Besoin</label>
            <input
                class="block border border-gray-400 p-2 w-full"
                type="text"
                name="patient_need"
                maxlength="42"
                required
            />
        </div> 
        <div class="mb-6">
            <label class="block font-bold mb-2" for="patient_ssn">Numéro sécurité sociale</label>
            <input
                class="block border border-gray-400 p-2 w-full"
                type="tel"
                name="patient_ssn"
                pattern="[12][ \.\-]?[0-9]{2}[ \.\-]?(0[1-9]|[1][0-2])[ \.\-]?([0-9]{2}|2A|2B)[ \.\-]?[0-9]{3}[ \.\-]?[0-9]{3}[ \.\-]?[0-9]{2}}"
                maxlength="21"
                required
            />
        </div>
        <div class="mb-6">
            <label class="block font-bold mb-2" for="patient_number">Téléphone</label>
            <input
                class="block border border-gray-400 p-2 w-full"
                type="tel"
                name="patient_number"
                pattern="(01|02|03|04|05|06|07|08|09)[ \.\-]?[0-9]{2}[ \.\-]?[0-9]{2}[ \.\-]?[0-9]{2}[ \.\-]?[0-9]{2}"
                maxlength="14"
                required
            />
        </div>
        <div class="mb-6">
            <label class="block font-bold mb-2" for="patient_address">Adresse</label>
            <input
                class="block border border-gray-400 p-2 w-full"
                type="text"
                name="patient_address"
                maxlength="42"
                required
            />
        </div>
        <div class="mb-6">
            <label class="block font-bold mb-2" for="patient_town">Ville</label>
            <input
                class="block border border-gray-400 p-2 w-full"
                type="text"
                name="patient_town"
                maxlength="42"
                required
            />
        </div>
        <div class="mb-6">
            <label class="block font-bold mb-2" for="room_select">Salle</label>
            <select
                class="block border border-gray-400 p-2 w-full"
                name="room_select"
                required
            >
                <option value="">-- Choisir une salle --</option>
                <?php
                    $room_select_query = $conn->prepare("SELECT id_salle, nom_salle FROM salles;");
                    $room_select_query->execute();
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
            <label class="block font-bold mb-2" for="doctor_select">Médecin</label>
            <select
                class="block border border-gray-400 p-2 w-full"
                name="doctor_select"
                required
            >
                <option value="">-- Choisir un médecin --</option>
                <?php
                    $doctor_select_query = $conn->prepare("
                        SELECT
                            id_personnel,
                            prenom_personnel,
                            nom_personnel,
                            profession
                        FROM personnel
                        WHERE
                            niveau_privilege > 0 AND
                            niveau_privilege< 3
                    ;");
                    $doctor_select_query->execute();
                    while($doctor_select_row = $doctor_select_query->fetch()){
                        echo(
                            '<option value="'.$doctor_select_row['id_personnel'].'">'.
                            $doctor_select_row['prenom_personnel']." ".
                            $doctor_select_row['nom_personnel']." (".
                            $doctor_select_row['profession'].
                            ")</option>"
                        );
                    }; 
                ?>
            </select>
        </div>
        <div class="mb-6">
            <label class="block font-bold mb-2" for="rdv_datetime">Date et heure</label>
            <input
                class="block border border-gray-400 p-2 w-full"
                type="datetime-local"
                name="rdv_datetime"
                required
            />
            <script>
                document.getElementsByName("rdv_datetime")[0].setAttribute("min", new Date().toISOString().slice(0, 16));
            </script>
        </div>
        <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" type="submit" name="rdv_register">Valider</button>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit" onclick="location.href='rdv_manage.php';" name="rdv_register_cancel">Annuler</button>
    </form>
</div>
<?php include("footer.php"); ?>