<?php
    session_start();

    $errors = array(); //Used to collect errors if some happen.
    $conn = mysqli_connect('localhost:3307', 'root', '', 'cabinet'); //On Debian Linux : $conn = mysqli_connect('localhost', 'phpmyadmin', 'phpmyadmin', 'cabinet');
    
    if(isset($_POST['back_home'])){
        unset($_POST['back_home']);
        header("Refresh: 0; url=main.php");
    };

    # Staff registration
    if(isset($_POST['staff_register'])){
        $name = $_POST['new_name'];
        $last_name = $_POST['new_last_name'];
        $profession = $_POST['new_profession'];
        $user_login = $_POST['new_user_login'];
        $password = sha1($_POST['new_password']);
        if(isset($_POST['new_admin'])){$admin = 1;}
        else{$admin = 0;};
        $confirm_password = sha1($_POST['new_confirm_password']);
        unset($_POST['staff_register']);
        $login_check_query = "SELECT id_personnel FROM personnel WHERE identifiant='$user_login'";
        $login_check_query_result = mysqli_query($conn, $login_check_query);
        if(mysqli_num_rows($login_check_query_result) > 0){ //Check if user login already exist.
              array_push($errors, "Identifiant déjà utilisé.");
            ?>
            <script>
                alert("Identifiant déjà utilisé.");
            </script>
            <?php
        };
        $user_check_query = "SELECT id_personnel FROM personnel WHERE prenom_personnel='$name' AND nom_personnel='$last_name'";
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
              $insert_query = "INSERT INTO personnel (prenom_personnel, nom_personnel, profession, identifiant, mot_de_passe, admin) VALUES ('$name', '$last_name', '$profession', '$user_login', '$password', '$admin');";
              $insert_query_result = mysqli_query($conn, $insert_query);
        };
    };

	# Room registration
	if(isset($_POST['room_register'])){
        $room_name = $_POST['name'];
        unset($_POST['room_register']);
        $room_check_query = "SELECT id_salle FROM salles WHERE nom_salle='$room_name'";
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
              $insert_query = "INSERT INTO salles (nom_salle) VALUES ('$room_name');";
              $insert_query_result = mysqli_query($conn, $insert_query);
        };
    };

    # Login
    if(isset($_POST['login'])){ //Check if Login button is pressed.
        $username = $_POST['user_login'];
        $password = sha1($_POST['psswrd']);
        unset($_POST['login']);
        if(count($errors) == 0){ //If no errors, then log in.
            $login_query = "SELECT identifiant, profession, nom_personnel, prenom_personnel, admin FROM personnel WHERE identifiant='$username' AND mot_de_passe='$password';";
            $login_query_result = mysqli_query($conn, $login_query);
            $select_row = mysqli_fetch_array($login_query_result, MYSQLI_ASSOC);
            if(mysqli_num_rows($login_query_result) > 0){
                $_SESSION['username'] = $select_row['identifiant'];
                $_SESSION['profession'] = $select_row['profession'];
                $_SESSION['name'] = $select_row['prenom_personnel'];
                $_SESSION['last_name'] = $select_row['nom_personnel'];
                $_SESSION['admin'] = $select_row['admin'];
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
    };
?>
