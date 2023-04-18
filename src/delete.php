<?php
    include('server.php');

    # Staff deletion
    if(isset($_GET['what']) && $_GET['what'] == 1 && isset($_GET['id'])){
        $delete_query = $conn->prepare("DELETE FROM personnel WHERE id_personnel = ? ;");
        $delete_query->execute([$_GET['id']]);
        echo 'Personnel supprimé avec succès.';
    };

    # Room deletion
    if(isset($_GET['what']) && $_GET['what'] == 2 && isset($_GET['id'])){
        $delete_query = $conn->prepare("DELETE FROM salles WHERE id_salle = ? ;");
        $delete_query->execute([$_GET['id']]);
        echo 'Salle supprimée avec succès.';
    };

    # Patient deletion
    if(isset($_GET['what']) && $_GET['what'] == 3 && isset($_GET['id'])){
        $delete_query = $conn->prepare("DELETE FROM patients WHERE id_patient = ? ;");
        $delete_query->execute([$_GET['id']]);
        echo 'Patient supprimé avec succès.';
    };

    $_GET = array(); //Flushing the GET array to avoid some weird bugs.
?>