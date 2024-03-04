        <footer>
            <!--JS scripts.-->
            <script src="./../node_modules/jquery/dist/jquery.min.js"></script> <!--JQuery-->
            <?php if(strpos(basename($_SERVER['PHP_SELF']), "manage") !== false || basename($_SERVER['PHP_SELF']) === "main.php"){ ?>
            <script src="./../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script> <!--Bootstrap-->
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
                                                window.location.replace("staff_manage.php");
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
                                                window.location.replace("rdv_manage.php");
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
        </footer>
    </body>
</html>
<?php $conn = null; //Close the connection to the database. ?>
