<?php
    session_start();

    $errors = array(); //Used to collect errors if some happen.
    $conn = mysqli_connect('localhost:3307', 'root', '', 'cabinet'); //On Debian Linux : $conn = mysqli_connect('localhost', 'phpmyadmin', 'phpmyadmin', 'cabinet');
    
    # Goes back on main page when thecback home button is pressed.
    if(isset($_POST['back_home'])){
        $_POST = array();
        header("Refresh: 0; url=main.php");
    };

    # Staff registration
    if(isset($_POST['staff_register'])){
        $new_name = trim($_POST['new_staff_name']);
        $new_last_name = trim($_POST['new_staff_last_name']);
        $new_profession = trim($_POST['new_staff_profession']);
        $new_user_login = trim($_POST['new_staff_user_login']);
        $new_password = sha1($_POST['new_staff_password']);
        if(isset($_POST['new_staff_admin'])){$new_admin = 1;}
        else{$new_admin = 0;};
        $new_confirm_password = sha1($_POST['new_staff_confirm_password']);
        unset($_POST['staff_register']);
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
        $patient_name = trim($_POST['new_patient_name']);
        $patient_last_name = trim($_POST['new_patient_last_name']);
        if(isset($_POST['new_patient_need'])){$patient_need = mysqli_real_escape_string($conn, trim($_POST['new_patient_need']));}
        unset($_POST['staff_register']);
        $patient_check_query = "SELECT id_patient FROM patients WHERE prenom_patient='$patient_name' AND nom_patient='$patient_last_name'";
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
                $insert_query = "INSERT INTO patients (prenom_patient, nom_patient, besoin) VALUES ('$patient_name', '$patient_last_name', '$patient_need');";
                $insert_query_result = mysqli_query($conn, $insert_query);
        };
        $_POST = array();
    };

    # Login
    if(isset($_POST['login'])){ //Check if Login button is pressed.
        $password = sha1($_POST['psswrd']);
        $login = trim($_POST['user_login']);
        if(count($errors) == 0){ //If no errors, then log in.
            $login_query = "SELECT identifiant, profession, nom_personnel, prenom_personnel, admin FROM personnel WHERE identifiant='$login' AND mot_de_passe='$password';";
            $login_query_result = mysqli_query($conn, $login_query);
            $select_row = mysqli_fetch_array($login_query_result, MYSQLI_ASSOC);
            if(mysqli_num_rows($login_query_result) > 0){
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
                    alert("Incorrect username or password.");
                </script>
                <?php
            };
        };
        $_POST = array();
    };
?>
