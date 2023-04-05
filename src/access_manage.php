<html lang="fr">
    <head>
        <!--Character encoding type declaration.-->
        <meta charset="utf-8">

        <!--Style sheets.-->
        <link rel="stylesheet" href="global.css"> <!--Customised style sheet.-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous"> <!--Bootstrap 5.2.3.-->
        <style>
            .wrapper{
                width: 700px;
                margin: 0 auto;
            }
            table tr td:last-child{width: 120px;}
        </style>

        <!--JS scripts.-->
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

        <!--PHP scripts.-->
        <?php
            include('server.php');
            if($_SESSION['admin'] == 0 || !isset($_SESSION['profession'])){header("Location: index.php");};

            $tag_list_query = "SELECT id_badge, mdp_badge, actif FROM badges_visiophone";
            $tag_list_query_result = mysqli_query($conn, $tag_list_query);
        ?>

        <!--Others.-->
        <title>Gestion des patients</title>
        <link rel="icon" type="image/x-icon" href="../images/favicon.png"> <!--Favicon.-->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!--Permet l'adaptation de la page et la disposition de ses éléments à tous les terminaux.-->
    </head>
    <body>
        <header>
            <?php echo $navbar; ?>
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
                                <input type="password" name="current_doorcode" placeholder="Code actuel" required/><br>
                                <input type="password" name="new_doorcode" placeholder="Nouveau code" required/><br>
                                <input type="password" name="confirm_new_doorcode" placeholder="Confirmation du nouveau code" required/><br>
                                <button type="submit" name="change_doorcode">Ajouter</button>
                            </form>
                            <hr>
                        </div>

                        <hr>
                        <div class="mt-5 mb-3 d-flex justify-content-between">
                            <h2 class="pull-left">Badges</h2>
                            <!--button class="btn" data-bs-toggle="collapse" data-bs-target="#add_form">Mofifier</button-->
                        </div>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Numéro badge</th>
                                    <th>Statut</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    while($row = mysqli_fetch_array($tag_list_query_result)){
                                        if($row['actif']){$active = "Oui";}
                                        else{$active = "Non";};
                                        echo(
                                            '<tr id="'.$row['id_badge'].'">'.
                                            "<td>".base64_decode($row['mdp_badge'])."</td>".
                                            "<td>$active</td>".
                                            "<td>".
                                            '<button class="btn btn-danger btn-sm remove">Modifier</button>'.
                                            "</td>".
                                            "</tr>"
                                        );
                                    };
                                    mysqli_free_result($tag_list_query_result);
                                    mysqli_close($conn); //Close the connection to the database.
                                ?>
                            </tbody>
                        </table>
                        <div>
                            <!--?php
                                if(file_exists("access/log.txt")){
                                    $logs = nl2br(file_get_contents("access/log.txt" ));
                                    echo $logs;
                                };
                            ?-->
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <footer>
            
        </footer>
    </body>
</html>