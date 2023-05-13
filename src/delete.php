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
        $_POST = array();
        unset($_SESSION['u_patient_id']);
        echo 'Patient supprimé avec succès.';
    };

    # Rendezvous deletion
    if(isset($_GET['what']) && $_GET['what'] == 4 && isset($_GET['id'])){
        $delete_query = $conn->prepare("DELETE FROM reservations WHERE id_reservation = ? ;");
        $delete_query->execute([$_GET['id']]);
        echo 'Rendez-vous annulé avec succès.';
    };

    unset($delete_query);
    $_GET = array(); //Flushing the GET array to avoid some bugs.
?>