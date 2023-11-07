<?php require_once("header.php"); ?>
<main>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="mt-5 mb-3 d-flex justify-content-between">
                    <h2 class="pull-left">Liste des salles</h2>
                    <button class="btn" onclick='location.href="room_add.php";'>Ajouter</button>
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
                                    '<a class="btn btn-info btn-sm" href="room_update.php?rid_u='.$row['id_salle'].'">Modifier</a>'.
                                    "</td>".
                                    "</tr>"
                                );
                            };
                            unset($row);
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<?php require_once("footer.php"); ?>