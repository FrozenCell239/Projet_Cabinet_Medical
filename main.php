<html lang="fr">
    <head>
        <!--Character encoding type declaration.-->
        <meta charset="utf-8">

        <!--Style sheets.-->
        <link rel="stylesheet" href="global.css"> <!--Customised style sheet.-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous"> <!--Bootstrap 5.2.3.-->

        <!--JS scripts.-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

        <!--PHP scripts.-->
        <?php
            include('server.php');
            if(!isset($_SESSION['profession'])){header("Location: index.php");};
            if($_SESSION['admin'] == 1){include('OLD/serial_command.php');};
        ?>

        <!--Others.-->
        <title>Tableau de bord</title>
        <link rel="icon" type="image/x-icon" href="favicon.png"> <!--Favicon.-->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!--Permet l'adaptation de la page et la disposition de ses éléments à tous les terminaux.-->
    </head>
    <body>
        <header>
            
        </header>
        <main>
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <h1>Connecté(e) en tant que <b><?php echo $_SESSION['name']." ".$_SESSION['last_name']."</b>, poste ".$_SESSION['profession']; ?>.</h1>
                        <hr>
                        <?php if($_SESSION['admin'] == 1){ //Interface propre à la secrétaire. ?>
                        <form action="main.php" method="post">
                            <button type="submit" name="door_unlock">Déverrouiller la porte</button>
                        </form>
                        <form action="main.php" method="post">
                            <button type="submit" name="door_open">Ouvrir la porte</button>
                        </form>
                        <hr>
                        <form action="main.php" method="post">
                            <button type="submit" name="go_to_register">Modifier la liste du personnel</button>
                        </form>
                        <?php
                            };
	                    	if(isset($_POST['door_unlock'])){
                                unset($_POST['door_unlock']);
                                strikeOpen();
	                    	};
                            if(isset($_POST['door_open'])){
                                unset($_POST['door_open']);
                                doorOpen();
	                    	};
                            if(isset($_POST['go_to_register'])){
                                unset($_POST['go_to_register']);
                                header("Refresh: 0; url=manage.php");
	                    	};
	                    ?>
                        <hr>
                    </div>
                </div>
            </div>
        </main>
        <footer>
            <center>
                <form action="logout.php" method="post">
                    <button type="submit">Se déconnecter</button>
                </form>
            </center>
        </footer>
    </body>
</html>