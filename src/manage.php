<html lang="fr">
    <head>
        <!--Character encoding type declaration.-->
        <meta charset="utf-8">

        <!--Style sheets.-->
        <link rel="stylesheet" href="global.css"> <!--Customised style sheet.-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous"> <!--Bootstrap 5.2.3.-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
        <style>
            .wrapper{
                width: 700px;
                margin: 0 auto;
            }
            table tr td:last-child{width: 120px;}
        </style>

        <!--JS scripts.-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

        <!--PHP scripts.-->
        <?php
            include('server.php');
            if($_SESSION['admin'] == 0 || !isset($_SESSION['profession'])){header("Location: index.php");};
        ?>

        <!--Others.-->
        <title>Gestion du personnel</title>
        <link rel="icon" type="image/x-icon" href="../images/favicon.png"> <!--Favicon.-->
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
                        <div class="mt-5 mb-3 d-flex justify-content-between">
                            <h2 class="pull-left">Liste du personnel interne</h2>
                            <button class="btn" data-bs-toggle="collapse" data-bs-target="#add_form"><i class="bi bi-plus"></i> Ajouter</button>
                        </div>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Prénom</th>
                                    <th>Nom</th>
                                    <th>Identifiant</th>
                                    <th>Profession</th>
                                    <th>Administrateur</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $staff_list_query = "SELECT id_personnel, prenom_personnel, nom_personnel, profession, identifiant, admin FROM personnel";
                                    $staff_list_query_result = mysqli_query($conn, $staff_list_query);

                                    while($row = mysqli_fetch_array($staff_list_query_result)){
                                        if($row['admin']){$is_admin = "Oui";}
                                        else{$is_admin = "Non";};
                                        echo(
                                            "<tr>".
                                            "<td>".$row['prenom_personnel']."</td>".
                                            "<td>".$row['nom_personnel']."</td>".
                                            "<td>".$row['identifiant']."</td>".
                                            "<td>".$row['profession']."</td>".
                                            "<td>".$is_admin."</td>".
                                            "<td>".
                                            '<a href="update.php?what=\'1\'&id='.$row['id_personnel'].'" class="me-3"><span class="bi bi-pencil"></span></a>'.
                                            '<a href="delete.php?what=\'1\'&id='.$row['id_personnel'].'"><span class="bi bi-trash"></span></a>'.
                                            "</td>".
                                            "</tr>"
                                        );
                                    };
                                    mysqli_free_result($staff_list_query_result); //Free result set.
                                    mysqli_close($conn); //Close the connection to the database.
                                ?>
                            </tbody>
                        </table>
                        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Avertissement</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Êtes-vous sûr(e) de vouloir supprimer cet utilisateur ? Cette action est irréversible et n\'engage aucune autre responsabilité que la vôtre.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <button type="button" class="btn btn-primary">Confirmer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div id="add_form" class="collapse">
                            <form action="manage.php" method="post">
                                <h1>Ajouter un personnel</h1>
                                <input type="text" name="name" placeholder="Prénom" required/><br>
                                <input type="text" name="last_name" placeholder="Nom" required/><br>
                                <input type="text" name="profession" placeholder="Profession" required/><br>
                                <input type="text" name="user_login" placeholder="Identifiant" required/><br>
                                <input type="password" name="password" placeholder="Mot de passe" required/><br>
                                <input type="password" name="confirm_password" placeholder="Confirmation du mot de passe" required/><br>
                                <input type="checkbox" name="admin"> Administrateur<br>
                                <button type="submit" name="register">Ajouter</button>
                            </form>
                            <hr>
                        </div>
                        <form action="manage.php" method="post">
                            <button type="submit" name="back_home">Revenir à la page principale</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
        <footer>
            
        </footer>
    </body>
</html>
