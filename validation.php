<?php

    require_once('includes/header.php');
    require_once('classes/VideoUploadData.php');
    require_once('classes/VideoProcessor.php');

    if(!isset($_POST['uploadBtn'])){
        echo 'Aucune information n\'a été envoyé !';
        exit();
    }

    // 1 - Création d'une classe pour le chargement des données avec SQL

    $videoUploadData = new VideoUploadData(
        $_FILES['fileInput'],
        $_POST['titleInput'],
        $_POST['descriptionInput'],
        $_POST['privacyInput'],
        $_POST['categoriesInput'],
        $user->getUsername()
    );

    // 2 - Vérifier le données

    $videoProcessor = new VideoProcessor($con);

    $isSuccessful = $videoProcessor->upload($videoUploadData);



    // 3 - Vérification que le chargement s'est bien passé

    if($isSuccessful){
        echo "<div class='alert alert-success'>La vidéo a été chargée avec succés</div>";
    }