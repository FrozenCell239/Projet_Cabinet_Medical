<?php
    include("header.php");
    $staff_info = $conn->prepare("
        SELECT
            prenom_personnel,
            nom_personnel,
            profession,
            mail,
            niveau_privilege,
            numero_badge,
            code_porte,
            identifiant
        FROM personnel
        WHERE id_personnel=?
    ;");
    $staff_info->execute([$_SESSION['u_staff_id']]);
    $staff_info_row = $staff_info->fetch();
?>
<div class="bg-white rounded-xl p-8 shadow-md" style="margin-top: 100px">
    <h1 class="text-2xl font-bold mb-6">Mise à jour d'un personnel</h1>
    <form action="server.php" method="post">
        <div class="mb-6">
            <label class="block font-bold mb-2" for="u_staff_name">Prénom</label>
            <input
                class="block border border-gray-400 p-2 w-full"
                type="text"
                name="u_staff_name"
                maxlength="42"
                value="<?= $staff_info_row['prenom_personnel']; ?>"
                required
            />
        </div>
        <div class="mb-6">
            <label class="block font-bold mb-2" for="u_staff_last_name">Nom</label>
            <input
                class="block border border-gray-400 p-2 w-full"
                type="text"
                name="u_staff_last_name"
                maxlength="42"
                value="<?= $staff_info_row['nom_personnel']; ?>"
                required
            />
        </div>
        <div class="mb-6">
            <label class="block font-bold mb-2" for="u_staff_profession">Profession</label>
            <input
                class="block border border-gray-400 p-2 w-full"
                type="text"
                name="u_staff_profession"
                maxlength="42"
                value="<?= $staff_info_row['profession']; ?>"
                required
            />
        </div>
        <div class="mb-6">
            <label class="block font-bold mb-2" for="u_staff_mail">Mail</label>
            <input
                class="block border border-gray-400 p-2 w-full"
                type="email"
                name="u_staff_mail"
                maxlength="42"
                value="<?= $staff_info_row['mail']; ?>"
                required
            />
        </div>
        <div class="mb-6">
            <label class="block font-bold mb-2" for="u_staff_level">Catégorie de personnel</label>
            <select
                class="block border border-gray-400 p-2 w-full"
                name="u_staff_level"
                required
            >
                <option value="">-- Choisir une catégorie --</option>
                <?php
                    foreach($privilege_levels as $key => $value){
                        if($key == $staff_info_row['niveau_privilege']){
                            $is_selected = "selected";
                        }
                        else{$is_selected = "";};
                        echo "<option $is_selected value='$key'>$value</option>";
                    };
                ?>
            </select>
        </div>
        <div class="mb-6">
            <label class="block font-bold mb-2" for="u_staff_user_login">Identifiant</label>
            <input
                class="block border border-gray-400 p-2 w-full"
                type="text"
                name="u_staff_user_login"
                maxlength="42"
                minlength="4"
                pattern="[a-z0-9_]+"
                value="<?= $staff_info_row['identifiant']; ?>"
                <?= ($staff_info_row['niveau_privilege'] == 0) ? "disabled" : "" ; ?>
            />
        </div>
        <script>
            var
                password_inputs = document.querySelectorAll('input[name*="password"], input[name*="login"]'),
                staff_level = document.getElementsByName("u_staff_level")[0],
                selected_value
            ;

            staff_level.addEventListener("change", function(){
                selected_value = staff_level.value;
                if(parseInt(this.value) > 0){
                    password_inputs.forEach(function(input){
                        input.removeAttribute("disabled");
                        input.setAttribute("required", '');
                    });
                }
                else{
                    password_inputs.forEach(function(input){
                        input.setAttribute("disabled", '');
                        input.removeAttribute("required");
                        input.value = '';
                    });
                };
            });
        </script>
        <div class="mb-6">
            <label class="block font-bold mb-2" for="u_staff_access_type">Code d'accès</label>
            <label class="inline-flex items-center">
                <input type="radio" name="u_staff_access_type" value="code_porte" <?= ($staff_info_row['numero_badge'] !== null) ? '' : "checked" ; ?> required>
                <span class="ml-2">Digicode</span>
            </label>
            <label class="inline-flex items-center ml-6">
                <input type="radio" name="u_staff_access_type" value="numero_badge" <?= ($staff_info_row['numero_badge'] !== null) ? "checked" : ''; ?> required>
                <span class="ml-2">Badge/carte</span>
            </label>
            <input
                class="block border border-gray-400 p-2 w-full"
                type="text"
                name="u_staff_access_code"
                value="<?= ($staff_info_row['numero_badge'] !== null) ? base64_decode($staff_info_row['numero_badge']) : ''; ?>"
                required
            />
            <script>
                var
                    access_type = document.getElementsByName("u_staff_access_type")[0],
                    code_input = document.getElementsByName("u_staff_access_code")[0],
                    doorcode_attributes = {
                        "maxlength" : "8",
                        "pattern" : "[A-D0-9*]{4,8}",
                        "oninvalid" : "setCustomValidity('4 à 8 caractères. Les caracèters autorisés sont 0 à 9, A à D, et *.')",
                        "oninput" : "setCustomValidity('')"
                    },
                    tag_attributes = {
                        "maxlength" : "12",
                        "pattern" : "[1-9]+",
                        "oninvalid" : "setCustomValidity('12 caractères maximum allant de 1 à 9. Ne pas noter les zéros et les espaces.')",
                        "oninput" : "setCustomValidity('')"
                    }
                ;
            
                for(var i = 0; i < access_type.length; i++){
                    access_type[i].addEventListener("change", function(){
                        if(this.value === "code_porte"){
                            for(var attr in doorcode_attributes){
                                code_input.setAttribute(attr, doorcode_attributes[attr]);
                            };
                        }
                        else{
                            for(var attr in tag_attributes){
                                code_input.setAttribute(attr, tag_attributes[attr]);
                            };
                        };
                    });
                };
            </script>
        </div>
        <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" type="submit" name="staff_register">Valider</button>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit" onclick="location.href='staff_manage.php';" name="staff_register_cancel">Annuler</button>
    </form>
</div>
<?php include("footer.php"); ?>