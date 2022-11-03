<?php

 require_once ('../../includes/config.php');

 if(isset($_POST['userTo']) AND isset($_POST['userFrom'])){
    
    $userTo = $_POST['userTo'];
    $userFrom = $_POST['userFrom'];
    

    // Vérifier si l'utilisateur est déjà abonné

    $query = $con->prepare("SELECT * FROM subscribers WHERE userTo=:userTo AND userFrom=:userFrom");
    $query->bindParam(':userTo',$userTo);
    $query->bindParam(':userFrom',$userFrom);

    $query->execute();

    if($query->rowCount() == 0){
        // Pas abonné => insère dans la bdd
        $query = $con->prepare("INSERT INTO subscribers(userTo,userFrom) VALUES(:userTo,:userFrom)");
        $query->bindParam(':userTo',$userTo);
        $query->bindParam(':userFrom',$userFrom);
    
        $query->execute();

    }else{
        // Déjà abonné => supprime l'abonnement
        $query = $con->prepare("DELETE FROM subscribers WHERE userTo=:userTo AND userFrom=:userFrom");
        $query->bindParam(':userTo',$userTo);
        $query->bindParam(':userFrom',$userFrom);
    
        $query->execute();

    }

    // On retourne me nombre d'abonné
    $query = $con->prepare("SELECT * FROM subscribers WHERE userTo=:userTo");
    $query->bindParam(':userTo',$userTo);

    $query->execute();

    echo $query->rowCount();

 }else{
     echo "Un ou plusieurs paramètres sont manquants dans subscribe.php";
 }