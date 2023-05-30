        <footer>
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
        </footer>
    </body>
</html>
<?php $conn = null; //Close the connection to the database. ?>