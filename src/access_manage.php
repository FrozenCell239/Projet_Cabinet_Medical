<html lang="fr">
    <head>
        <!--Character encoding type declaration.-->
        <meta charset="utf-8">

        <!--Style sheets.-->
        <link rel="stylesheet" href="global.css"> <!--Customised style sheet.-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous"> <!--Bootstrap 5.2.3.-->

        <!--JS scripts.-->
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

        <!--PHP scripts.-->
        <?php
            include('server.php');
            if(isset($_SESSION['user'])){$user = $_SESSION['user'];}
            else{header("Location: index.php");};
            if($user->getPrivilegeLevel() < 3){header("Location: main.php");};
        ?>

        <!--Others.-->
        <title>Gestion des patients</title>
        <link rel="icon" type="image/x-icon" href="../images/favicon.png"> <!--Favicon.-->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!--Permet l'adaptation de la page et la disposition de ses éléments à tous les terminaux.-->
    </head>
    <body>
        <header>
            <?= $navbar; ?>
        </header>
        <main>
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="mt-5 mb-3 d-flex justify-content-between">
                            <h2 class="pull-left">Code actuel de la porte</h2>
                            <button class="btn" data-bs-toggle="collapse" data-bs-target="#add_form">Modifier</button>
                        </div>
                        <div id="add_form" class="collapse">
                            <form action="access_manage.php" method="post">
                                <label for="current_doorcode">Code actuel</label>
                                <input
                                    type="password"
                                    pattern="[A-D0-9*]{4,8}"
                                    name="current_doorcode"
                                    oninvalid="setCustomValidity('4 à 8 caractères. Les caracèters autorisés sont 0 à 9, A à D, et *.')"
                                    oninput="setCustomValidity('')"
                                    maxlength="8"
                                    size="4"
                                    required
                                /><br>
                                <label for="new_doorcode">Nouveau code</label>
                                <input
                                    type="password"
                                    pattern="[A-D0-9*]{4,8}"
                                    name="new_doorcode"
                                    oninvalid="setCustomValidity('4 à 8 caractères. Les caracèters autorisés sont 0 à 9, A à D, et *.')"
                                    oninput="setCustomValidity('')"
                                    maxlength="8"
                                    size="4"
                                    required
                                /><br>
                                <label for="confirm_new_doorcode">Confirmation du nouveau code</label>
                                <input
                                    type="password"
                                    pattern="[A-D0-9*]{4,8}"
                                    name="confirm_new_doorcode"
                                    oninvalid="setCustomValidity('4 à 8 caractères. Les caracèters autorisés sont 0 à 9, A à D, et *.')"
                                    oninput="setCustomValidity('')"
                                    maxlength="8"
                                    size="4"
                                    required
                                /><br>
                                <button type="submit" name="change_doorcode">Modifier</button>
                            </form>
                        </div>
                        <hr>
                        <button class="btn" data-bs-toggle="collapse" data-bs-target="#logs_display">Afficher l'historique des accès</button>
                        <div id="logs_display" class="collapse">
                            <?php
                                define('LOG_FILE', "log.txt"); //Path and name of the log file. Only the name of the file here as all files are in the same folders.
                                if(file_exists(LOG_FILE)){
                                    $logs = nl2br(file_get_contents(LOG_FILE));
                                    echo $logs;
                                }
                                else{echo "<i>&nbsp;&nbsp;&nbsp;&nbsp;Aucun accès répertorié pour le moment.</i>";};
                            ?>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <footer>
            
        </footer>
    </body>
</html>
<?php $conn = null; //Close the connection to the database. ?>