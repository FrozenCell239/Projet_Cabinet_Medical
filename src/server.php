<?php
    date_default_timezone_set('Europe/Paris');

    # Global use functions
    function enlog($LOG, $DISPLAY){ //Pushes some logs into a text file and to the Arduino board.
        if($DISPLAY == true){echo "* ".$LOG;}; //Sends the log to the Arduino board.
        $fp = fopen("log.txt", "a"); //Opens the log text file in "append" mode.
        fwrite($fp, "• ".date(DATE_RFC2822)." : ".$LOG); //Pushes the date and time of the log message and its content.
        fclose($fp); //Closes the log text file.
    };
    function doorControl($ORDER){ //Handles order sending to Arduino board.
        if($socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP)){
            $udp_port = 8888;
            $arduino_ip = '192.168.1.177';
            socket_sendto($socket, $ORDER, strlen($ORDER), 0, $arduino_ip, $udp_port);
            //socket_recvfrom($socket, $udp_buffer, 64, 0, $arduino_ip, $udp_port);
            //echo "Acknowledgement : $udp_buffer<br>";
            sleep(1);
            if($ORDER == '$'){$order_type = "unlocked";};
            if($ORDER == '#'){$order_type = "opened";};
            enlog("Door $order_type from office.".PHP_EOL, false);
        }
        else{echo("Can't create socket.<br>");};
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
                        <li class="nav-item">
                            <a class="nav-link" href="password_update.php">Modifier mot de passe</a>
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
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="password_update.php">Modifier mot de passe</a>
                        </li>
                        <form class="d-flex" action="logout.php" method="post">
                            <button class="btn btn-primary" type="submit">Se déconnecter</button>
                        </form>
                    </ul>
                </div>
            </div>
        </nav>
    ';

    # Session and connection to database init
    session_start();
    $errors = array(); //Used to collect errors if some happen.
    $pdo_options = [ //Some options to configure the PDO connection.
        PDO::ATTR_EMULATE_PREPARES => false, //Turn off emulation mode for "real" prepared statements.
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //Turn on errors in the form of exceptions.
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //Makes the default fetch be an associative array.
    ];
    try{
        $conn = new PDO("mysql:host=localhost:3307;dbname=cabinet;charset=utf8mb4", "root", "", $pdo_options); //Connection to the database.
    }
    catch(Exception $e){echo "Connection failed : " . $e->getMessage();};

    # Navbar setting
    if(isset($_SESSION['profession']) && $_SESSION['profession'] == 'secretaire'){$navbar = $secretary_navbar;};
    if(isset($_SESSION['profession']) && $_SESSION['profession'] != 'secretaire'){$navbar = $non_secretary_navbar;};

    # Staff registration
    if(isset($_POST['staff_register'])){
        $new_name = filter_var(ucfirst(trim($_POST['new_staff_name'])), FILTER_SANITIZE_STRING);
        $new_last_name = filter_var(ucfirst(trim($_POST['new_staff_last_name'])), FILTER_SANITIZE_STRING);
        $new_profession = filter_var(trim($_POST['new_staff_profession']), FILTER_SANITIZE_STRING);
        $new_user_login = filter_var(trim($_POST['new_staff_user_login']), FILTER_SANITIZE_STRING);
        $new_user_mail = filter_var(trim($_POST['new_staff_mail']), FILTER_SANITIZE_STRING);
        $new_user_password = sha1($_POST['new_staff_password']);
        $new_user_confirm_password = sha1($_POST['new_staff_confirm_password']);
        if(isset($_POST['new_staff_admin'])){$new_admin = 1;}
        else{$new_admin = 0;};
        if($new_user_password != $new_user_confirm_password){ //Checks if passwords match.
            array_push($errors, "Les mots de passe ne correspondent pas.");
            ?>
            <script>
                alert("Les mots de passe ne correspondent pas.");
            </script>
            <?php
        };
        $login_check_query = $conn->prepare("SELECT id_personnel FROM personnel WHERE identifiant=?;");
        $login_check_query->execute([$new_user_login]);
        if($login_check_query->rowCount() > 0){ //Checks if user login already exists.
            array_push($errors, "Identifiant déjà utilisé.");
            ?>
            <script>
                alert("Identifiant déjà utilisé.");
            </script>
            <?php
        };
        $user_check_query = $conn->prepare("SELECT id_personnel FROM personnel WHERE prenom_personnel=? AND nom_personnel=?;");
        $user_check_query->execute([$new_name, $new_last_name]);
        if($user_check_query->rowCount() > 0){ //Check if user already exists.
            array_push($errors, "Cette personne est déjà répertoriée.");
            ?>
            <script>
                alert("Cette personne est déjà répertoriée.");
            </script>
            <?php
        };
        if(count($errors) == 0){ //If no errors, register.
            $insert_query = $conn->prepare("INSERT INTO personnel (prenom_personnel, nom_personnel, profession, identifiant, mail, mot_de_passe, admin) VALUES (?, ?, ?, ?, ?, ?, ?);");
            $insert_query->execute([$new_name, $new_last_name, $new_profession, $new_user_login, $new_user_mail, $new_user_password, $new_admin]);
        };
        $_POST = array();
    };

	# Room registration
	if(isset($_POST['room_register'])){
        $new_room_name = filter_var(ucfirst(trim($_POST['new_room_name'])), FILTER_SANITIZE_STRING);
        $room_check_query = $conn->prepare("SELECT id_salle FROM salles WHERE nom_salle=?");
        $room_check_query->execute([$new_room_name]);
        if($room_check_query->rowCount() > 0){ //Check if room already exists.
            array_push($errors, "Cette salle est déjà répertoriée.");
            ?>
            <script>
                alert("Cette salle est déjà répertoriée.");
            </script>
            <?php
        };
        if(count($errors) == 0){ //If no errors, register.
            $insert_query = $conn->prepare("INSERT INTO salles (nom_salle) VALUES (?);");
            $insert_query->execute([$new_room_name]);
        };
        $_POST = array();
    };

    # Patient registration
    if(isset($_POST['patient_register'])){
        $new_patient_name = filter_var(ucfirst(trim($_POST['new_patient_name'])), FILTER_SANITIZE_STRING);
        $new_patient_last_name = filter_var(ucfirst(trim($_POST['new_patient_last_name'])), FILTER_SANITIZE_STRING);
        $new_patient_number = filter_var(trim($_POST['new_patient_number']), FILTER_SANITIZE_STRING);
        $patient_check_query = $conn->prepare("SELECT id_patient FROM patients WHERE prenom_patient=? AND nom_patient=?;");
        $patient_check_query->execute([$new_patient_name, $new_patient_last_name]);
        if($patient_check_query->rowCount() > 0){ //Check if patient already exists.
            array_push($errors, "Ce(tte) patient(e) est déjà répertorié(e).");
            ?>
            <script>
                alert("Ce(tte) patient(e) est déjà répertorié(e).");
            </script>
            <?php
        };
        if(count($errors) == 0){ //If no errors, register.
            $insert_query = $conn->prepare("INSERT INTO patients (prenom_patient, nom_patient, numero_patient) VALUES (?, ?, ?);");
            $insert_query->execute([$new_patient_name, $new_patient_last_name, $new_patient_number]);
        };
        $_POST = array();
    };

    # Login
    if(isset($_POST['login'])){ //Check if Login button is pressed.
        $password = sha1($_POST['psswrd']);
        $login = filter_var(trim($_POST['user_login']), FILTER_SANITIZE_STRING);
        $login_query = $conn->prepare("SELECT id_personnel, identifiant, profession, nom_personnel, prenom_personnel, admin FROM personnel WHERE mot_de_passe=? AND identifiant=?;");
        $login_query->execute([$password, $login]);
        if($row = $login_query->fetch()){
            $_SESSION['user_id'] = $row['id_personnel'];
            $_SESSION['username'] = $row['identifiant'];
            $_SESSION['profession'] = $row['profession'];
            $_SESSION['name'] = $row['prenom_personnel'];
            $_SESSION['last_name'] = $row['nom_personnel'];
            $_SESSION['admin'] = $row['admin'];
            $_POST = array();
            unset($row);
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
        $current_doorcode_query = $conn->prepare("SELECT id_code FROM code_visiophone WHERE mdp_code = ? ;");
        $current_doorcode_query->execute([$current_doorcode]);
        if($current_doorcode_query->rowCount() == 0){ //Checks if typed current doorcode exists.
            array_push($errors, "Le code actuel saisi est incorrect.");
            ?>
            <script>
                alert("Le code actuel saisi est incorrect.");
            </script>
            <?php
        }
        else{$doorcode_id = ($current_doorcode_query->fetch())['id_code'];};
        if(count($errors) == 0){
            $doorcode_change_query = $conn->prepare("UPDATE code_visiophone SET mdp_code=? WHERE id_code=?;");
            $doorcode_change_query->execute([$new_doorcode, $doorcode_id]);
            ?>
            <script>
                alert("Code modifié avec succès.");
            </script>
            <?php
            //$doctors_mail_query = $conn->prepare("SELECT mail FROM personnel;");
            //$doctors_mail_query->execute();
            //while($row = $doctors_mail_query->fetch()){
            //    mail($row['mail'], "Modification du code de porte du cabinet.", "TEST");
            //};
        };
        unset($row);
        $_POST = array();
    };

    # New rendezvous creating
    if(isset($_POST['rdv_register'])){
        $patient_name = filter_var(ucfirst(trim($_POST['patient_name'])), FILTER_SANITIZE_STRING);
        $patient_last_name = filter_var(ucfirst(trim($_POST['patient_last_name'])), FILTER_SANITIZE_STRING);
        $patient_need = filter_var(ucfirst(trim($_POST['patient_need'])), FILTER_SANITIZE_STRING);
        $patient_number = filter_var(trim($_POST['patient_number']), FILTER_SANITIZE_STRING);
        $doctor_select = filter_var($_POST['doctor_select'], FILTER_SANITIZE_STRING);
        $room_select = filter_var($_POST['room_select'], FILTER_SANITIZE_STRING);
        $new_rdv_datetime = filter_var($_POST['rdv_datetime'], FILTER_SANITIZE_STRING);
        $get_patient_id_query = $conn->prepare("SELECT id_patient FROM patients WHERE prenom_patient = ? AND nom_patient = ?;");
        $get_patient_id_query->execute([$patient_name, $patient_last_name]);
        if($get_patient_id_query->rowCount() == 0){
            $new_patient_query = $conn->prepare("INSERT INTO patients (prenom_patient, nom_patient, numero_patient) VALUES (?, ?, ?);");
            $new_patient_query->execute([$patient_name, $patient_last_name, $patient_number]);
            $get_patient_id_query->execute([$patient_name, $patient_last_name]);
            $patient_id = ($get_patient_id_query->fetch())['id_patient'];
        }
        else{
            $patient_id = ($get_patient_id_query->fetch())['id_patient'];
            $update_patient_number = $conn->prepare("UPDATE patients SET numero_patient = ? WHERE  id_patient = ?;");
            $update_patient_number->execute([$patient_number, $patient_id]);
        };
        $new_rdv_query = $conn->prepare("INSERT INTO reservations (id_patient, id_personnel, id_salle, date_heure, besoin) VALUES (?, ?, ?, ?, ?);");
        $new_rdv_query->execute([$patient_id, $doctor_select, $room_select, $new_rdv_datetime, $patient_need]);
        ?>
            <script>
                alert("Rendez-vous ajouté avec succès.");
            </script>
        <?php
        $_POST = array();
    };

    # Password updating
    if(isset($_POST['password_update'])){ //Handling password update.
        $current_password = sha1($_POST['current_password']);
        $new_password = sha1($_POST['new_password']);
        $confirm_new_password = sha1($_POST['confirm_new_password']);
        if($new_password != $confirm_new_password){ //Checks if passwords match.
            array_push($errors, "Les codes ne correspondent pas.");
            ?>
            <script>
                alert("Les codes ne correspondent pas.");
            </script>
            <?php
        };
        $current_password_query = $conn->prepare("SELECT mot_de_passe FROM personnel WHERE id_personnel = ? ;");
        $current_password_query->execute([$_SESSION['user_id']]);
        if($current_password != ($current_password_query->fetch())['mot_de_passe']){ //Checks if typed current password exists.
            array_push($errors, "Le mot de passe actuel saisi est incorrect.");
            ?>
            <script>
                alert("Le mot de passe actuel saisi est incorrect.");
            </script>
            <?php
        };
        if(count($errors) == 0){
            $password_update_query = $conn->prepare("UPDATE personnel SET mot_de_passe=? WHERE id_personnel=?;");
            $password_update_query->execute([$new_password, $_SESSION['user_id']]);
            ?>
            <script>
                alert("Mot de passe modifié avec succès.");
            </script>
            <?php
        };
        $_POST = array();
        header("Refresh: 0; url=main.php");
    };

    # Patient information updating
    if(isset($_GET['ptid_u'])){ //Hiding the patient ID in the URL bar.
        $_SESSION['u_patient_id'] = $_GET['ptid_u'];
        header("Refresh: 0; url=patient_update.php");
    };
    if(isset($_POST['patient_update'])){ //Handling patient update.
        $u_patient_id = $_SESSION['u_patient_id'];
        $u_patient_name = filter_var(ucfirst(trim($_POST['u_patient_name'])), FILTER_SANITIZE_STRING);
        $u_patient_last_name = filter_var(ucfirst(trim($_POST['u_patient_last_name'])), FILTER_SANITIZE_STRING);
        $u_patient_number = filter_var(trim($_POST['u_patient_number']), FILTER_SANITIZE_STRING);
        $patient_update_query = $conn->prepare("UPDATE patients SET prenom_patient=?, nom_patient=?, numero_patient=? WHERE id_patient=?;");
        $patient_update_query->execute([$u_patient_name, $u_patient_last_name, $u_patient_number, $u_patient_id]);
        ?>
        <script>
            alert("Patient mis à jour avec succès.");
        </script>
        <?php
        unset($_SESSION['u_patient_id']);
        $_POST = array();
        header("Refresh: 0; url=patients_manage.php");
    };
    if(isset($_POST['patient_update_cancel'])){ //Patient information update canceling.
        $_POST = array();
        unset($_SESSION['u_patient_id']);
        header("Refresh: 0; url=patients_manage.php");
    };

    # Room information updating
    if(isset($_GET['rid_u'])){ //Hiding the room ID in the URL bar.
        $_SESSION['u_room_id'] = $_GET['rid_u'];
        header("Refresh: 0; url=room_update.php");
    };
    if(isset($_POST['room_update'])){ //Handling room update.
        $u_room_id = $_SESSION['u_room_id'];
        $u_room_name = filter_var(ucfirst(trim($_POST['u_room_name'])), FILTER_SANITIZE_STRING);
        $room_update_query = $conn->prepare("UPDATE salles SET nom_salle=? WHERE id_salle=?;");
        $room_update_query->execute([$u_room_name, $u_room_id]);
        ?>
        <script>
            alert("Salle mise à jour avec succès.");
        </script>
        <?php
        unset($_SESSION['u_room_id']);
        $_POST = array();
        header("Refresh: 0; url=rooms_manage.php");
    };
    if(isset($_POST['room_update_cancel'])){ //Room information update canceling.
        $_POST = array();
        unset($_SESSION['u_room_id']);
        header("Refresh: 0; url=rooms_manage.php");
    };

    # Rendezvous updating
    if(isset($_GET['rdvid_u'])){ //Hiding the room ID in the URL bar.
        $_SESSION['u_rdv_id'] = $_GET['rdvid_u'];
        header("Refresh: 0; url=rdv_update.php");
    };
    if(isset($_POST['rdv_update'])){ //Handling rendezvous update.
        $rdv_update_query_options = [
            filter_var(ucfirst(trim($_POST['urdv_patient_need'])), FILTER_SANITIZE_STRING),
            $_SESSION['urdv_patient_id'],
            filter_var($_POST['urdv_doctor'], FILTER_SANITIZE_STRING),
            filter_var($_POST['urdv_room'], FILTER_SANITIZE_STRING),
            filter_var($_POST['urdv_datetime'], FILTER_SANITIZE_STRING),
            $_SESSION['u_rdv_id']
        ];
        $rdv_update_query = $conn->prepare("UPDATE reservations SET besoin=?, id_patient=?, id_personnel=?, id_salle=?, date_heure=? WHERE id_reservation=?;");
        $rdv_update_query->execute($rdv_update_query_options);
        ?>
        <script>
            alert("Rendez-vous modifié avec succès.");
        </script>
        <?php
        unset($_SESSION['u_rdv_id']);
        unset($_SESSION['urdv_patient_id']);
        $_POST = array();
        header("Refresh: 0; url=rdv_manage.php");
    };
    if(isset($_POST['rdv_update_cancel'])){ //Rendezvous update canceling.
        $_POST = array();
        unset($_SESSION['u_patient_id']);
        unset($_SESSION['urdv_patient_id']);
        header("Refresh: 0; url=rdv_manage.php");
    };

    # Tag toggling
    if(isset($_GET['what']) && $_GET['what'] == 10 && isset($_GET['id'])){
        $toggle_query = $conn->prepare("UPDATE badges_visiophone SET actif = 1 - actif WHERE id_badge = ?;");
        $toggle_query->execute([$_GET['id']]);
        echo 'Badge activé/désactivé avec succès.';
        $_GET = array();
    };
?>