<?php
    declare(strict_types=1);
    date_default_timezone_set('Europe/Paris');

    # Global classes
    class BasicSqlBuilder{
        private string $array_type;
        private string $query;
        private array $query_parameters;

        private function arrayTypeCheck(array $ARRAY, string $EXPECTED){
            if(array_keys($ARRAY) === range(0, count($ARRAY) - 1)){ //Checks if the given array is an indexed array...
                $this->array_type = 'indexed';
            }
            else{ //...else it's then an associative array.
                $this->array_type = 'associative';
            };
            if($this->array_type !== $EXPECTED){
                trigger_error(ucfirst($EXPECTED)." array expected, $this->array_type array given.", E_USER_ERROR);
            }
            else{return true;};
        }
    
        private function buildSelectQuery(string $TABLE, array $DATA, array $CONDITIONS = []) : string {
            if(sizeof($DATA) == 0){trigger_error("Datas expected, none given.", E_USER_ERROR);};
            if(self::arrayTypeCheck($DATA, 'indexed')){
                $this->query = "SELECT ";
                foreach($DATA as $key => $value){
                    $this->query .= "$value, ";
                };
                $this->query = rtrim($this->query, ', ');
                $this->query .= " FROM $TABLE ";
                if(sizeof($CONDITIONS) > 0 && self::arrayTypeCheck($CONDITIONS, 'associative')){
                    $this->query .= " WHERE ";
                    foreach($CONDITIONS as $key => $value){
                        $this->query .= "$key = ? AND ";
                        $this->query_parameters[] = $value;
                    };
                    $this->query = rtrim($this->query, " AND ");
                };
                $this->query .= ';';
                return $this->query;
            };
        }

        private function buildUpdateQuery(string $TABLE, array $DATA, array $CONDITIONS = []) : string {
            if(sizeof($DATA) == 0){trigger_error("Datas expected, none given.", E_USER_ERROR);};
            if(self::arrayTypeCheck($DATA, 'associative')){
                $this->query = "UPDATE $TABLE SET ";
                foreach($DATA as $key => $value){
                    $this->query .= "$key = ?, ";
                    $this->query_parameters[] = $value;
                };
                $this->query = rtrim($this->query, ', ');
                if(sizeof($CONDITIONS) > 0 && self::arrayTypeCheck($CONDITIONS, 'associative')){
                    $this->query .= " WHERE ";
                    foreach($CONDITIONS as $key => $value){
                        $this->query .= "$key = ? AND ";
                        $this->query_parameters[] = $value;
                    };
                    $this->query = rtrim($this->query, " AND ");
                }
                else{trigger_error("Conditions expected, none given.", E_USER_ERROR);};
                $this->query .= ';';
                return $this->query;
            };
        }

        private function buildDeleteQuery(string $TABLE, array $CONDITIONS = []) : string {
            $this->query = "DELETE FROM $TABLE WHERE ";
            if(sizeof($CONDITIONS) > 0 && self::arrayTypeCheck($CONDITIONS, 'associative')){
                foreach($CONDITIONS as $key => $value){
                    $this->query .= $key." = ? AND ";
                    $this->query_parameters[] = $value;
                };
                $this->query = rtrim($this->query, " AND ");
            }
            else{trigger_error("Conditions expected, none given.", E_USER_ERROR);};
            $this->query .= ';';
            return $this->query;
        }

        protected function buildQuery(PDO $DATABASE, string $TYPE, string $TABLE, array $DATA, array $CONDITIONS = []) : array {
            switch($TYPE){
                case "SELECT" :{
                    $this->query = self::buildSelectQuery($TABLE, $DATA, $CONDITIONS);
                    break;
                };
                case "UPDATE" :{
                    $this->query = self::buildUpdateQuery($TABLE, $DATA, $CONDITIONS);
                    break;
                };
                case "DELETE" :{
                    if(sizeof($CONDITIONS) == 0){$CONDITIONS = $DATA;};
                    $this->query = self::buildDeleteQuery($TABLE, $CONDITIONS);
                    break;
                };
                default :{
                    trigger_error('Invalid query type : must be "SELECT", "UPDATE", or "DELETE".', E_USER_ERROR);
                    break;
                };
            };
            $query_execute = $DATABASE->prepare($this->query);
            $query_execute->execute($this->query_parameters);
            $this->query_parameters = [];
            $this->array_type = '';
            $this->query = '';
            if($result = $query_execute->fetch()){
                unset($query_execute);
                return $result;
            }
            else{return [];};
        }
    };

    class User extends BasicSqlBuilder{
        private int $privilege_level;
        private int $id;
        private string $profession;
        private string $mail;
        private string $full_name;

        public function InitUser(PDO $DATABASE, string $USERNAME, string $PASSWORD) : bool {
            $datas = [
                "id_personnel",
                "nom_personnel",
                "prenom_personnel",
                "profession",
                "mail",
                "niveau_privilege"
            ];
            $conditions = [
                "mot_de_passe" => $PASSWORD,
                "identifiant" => $USERNAME
            ];
            $user_info = self::buildQuery($DATABASE, "SELECT", "personnel", $datas, $conditions);
            if(sizeof($user_info) > 0){
                $this->id = $user_info['id_personnel'];
                $this->full_name = $user_info['prenom_personnel'].' '.$user_info['nom_personnel'];
                $this->profession = $user_info['profession'];
                $this->mail = $user_info['mail'];
                $this->privilege_level = $user_info['niveau_privilege'];
                return true;
            }
            else{return false;};
        }
        public function getFullName() : string {return $this->full_name;}
        public function getProfession() : string {return $this->profession;}
        public function getPrivilegeLevel() : int {return $this->privilege_level;}
        public function getID() : int {return $this->id;}
        public function getMail() : string {return $this->mail;}
        public function setMail(PDO $DATABASE, string $MAIL) : void {
            $datas = ["mail" => $MAIL];
            $conditions = ["id_personnel" => $this->id];
            $user_info = self::buildQuery($DATABASE, "UPDATE", "personnel", $datas, $conditions);
            $this->mail = $MAIL;
        }
        public function setPassword(PDO $DATABASE, string $PASSWORD) : void {
            $datas = ["mot_de_passe" => $PASSWORD];
            $conditions = ["id_personnel" => $this->id];
            $user_info = self::buildQuery($DATABASE, "UPDATE", "personnel", $datas, $conditions);
        }
    };
    
    # Global use functions
    function enlog($LOG, $DISPLAY){ //Pushes some logs into a text file and to the Arduino board.
        if($DISPLAY == true){echo "* ".$LOG;}; //Sends the log to the Arduino board.
        $fp = fopen("log.txt", "a"); //Opens the log text file in "append" mode.
        fwrite($fp, "• ".date(DATE_RFC2822)." : ".$LOG.PHP_EOL); //Pushes the date and time of the log message and its content.
        fclose($fp); //Closes the log text file.
    };
    function doorControl($ORDER){ //Handles order sending to Arduino board.
        if($socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP)){
            $udp_port = 8888;
            $arduino_ip = '192.168.10.50';
            socket_sendto($socket, $ORDER, strlen($ORDER), 0, $arduino_ip, $udp_port);
            //socket_recvfrom($socket, $udp_buffer, 64, 0, $arduino_ip, $udp_port);
            //echo "Acknowledgement : $udp_buffer<br>";
            sleep(1);
            if($ORDER == '%'){$order_type = "déverrouillée";};
            if($ORDER == '#'){$order_type = "ouverte";};
            enlog("Porte $order_type depuis le secrétariat.", false);
        }
        else{echo("Impossible de créer le socket.<br>");};
    };

    # Global use variables
    $privilege_levels = [ //Privilege levels for the staff members, from lowest to highest.
        "Personnel extérieur",
        "Médecin",
        "Médecin en chef",
        "Secrétaire-gestionnaire"
    ];

    # Session and connection to database init
    session_start();
    $errors = []; //Used to collect errors if some happen.
    $pdo_options = [ //Some options to configure the PDO connection.
        PDO::ATTR_EMULATE_PREPARES => false, //Turn off emulation mode for "real" prepared statements.
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //Turn on errors in the form of exceptions.
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //Makes the default fetch be an associative array.
    ];
    try{
        $conn = new PDO("mysql:host=localhost:3307;dbname=cabinet;charset=utf8mb4", "root", "", $pdo_options); //Connection to the database. # $conn = new PDO("mysql:host=mariadb:3306;dbname=cabinet;charset=utf8mb4", "root", "root", $pdo_options);
    }
    catch(Exception $e){echo "Connection failed : ".$e->getMessage();};
    if(isset($_SESSION['user'])){
        $user = $_SESSION['user'];
    };

    # Staff registration //À MODIFIER
    if(isset($_POST['staff_register'])){
        $new_name = ucfirst(trim($_POST['new_staff_name']));
        $new_last_name = ucfirst(trim($_POST['new_staff_last_name']));
        $new_profession = trim($_POST['new_staff_profession']);
        $new_user_login = trim($_POST['new_staff_user_login']);
        $new_user_mail = trim($_POST['new_staff_mail']);
        $new_user_password = sha1($_POST['new_staff_password']);
        $new_user_confirm_password = sha1($_POST['new_staff_confirm_password']);
        $new_user_level = $_POST['new_staff_level'];
        //$new_user_access_code = trim($_POST['new_staff_access_code']);
        $new_user_access_type = trim($_POST['new_staff_access_type']);
        if($new_user_password != $new_user_confirm_password){ //Checks if passwords match.
            $errors[] = "Les mots de passe ne correspondent pas.";
            ?>
            <script>
                alert("Les mots de passe ne correspondent pas.");
            </script>
            <?php
        };
        $login_check_query = $conn->prepare("SELECT id_personnel FROM personnel WHERE identifiant=?;");
        $login_check_query->execute([$new_user_login]);
        if($login_check_query->rowCount() > 0){ //Checks if user login already exists.
            $errors[] = "Identifiant déjà utilisé.";
            ?>
            <script>
                alert("Identifiant déjà utilisé.");
            </script>
            <?php
        };
        $user_check_query = $conn->prepare("SELECT id_personnel FROM personnel WHERE prenom_personnel=? AND nom_personnel=?;");
        $user_check_query->execute([$new_name, $new_last_name]);
        if($user_check_query->rowCount() > 0){ //Check if user already exists.
            $errors[] = "Cette personne est déjà répertoriée.";
            ?>
            <script>
                alert("Cette personne est déjà répertoriée.");
            </script>
            <?php
        };
        if(count($errors) == 0){ //If no errors, register.
            $insert_query = $conn->prepare("INSERT INTO personnel (prenom_personnel, nom_personnel, profession, identifiant, mail, mot_de_passe, niveau_privilege) VALUES (?, ?, ?, ?, ?, ?, ?);");
            $insert_query->execute([$new_name, $new_last_name, $new_profession, $new_user_login, $new_user_mail, $new_user_password, $new_user_level]);
            ?>
            <script>
                alert("Personnel enregistré avec succès.");
            </script>
            <?php
            header("Refresh: 0; url=staff_manage.php");
        }
        else{header("Refresh: 0; url=staff_add.php");};
    };

	# Room registration
	if(isset($_POST['room_register'])){
        $new_room_name = ucfirst(trim($_POST['new_room_name']));
        $room_check_query = $conn->prepare("SELECT id_salle FROM salles WHERE nom_salle=?");
        $room_check_query->execute([$new_room_name]);
        if($room_check_query->rowCount() > 0){ //Check if room already exists.
            $errors[] = "Cette salle est déjà répertoriée.";
            ?>
            <script>
                alert("Cette salle est déjà répertoriée.");
            </script>
            <?php
        };
        if(count($errors) == 0){ //If no errors, register.
            $insert_query = $conn->prepare("INSERT INTO salles (nom_salle) VALUES (?);");
            $insert_query->execute([$new_room_name]);
            ?>
            <script>
                alert("Salle enregistrée avec succès.");
            </script>
            <?php
            header("Refresh: 0; url=rooms_manage.php");
        };
    };

    # Patient registration
    if(isset($_POST['patient_register'])){
        $new_patient_name = ucfirst(trim($_POST['new_patient_name']));
        $new_patient_last_name = ucfirst(trim($_POST['new_patient_last_name']));
        $new_patient_number = trim($_POST['new_patient_number']);
        $new_patient_ssn = trim($_POST['new_patient_ssn']);
        $new_patient_address = trim($_POST['new_patient_address']);
        $new_patient_town = ucfirst(trim($_POST['new_patient_town']));
        $patient_check_query = $conn->prepare("SELECT id_patient FROM patients WHERE prenom_patient=? AND nom_patient=?;");
        $patient_check_query->execute([$new_patient_name, $new_patient_last_name]);
        if($patient_check_query->rowCount() > 0){ //Check if patient already exists.
            $errors[] = "Ce(tte) patient(e) est déjà répertorié(e).";
            ?>
            <script>
                alert("Ce(tte) patient(e) est déjà répertorié(e).");
            </script>
            <?php
        };
        if(count($errors) == 0){ //If no errors, register.
            $insert_query = $conn->prepare("
                INSERT INTO patients (prenom_patient, nom_patient, numero_patient, numero_securite_sociale, adresse_patient, ville_patient)
                VALUES (?, ?, ?, ?, ?, ?);
            ");
            $insert_query->execute([
                $new_patient_name,
                $new_patient_last_name,
                $new_patient_number,
                $new_patient_ssn,
                $new_patient_address,
                $new_patient_town
            ]);
            ?>
            <script>
                alert("Patient(e) enregistré(e) avec succès.");
            </script>
            <?php
            header("Refresh: 0; url=patients_manage.php");
        };
    };

    # Login
    if(isset($_POST['login'])){ //Check if Login button is pressed.
        $password = sha1($_POST['psswrd']);
        $login = trim($_POST['user_login']);
        $user = new User;
        if($user->InitUser($conn, $login, $password)){
            $_SESSION['user'] = $user;
            header("Refresh: 0; url=main.php");
        }
        else{
            ?>
            <script>
                alert("Identifiant et/ou mot de passe incorrect.");
            </script>
            <?php
        };
    };

    # Doorcode changing
    if(isset($_POST['change_doorcode'])){
        $current_doorcode = sha1($_POST['current_doorcode']);
        $new_doorcode = sha1($_POST['new_doorcode']);
        $confirm_new_doorcode = sha1($_POST['confirm_new_doorcode']);
        if($new_doorcode != $confirm_new_doorcode){ //Checks if new doorcodes match.
            $errors[] = "Les codes ne correspondent pas.";
            ?>
            <script>
                alert("Les codes ne correspondent pas.");
            </script>
            <?php
        };
        $current_doorcode_query = $conn->prepare("SELECT id_code FROM code_visiophone WHERE mdp_code = ? ;");
        $current_doorcode_query->execute([$current_doorcode]);
        if($current_doorcode_query->rowCount() == 0){ //Checks if typed current doorcode exists.
            $errors[] = "Le code actuel saisi est incorrect.";
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
        };
        unset($row);
    };

    # New rendezvous creating
    if(isset($_POST['rdv_register'])){
        $patient_name = ucfirst(trim($_POST['patient_name']));
        $patient_last_name = ucfirst(trim($_POST['patient_last_name']));
        $patient_need = ucfirst(trim($_POST['patient_need']));
        $patient_number = trim($_POST['patient_number']);
        $doctor_select = $_POST['doctor_select'];
        $room_select = $_POST['room_select'];
        $new_rdv_datetime = $_POST['rdv_datetime'];
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
    };

    # Current user password updating
    if(isset($_POST['password_update'])){ //Handling password update.
        $current_password = sha1($_POST['current_password']);
        $new_password = sha1($_POST['new_password']);
        $confirm_new_password = sha1($_POST['confirm_new_password']);
        if($new_password != $confirm_new_password){ //Checks if passwords match.
            $errors[] = "Les mots de passe ne correspondent pas.";
            ?>
            <script>
                alert("Les mots de passe ne correspondent pas.");
            </script>
            <?php
        };
        $current_password_query = $conn->prepare("SELECT mot_de_passe FROM personnel WHERE id_personnel = ? ;");
        $current_password_query->execute([$user->getID()]);
        if($current_password != ($current_password_query->fetch())['mot_de_passe']){ //Checks if typed current password exists.
            $errors[] = "Le mot de passe actuel saisi est incorrect.";
            ?>
            <script>
                alert("Le mot de passe actuel saisi est incorrect.");
            </script>
            <?php
        };
        if(count($errors) == 0){
            $user->setPassword($conn, $new_password);
            ?>
            <script>
                alert("Mot de passe modifié avec succès.");
            </script>
            <?php
        };
        header("Refresh: 0; url=main.php");
    };

    # Current user mail updating
    if(isset($_POST['mail_update'])){
        $new_mail = trim($_POST['mail']);
        $user->setMail($conn, $new_mail);
        ?>
        <script>
            alert("Adresse mail modifiée avec succès.");
        </script>
        <?php
        header("Refresh: 0; url=main.php");
    };

    # Patient information updating
    if(isset($_GET['ptid_u'])){ //Hiding the patient ID in the URL bar.
        $_SESSION['u_patient_id'] = $_GET['ptid_u'];
        header("Refresh: 0; url=patient_update.php");
    };
    if(isset($_POST['patient_update'])){ //Handling patient update.
        $u_patient_id = $_SESSION['u_patient_id'];
        $u_patient_name = ucfirst(trim($_POST['u_patient_name']));
        $u_patient_last_name = ucfirst(trim($_POST['u_patient_last_name']));
        $u_patient_number = trim($_POST['u_patient_number']);
        $u_patient_ssn = trim($_POST['u_patient_ssn']);
        $u_patient_address = trim($_POST['u_patient_address']);
        $u_patient_town = ucfirst(trim($_POST['u_patient_town']));
        $patient_update_query = $conn->prepare("
            UPDATE patients
            SET prenom_patient=?, nom_patient=?, numero_patient=?, numero_securite_sociale=?, adresse_patient=?, ville_patient=?
            WHERE id_patient=?;
        ");
        $patient_update_query->execute([
            $u_patient_name,
            $u_patient_last_name,
            $u_patient_number,
            $u_patient_ssn,
            $u_patient_address,
            $u_patient_town,
            $u_patient_id
        ]);
        ?>
        <script>
            alert("Patient mis à jour avec succès.");
        </script>
        <?php
        unset($_SESSION['u_patient_id']);
        header("Refresh: 0; url=patients_manage.php");
    };
    if(isset($_GET['u_pt_cncl'])){ //Patient update canceling.
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
        $u_room_name = ucfirst(trim($_POST['u_room_name']));
        $room_update_query = $conn->prepare("UPDATE salles SET nom_salle=? WHERE id_salle=?;");
        $room_update_query->execute([$u_room_name, $u_room_id]);
        ?>
        <script>
            alert("Salle mise à jour avec succès.");
        </script>
        <?php
        unset($_SESSION['u_room_id']);
        header("Refresh: 0; url=rooms_manage.php");
    };
    if(isset($_POST['room_update_cancel'])){ //Room information update canceling.
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
            ucfirst(trim($_POST['urdv_patient_need'])),
            $_SESSION['urdv_patient_id'],
            $_POST['urdv_doctor'],
            $_POST['urdv_room'],
            $_POST['urdv_datetime'],
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
        header("Refresh: 0; url=rdv_manage.php");
    };
    if(isset($_POST['rdv_update_cancel'])){ //Rendezvous update canceling.
        unset($_SESSION['u_patient_id']);
        unset($_SESSION['urdv_patient_id']);
        header("Refresh: 0; url=rdv_manage.php");
    };
?>