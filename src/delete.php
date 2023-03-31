<?php
    include('server.php');

    if(isset($_GET['what']) && $_GET['what'] == 1 && isset($_GET['id'])){
        $delete_query = "DELETE FROM personnel WHERE id_personnel=".$_GET['id'];
        mysqli_query($conn, $delete_query);
        echo 'Personnel supprimé avec succès.';
    };
?>
