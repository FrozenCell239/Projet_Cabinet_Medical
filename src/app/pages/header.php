<!DOCTYPE html>
<html lang="fr">
    <head>
        <!--Character encoding type declaration.-->
        <meta charset="utf-8">

        <!--Style sheets.-->
        <link rel="stylesheet" href="./../css/global.css"/> <!--Customised style sheet.-->

        <?php if(strpos(basename($_SERVER['PHP_SELF']), "manage") !== false || basename($_SERVER['PHP_SELF']) === "main.php"){ ?>
        <link rel="stylesheet" href="./../node_modules/bootstrap/dist/css/bootstrap.min.css"/> <!--Bootstrap-->
        <?php }else{ ?>
        <link rel="stylesheet" href="./../css/output.css"/> <!--TailwindCSS-->
        <?php }; ?>
        <?php if(basename($_SERVER['PHP_SELF']) === "rdv_update.php"){ ?>
        <style>
            input:read-only{background-color: #dddddd;}
        </style>
        <?php }; ?>

        <!--PHP scripts.-->
        <?php
            include('server.php');
            if(basename($_SERVER['PHP_SELF']) !== "index.php"){
                if(isset($_SESSION['user'])){$user = $_SESSION['user'];}
                else{header("Location: index.php");};
                if($user->getPrivilegeLevel() < 3 && basename($_SERVER['PHP_SELF']) !== "main.php"){header("Location: main.php");};
            }
            else{
                if(isset($_SESSION['user'])){header("Location: main.php");};
            };
        ?>

        <!--Others.-->
        <title>Gestion cabinet médical</title>
        <link rel="icon" type="image/x-icon" href="./../img/favicon.png"> <!--Favicon.-->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!--Permet l'adaptation de la page et la disposition de ses éléments à tous les terminaux.-->
    </head>
    <body
        <?php if(strpos(basename($_SERVER['PHP_SELF']), "manage") === false && basename($_SERVER['PHP_SELF']) !== "main.php"){ ?>
        class="bg-gray-200 flex justify-center items-center h-screen" style="margin-top:100px; margin-bottom: 100px;"
        <?php }; ?>
    >
        <header>
            <?php if(strpos(basename($_SERVER['PHP_SELF']), "manage") !== false || basename($_SERVER['PHP_SELF']) === "main.php"){ ?>
            <nav class="navbar navbar-expand-sm bg-primary navbar-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="main.php">Accueil</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="collapsibleNavbar">
                        <ul class="navbar-nav">
                            <?php if(isset($user) && $user->getPrivilegeLevel() == 3){ ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Listes</a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="staff_manage.php">Personnel</a></li>
                                        <li><a class="dropdown-item" href="patients_manage.php">Patients</a></li>
                                        <li><a class="dropdown-item" href="rooms_manage.php">Salles</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Gestion</a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="rdv_manage.php">Rendez-vous</a></li>
                                        <li><a class="dropdown-item" href="access_manage.php">Accès</a></li>
                                    </ul>
                                </li>
                            <?php }; ?>
                            <li class="nav-item">
                                <a class="nav-link" href="user_update.php">Modifier mot de passe</a>
                            </li>
                            <button class="btn btn-primary" type="submit" onclick="location.href=`logout.php`">Se déconnecter</button>
                        </ul>
                    </div>
                </div>
            </nav>
            <?php }; ?>
        </header>
