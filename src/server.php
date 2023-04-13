<?php
    # Global use functions
    function sqlGetID($BDD, $QUERY, $IDNAME){
        $get_id_query_result = mysqli_query($BDD, $QUERY);
        $get_id_row = mysqli_fetch_array($get_id_query_result, MYSQLI_ASSOC);
        if(isset($get_id_row[$IDNAME])){return $get_id_row[$IDNAME];}
        else{return -1;};
    };

    # HTML snippets
    $secretary_navbar = '
        <nav class="navbar navbar-expand-sm bg-primary navbar-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="main.php">Accueil</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="collapsibleNavbar">

                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Listes</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="staff_manage.php">Personnel</a></li>
                                <li><a class="dropdown-item" href="patients_manage.php">Patients</a></li>
                                <li><a class="dropdown-item" href="rooms_manage.php">Salles</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Gestion</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="rdv_manage.php">Rendez-vous</a></li>
                                <li><a class="dropdown-item" href="access_manage.php">Accès</a></li>
                            </ul>
                        </li>    
                    </ul>

                    <form class="d-flex" action="logout.php" method="post">
                        <button class="btn btn-primary" type="submit">Se déconnecter</button>
                    </form>
                </div>
            </div>
        </nav>
    ';
    $non_secretary_navbar = '
        <nav class="navbar navbar-expand-sm bg-primary navbar-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="main.php">Accueil</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="collapsibleNavbar">    
                    <form class="d-flex" action="logout.php" method="post">
                        <button class="btn btn-primary" type="submit">Se déconnecter</button>
                    </form>
                </div>
            </div>
        </nav>
    ';

    # Session and connection to database init
    session_start();
    $errors = array(); //Used to collect errors if some happen.
    $conn = mysqli_connect('localhost:3307', 'root', '', 'cabinet'); //On Debian Linux : $conn = mysqli_connect('localhost', 'phpmyadmin', 'phpmyadmin', 'cabinet');

    # Navbar setting
    if(isset($_SESSION['profession']) && $_SESSION['profession'] == 'secretaire'){$navbar = $secretary_navbar;};
    if(isset($_SESSION['profession']) && $_SESSION['profession'] != 'secretaire'){$navbar = $non_secretary_navbar;};

    # Staff registration
    if(isset($_POST['staff_register'])){
        $new_name = trim($_POST['new_staff_name']);
        $new_last_name = trim($_POST['new_staff_last_name']);
        $new_profession = trim($_POST['new_staff_profession']);
        $new_user_login = trim($_POST['new_staff_user_login']);
        $new_password = sha1($_POST['new_staff_password']);
        $new_confirm_password = sha1($_POST['new_staff_confirm_password']);
        if(isset($_POST['new_staff_admin'])){$new_admin = 1;}
        else{$new_admin = 0;};
        if($new_password != $new_confirm_password){
            array_push($errors, "Les mots de passe ne correspondent pas.");
            ?>
            <script>
                alert("Les mots de passe ne correspondent pas.");
            </script>
            <?php
        };
        $login_check_query = "SELECT id_personnel FROM personnel WHERE identifiant='$new_user_login'";
        $login_check_query_result = mysqli_query($conn, $login_check_query);
        if(mysqli_num_rows($login_check_query_result) > 0){ //Check if user login already exist.
              array_push($errors, "Identifiant déjà utilisé.");
            ?>
            <script>
                alert("Identifiant déjà utilisé.");
            </script>
            <?php
        };
        $user_check_query = "SELECT id_personnel FROM personnel WHERE prenom_personnel='$new_name' AND nom_personnel='$new_last_name'";
        $user_check_query_result = mysqli_query($conn, $user_check_query);
        if(mysqli_num_rows($user_check_query_result) != 0){ //Check if user already exist.
              array_push($errors, "Cette personne est déjà répertoriée.");
            ?>
            <script>
                alert("Cette personne est déjà répertoriée.");
            </script>
            <?php
        };
        if(count($errors) == 0){ //If no errors, register.
              $insert_query = "INSERT INTO personnel (prenom_personnel, nom_personnel, profession, identifiant, mot_de_passe, admin) VALUES ('$new_name', '$new_last_name', '$new_profession', '$new_user_login', '$new_password', '$new_admin');";
              $insert_query_result = mysqli_query($conn, $insert_query);
        };
        $_POST = array();
    };

	# Room registration
	if(isset($_POST['room_register'])){
        $new_room_name = trim($_POST['new_room_name']);
        $room_check_query = "SELECT id_salle FROM salles WHERE nom_salle='$new_room_name'";
        $room_check_query_result = mysqli_query($conn, $room_check_query);
        if(mysqli_num_rows($room_check_query_result) != 0){ //Check if room already exist.
              array_push($errors, "Cette salle est déjà répertoriée.");
            ?>
            <script>
                alert("Cette salle est déjà répertoriée.");
            </script>
            <?php
        };
        if(count($errors) == 0){ //If no errors, register.
              $insert_query = "INSERT INTO salles (nom_salle) VALUES ('$new_room_name');";
              $insert_query_result = mysqli_query($conn, $insert_query);
        };
        $_POST = array();
    };

    # Patient registration
    if(isset($_POST['patient_register'])){
        $new_patient_name = trim($_POST['new_patient_name']);
        $new_patient_last_name = trim($_POST['new_patient_last_name']);
        $patient_check_query = "SELECT id_patient FROM patients WHERE prenom_patient='$new_patient_name' AND nom_patient='$new_patient_last_name'";
        $patient_check_query_result = mysqli_query($conn, $patient_check_query);
        if(mysqli_num_rows($patient_check_query_result) != 0){ //Check if patient already exist.
            array_push($errors, "Ce(tte) patient(e) est déjà répertorié(e).");
            ?>
            <script>
                alert("Ce(tte) patient(e) est déjà répertorié(e).");
            </script>
            <?php
        };
        if(count($errors) == 0){ //If no errors, register.
                $insert_query = "INSERT INTO patients (prenom_patient, nom_patient) VALUES ('$new_patient_name', '$new_patient_last_name');";
                $insert_query_result = mysqli_query($conn, $insert_query);
        };
        $_POST = array();
    };

    # Login
    if(isset($_POST['login'])){ //Check if Login button is pressed.
        $password = sha1($_POST['psswrd']);
        $login = trim($_POST['user_login']);
        $login_query = "SELECT id_personnel, identifiant, profession, nom_personnel, prenom_personnel, admin FROM personnel WHERE identifiant='$login' AND mot_de_passe='$password';";
        $login_query_result = mysqli_query($conn, $login_query);
        $select_row = mysqli_fetch_array($login_query_result, MYSQLI_ASSOC);
        if(mysqli_num_rows($login_query_result) > 0){
            $_SESSION['user_id'] = $select_row['id_personnel'];
            $_SESSION['username'] = $select_row['identifiant'];
            $_SESSION['profession'] = $select_row['profession'];
            $_SESSION['name'] = $select_row['prenom_personnel'];
            $_SESSION['last_name'] = $select_row['nom_personnel'];
            $_SESSION['admin'] = $select_row['admin'];
            $_POST = array();
            header("Refresh: 0; url=main.php");
        }
        else{
            ?>
            <script>
                alert("Identifiant ou mot de passe incorrect.");
            </script>
            <?php
        };
        $_POST = array();
    };

    # Doorcode changing
    if(isset($_POST['change_doorcode'])){
        $current_doorcode = sha1($_POST['current_doorcode']);
        $new_doorcode = sha1($_POST['new_doorcode']);
        $confirm_new_doorcode = sha1($_POST['confirm_new_doorcode']);
        if($new_doorcode != $confirm_new_doorcode){ //Checks if new doorcodes match.
            array_push($errors, "Les codes ne correspondent pas.");
            ?>
            <script>
                alert("Les codes ne correspondent pas.");
            </script>
            <?php
        };
        $current_doorcode_check = "SELECT * FROM code_visiophone WHERE mdp_code='$current_doorcode';";
        $current_doorcode_check_result = mysqli_query($conn, $current_doorcode_check);
        if(mysqli_num_rows($current_doorcode_check_result) == 0){ //Checks if typed current doorcode exists.
            array_push($errors, "Le code actuel saisi est incorrect.");
            ?>
            <script>
                alert("Le code actuel saisi est incorrect.");
            </script>
            <?php
        }
        else{
            $row = mysqli_fetch_array($current_doorcode_check_result, MYSQLI_ASSOC);
            $doorcode_id = $row['id_code'];
        };
        if(count($errors) == 0){
            $doorcode_change_query = "UPDATE code_visiophone SET mdp_code='$new_doorcode' WHERE id_code='$doorcode_id';";
            mysqli_query($conn, $doorcode_change_query);
            ?>
            <script>
                alert("Code modifié avec succès.");
            </script>
            <?php
        };
        mysqli_free_result($current_doorcode_check_result);
        mysqli_free_result($doorcode_change_query_result);
        $_POST = array();
    };

    # New rendezvous creating
    if(isset($_POST['rdv_register'])){
        $patient_name = $_POST['patient_name'];
        $patient_last_name = mysqli_real_escape_string($conn, trim($_POST['patient_last_name']));
        $patient_need = mysqli_real_escape_string($conn, trim($_POST['patient_need']));
        $doctor_select = $_POST['doctor_select'];
        $room_select = $_POST['room_select'];
        $new_rdv_datetime = $_POST['rdv_datetime'];
        $get_patient_id_query = "SELECT id_patient FROM patients WHERE prenom_patient = '$patient_name' AND nom_patient = '$patient_last_name';";
        if(sqlGetID($conn, $get_patient_id_query, 'id_patient') === -1){
            $new_patient_query = "INSERT INTO patients (prenom_patient, nom_patient) VALUES ('$patient_name', '$patient_last_name');";
            mysqli_query($conn, $new_patient_query);    
        };
        $patient_id = sqlGetID($conn, $get_patient_id_query, 'id_patient');
        $new_rdv_query = "INSERT INTO reservations (id_patient, id_personnel, id_salle, date_heure, besoin) VALUES ($patient_id, $doctor_select, $room_select, '$new_rdv_datetime', '$patient_need');";
        mysqli_query($conn, $new_rdv_query);
        ?>
            <script>
                alert("Rendez-vous ajouté avec succès.");
            </script>
        <?php
        $_POST = array();
    };
?>
