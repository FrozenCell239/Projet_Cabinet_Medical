<?php require_once("header.php"); ?>
<main>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
            <h1 style="margin-top: 20px;">Historique des accès</h1>
            <hr>
            <?php
                define('LOG_FILE', "log.txt"); //Path and name of the log file. Only the name of the file here as all files are in the same folders.
                if(file_exists(LOG_FILE)){
                    $logs = nl2br(file_get_contents(LOG_FILE));
                    echo $logs;
                }
                else{echo "<i>Aucun accès répertorié pour le moment.</i>";};
            ?>
            </div>
        </div>
    </div>
</main>
<?php require_once("footer.php"); ?>