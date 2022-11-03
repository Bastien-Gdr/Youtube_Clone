<?php



    ob_start();

    session_start();

    date_default_timezone_set("Europe/Paris");

    // On essaie de se connecter à la bdd

    try{
        $con = new PDO('mysql:dbname=Youtube;host=localhost',
                        'root',
                        'root');

        $con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
    }

    // Si on arrive pas à se connecteer, attrape les erreurs
    catch(PDOException $e){

        echo 'La connexion a échoué : ' . $e->getMessage();

    }
    