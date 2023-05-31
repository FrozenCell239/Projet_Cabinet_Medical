<?php include("header.php"); ?>
<div class="bg-white rounded-xl p-8 shadow-md" style="margin-top: 100px">
    <h1 class="text-2xl font-bold mb-6">Ajout d'un personnel</h1>
    <form action="server.php" method="post">
        <div class="mb-6">
            <label class="block font-bold mb-2" for="new_staff_name">Prénom</label>
            <input
                class="block border border-gray-400 p-2 w-full"
                type="text"
                name="new_staff_name"
                maxlength="42"
                required
            />
        </div>
        <div class="mb-6">
            <label class="block font-bold mb-2" for="new_staff_last_name">Nom</label>
            <input
                class="block border border-gray-400 p-2 w-full"
                type="text"
                name="new_staff_last_name"
                maxlength="42"
                required
            />
        </div>
        <div class="mb-6">
            <label class="block font-bold mb-2" for="new_staff_profession">Profession</label>
            <input
                class="block border border-gray-400 p-2 w-full"
                type="text"
                name="new_staff_profession"
                maxlength="42"
                required
            />
        </div>
        <div class="mb-6">
            <label class="block font-bold mb-2" for="new_staff_user_login">Identifiant</label>
            <input
                class="block border border-gray-400 p-2 w-full"
                type="text"
                name="new_staff_user_login"
                maxlength="42"
                required
            />
        </div>
        <div class="mb-6">
            <label class="block font-bold mb-2" for="new_staff_mail">Mail</label>
            <input
                class="block border border-gray-400 p-2 w-full"
                type="email"
                name="new_staff_mail"
                maxlength="42"
                required
            />
        </div>
        <div class="mb-6">
            <label class="block font-bold mb-2" for="new_staff_password">Mot de passe</label>
            <input
                class="block border border-gray-400 p-2 w-full"
                type="password"
                name="new_staff_password"
                maxlength="42"
                pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{8,}"
                title="8 caractères minimum dont au moins une majuscule, une minuscule, un chiffre, et un symbole ( @#$%^&*_=+- )."
                required
            />
        </div>
        <div class="mb-6">
            <label class="block font-bold mb-2" for="new_staff_confirm_password">Confirmation du mot de passe</label>
            <input
                class="block border border-gray-400 p-2 w-full"
                type="password"
                name="new_staff_confirm_password"
                maxlength="42"
                pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{8,}"
                title="8 caractères minimum dont au moins une majuscule, une minuscule, un chiffre, et un symbole ( @#$%^&*_=+- )."
                required
            />
        </div>
        <div class="mb-6">
            <label class="block font-bold mb-2" for="">Catégorie de personnel</label>
            <select
                class="block border border-gray-400 p-2 w-full"
                name="new_staff_level"
                required
            >
                <option value="">-- Choisir un médecin --</option>
                <?php
                    foreach($privilege_levels as $key => $value){
                        echo "<option value='$key'>$value</option>";
                    };
                ?>
            </select>
        </div>
        <div class="mb-6">
            <label class="block font-bold mb-2">Code d'accès</label>
            <label class="inline-flex items-center">
                <input type="radio" name="new_staff_access_type" value="doorcode">
                <span class="ml-2">Digicode</span>
            </label>
            <label class="inline-flex items-center ml-6">
                <input type="radio" name="new_staff_access_type" value="tag">
                <span class="ml-2">Badge/carte</span>
            </label>
            <input
                class="block border border-gray-400 p-2 w-full"
                type="text"
                name="new_staff_access_code"
                required
                disabled
            />
            <script>
                var
                    access_type = document.getElementsByName("new_staff_access_type"),
                    code_input = document.getElementsByName("new_staff_access_code")[0],
                    doorcode_attributes = {
                        "placeholder" : "Example 1",
                        "maxlength" : "8",
                        "pattern" : "[A-D0-9*]{4,8}",
                        "oninvalid" : "setCustomValidity('4 à 8 caractères. Les caracèters autorisés sont 0 à 9, A à D, et *.')",
                        "oninput" : "setCustomValidity('')"
                    },
                    tag_attributes = {
                        "placeholder" : "Example 2",
                        "maxlength" : "12",
                        "pattern" : "[1-9]",
                        "oninvalid" : "setCustomValidity('12 caractères maximum allant de 1 à 9. Ne pas noter les zéros et les espaces.')",
                        "oninput" : "setCustomValidity('')"
                    }
                ;
            
                for(var i = 0; i < access_type.length; i++){
                    access_type[i].addEventListener("change", function(){
                        code_input.removeAttribute("disabled");
                        if(this.value === "doorcode"){
                            for(var attr in doorcode_attributes){
                                code_input.setAttribute(attr, doorcode_attributes[attr]);
                            };
                        }
                        else{
                            for(var attr in tag_attributes){
                                code_input.setAttribute(attr, tag_attributes[attr]);
                            };
                            code_input.removeAttribute("disabled");
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