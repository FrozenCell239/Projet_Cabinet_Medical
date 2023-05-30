<?php include("header.php"); ?>
<div class="bg-white rounded-xl p-8 rounded shadow-md">
    <h1 class="text-2xl font-bold mb-6">Changement adresse mail</h1>
    <form action="user_update.php" method="post">
        <div class="mb-6">
            <label class="block font-bold mb-2" for="mail">Adresse mail</label>
            <input
                class="block border border-gray-400 p-2 w-full"
                type="email"
                name="mail"
                pattern="[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([_\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})"
                title="8 caractères minimum dont au moins une majuscule, une minuscule, un chiffre, et un symbole ( @#$%^&*_=+- )."
                value="<?= $user->getMail(); ?>"
                required
            />
        </div>
        <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" type="submit" name="mail_update">Valider</button>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="button" onclick="location.href='main.php';"  name="mail_update_cancel">Annuler</button>
    </form>
    <br><br>
    <h1 class="text-2xl font-bold mb-6">Changement mot de passe</h1>
    <form action="user_update.php" method="post">
        <div class="mb-6">
            <label class="block font-bold mb-2" for="current_password">Mot de passe actuel</label>
            <input
                class="block border border-gray-400 p-2 w-full"
                type="password"
                name="current_password"
                pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{8,}"
                title="8 caractères minimum dont au moins une majuscule, une minuscule, un chiffre, et un symbole ( @#$%^&*_=+- )."
                required
            />
        </div>
        <div class="mb-6">
            <label class="block font-bold mb-2" for="psswrd">Nouveau mot de passe</label>
            <input
                class="block border border-gray-400 p-2 w-full"
                type="password"
                name="new_password"
                pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{8,}"
                title="8 caractères minimum dont au moins une majuscule, une minuscule, un chiffre, et un symbole ( @#$%^&*_=+- )."
                required
            />
        </div>
        <div class="mb-6">
            <label class="block font-bold mb-2" for="psswrd">Confirmation mot de passe</label>
            <input
                class="block border border-gray-400 p-2 w-full"
                type="password"
                name="confirm_new_password"
                pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{8,}"
                title="8 caractères minimum dont au moins une majuscule, une minuscule, un chiffre, et un symbole ( @#$%^&*_=+- )."
                required
            />
        </div>
        <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" type="submit" name="password_update">Valider</button>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="button" onclick="location.href='main.php';" name="password_update_cancel">Annuler</button>
    </form>
</div>
<?php include("footer.php"); ?>