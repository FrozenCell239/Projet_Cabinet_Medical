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
        <script>
            $(function(){ //Handling room deletion.
                $('.remove').click(function(){
                    var id = $(this).closest('tr').attr('id');
                    if(confirm("Êtes-vous sûr(e) de vouloir retirer cette salle de la liste ? Cette action est irréversible et n'engage aucune autre responsabilité que la vôtre.")){
                        $.ajax({
                            url: 'delete.php?what=2&id=' + id,
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

        <!--PHP scripts.-->
        <?php
            include('server.php');
            if(isset($_SESSION['user'])){$user = $_SESSION['user'];}
            else{header("Location: index.php");};
            if($user->getPrivilegeLevel() < 3){header("Location: main.php");};
        ?>

        <!--Others.-->
        <title>Gestion des salles</title>
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
                            <h2 class="pull-left">Liste des salles</h2>
                            <button class="btn" data-bs-toggle="collapse" data-bs-target="#add_form"><i class="bi bi-plus"></i> Ajouter</button>
                        </div>
                        <div id="add_form" class="collapse">
                            <hr>
                            <form action="rooms_manage.php" method="post">
                                <h3>> Ajouter une salle</h3>
                                <input type="text" name="new_room_name" placeholder="Nom" required/><br>
                                <button type="submit" name="room_register">Ajouter</button>
                            </form>
                            <hr>
                        </div>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $rooms_list_query = $conn->prepare("SELECT * FROM salles ORDER BY nom_salle;");
                                    $rooms_list_query->execute();
                                    while($row = $rooms_list_query->fetch()){
                                        echo(
                                            '<tr id="'.$row['id_salle'].'">'.
                                            "<td>".$row['nom_salle']."</td>".
                                            "<td>".
                                            '<button class="btn btn-danger btn-sm remove">Supprimer</button>'.
                                            '<a class="btn btn-info btn-sm" href="room_update.php?rid_u='.$row['id_salle'].'">Modifier</a>'.
                                            "</td>".
                                            "</tr>"
                                        );
                                    };
                                    unset($row);
                                ?>
                            </tbody>
                        </table>
                        <!--div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                        </div-->
                    </div>
                </div>
            </div>
        </main>
        <footer>
            
        </footer>
    </body>
</html>
<?php $conn = null; //Close the connection to the database. ?>