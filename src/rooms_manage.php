<?php include("header.php"); ?>
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
<?php include("footer.php"); ?>