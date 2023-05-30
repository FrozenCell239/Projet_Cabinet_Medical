<!DOCTYPE html>
<html lang="fr">
    <head>
        <!--Character encoding type declaration.-->
        <meta charset="utf-8">

        <!--Style sheets.-->
        <link rel="stylesheet" href="global.css"> <!--Customised style sheet.-->
        <?php if(strpos(basename($_SERVER['PHP_SELF']), "manage") !== false || basename($_SERVER['PHP_SELF']) === "main.php"){ ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous"> <!--Bootstrap, v5.2.3.-->
        <?php }else{ ?>
        <script src="https://cdn.tailwindcss.com"></script> <!--Tailwind, latest.-->
        <?php }; ?>
        <?php if(basename($_SERVER['PHP_SELF']) === "rdv_update.php"){ ?>
        <?php }; ?>

        <!--JS scripts.-->
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
        <?php if(strpos(basename($_SERVER['PHP_SELF']), "manage") !== false || basename($_SERVER['PHP_SELF']) === "main.php"){ ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
        <?php
            };
            switch(basename($_SERVER['PHP_SELF'])){
                case 'staff_update.php':{
                    ?>
                    <script>
                        $(function(){ //Handling staff member deletion.
                            $('.remove').click(function(){
                                var id = $(this).closest('button').attr('id');
                                if(confirm("Êtes-vous sûr(e) de vouloir retirer ce personnel de la liste ? Cette action est irréversible et n'engage aucune autre responsabilité que la vôtre.")){
                                    $.ajax({
                                        url: 'delete.php?what=1&id=' + id,
                                        type: 'GET',
                                        success: function(data){
                                            alert(data);
                                            location.reload();
                                        },
                                        error: function(jqXHR, textStatus, errorThrown){
                                            alert('Error: ' + textStatus + ' - ' + errorThrown);
                                        }
                                    });
                                };
                            });
                        });
                    </script>
                    <?php
                    break;
                };
                case 'room_update.php':{
                    ?>
                    <script>
                        $(function(){ //Handling room deletion.
                            $('.remove').click(function(){
                                var id = $(this).closest('button').attr('id');
                                if(confirm("Êtes-vous sûr(e) de vouloir retirer cette salle de la liste ? Cette action est irréversible et n'engage aucune autre responsabilité que la vôtre.")){
                                    $.ajax({
                                        url: 'delete.php?what=2&id=' + id,
                                        type: 'GET',
                                        success: function(data){
                                            alert(data);
                                            window.location.replace("rooms_manage.php");
                                        },
                                        error: function(jqXHR, textStatus, errorThrown){
                                            alert('Error: ' + textStatus + ' - ' + errorThrown);
                                        }
                                    });
                                };
                            });
                        });
                    </script>
                    <?php
                    break;
                };
                case 'patient_update.php':{
                    ?>
                    <script>
                        $(function(){
                            $('.remove').click(function(){ //Handling patient deletion.
                                var id = $(this).closest('button').attr('id');
                                if(confirm("Êtes-vous sûr(e) de vouloir retirer ce patient de la liste ? Cette action est irréversible et n'engage aucune autre responsabilité que la vôtre.")){
                                    $.ajax({
                                        url: 'delete.php?what=3&id=' + id,
                                        type: 'GET',
                                        success: function(data){
                                            alert(data);
                                            window.location.replace("patients_manage.php");
                                        },
                                        error: function(jqXHR, textStatus, errorThrown){
                                            alert('Error: ' + textStatus + ' - ' + errorThrown);
                                        }
                                    });
                                };
                            });
                        });
                    </script>
                    <?php
                    break;
                };
                case 'rdv_update.php':{
                    ?>
                    <script>
                        $(function(){ //Handling rendezvous deletion.
                            $('.remove').click(function(){
                                var id = $(this).closest('button').attr('id');
                                if(confirm("Êtes-vous sûr(e) de vouloir annuler ce rendez-vous ? Cette action est irréversible et n'engage aucune autre responsabilité que la vôtre.")){
                                    $.ajax({
                                        url: 'delete.php?what=4&id=' + id,
                                        type: 'GET',
                                        success: function(data){
                                            alert(data);
                                            location.reload();
                                        },
                                        error: function(jqXHR, textStatus, errorThrown){
                                            alert('Error: ' + textStatus + ' - ' + errorThrown);
                                        }
                                    });
                                };
                            });
                        });
                    </script>
                    <?php
                    break;
                };
                default :{break;};
            };
        ?>

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
        <link rel="icon" type="image/x-icon" href="images/favicon.png"> <!--Favicon.-->
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