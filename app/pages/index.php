<?php require_once("header.php"); ?>
<div class="bg-white rounded-xl p-8 shadow-md">
    <h1 class="text-2xl font-bold mb-6">Connexion</h1>
    <form action="index.php" method="post">
        <div class="mb-6">
            <label class="block font-bold mb-2" for="user_login">Identifiant</label>
            <input class="block border border-gray-400 p-2 w-full" type="text" name="user_login" required/>
        </div>
        <div class="mb-6">
            <label class="block font-bold mb-2" for="psswrd">Mot de passe</label>
            <input
                class="block border border-gray-400 p-2 w-full"
                type="password"
                name="psswrd"
                pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{8,}"
                title="8 caractères minimum dont au moins une majuscule, une minuscule, un chiffre, et un symbole ( @#$%^&*_=+- )."
                required
            />
        </div>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit" name="login">Se connecter</button>
    </form>
</div>
<?php require_once("footer.php"); ?>