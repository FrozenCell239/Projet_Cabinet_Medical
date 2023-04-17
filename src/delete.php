<?php
    include('server.php');

    # Staff deletion
    if(isset($_GET['what']) && $_GET['what'] == 1 && isset($_GET['id'])){
        $delete_query = "DELETE FROM personnel WHERE id_personnel=".$_GET['id'];
        mysqli_query($conn, $delete_query);
        echo 'Personnel supprimé avec succès.';
    };

    # Room deletion
    if(isset($_GET['what']) && $_GET['what'] == 2 && isset($_GET['id'])){
        $delete_query = "DELETE FROM salles WHERE id_salle=".$_GET['id'];
        mysqli_query($conn, $delete_query);
        echo 'Salle supprimée avec succès.';
    };

    # Patient deletion
    if(isset($_GET['what']) && $_GET['what'] == 3 && isset($_GET['id'])){
        $delete_query = "DELETE FROM patients WHERE id_patient=".$_GET['id'];
        mysqli_query($conn, $delete_query);
        echo 'Patient supprimé avec succès.';
    };

    $_GET = array(); //Flushing the GET array to avoid some weird bugs.
?>