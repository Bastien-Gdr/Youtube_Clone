<?php

    require_once ('includes/header.php');
    require_once ('classes/VideoPlayer.php');
    require_once ('classes/VideoFormProvider.php');
    require_once ('classes/VideoUploadData.php');
    require_once ('classes/SelectThumbnail.php');

    if(!User::isLoggedIn()){
        header('Location: SignIn.php');
    }

    if(!isset($_GET['videoId'])){
        echo "Vous devez sélectionner une vidéo";
        exit;
    }

    $video = new Video($con,$_GET['videoId'],$user);

    if($video->getUploadedBy() != $user->getUsername()){
        echo "Vous ne pouvez modifier que les videos dont vous être le créateur !";
        exit;
    }

    $detailsMsg = "";
    // Soumission du formulaire
    if(isset($_POST['saveDetailsBtn'])){
        $videoData = new VideoUploadData(
            null,
            $_POST['titleInput'],
            $_POST['descriptionInput'],
            $_POST['privacyInput'],
            $_POST['categoriesInput'],
            $user->getUsername()
        );

    if($videoData->updateDetails($con,$video->getId())){
        // True
        $detailsMsg = "<div class='alert alert-success'><strong>Les détails de la vidéo ont été mis à jour avec succés</strong></div>";
        $video = new Video($con,$_GET['videoId'],$user);
    }else{
        $detailsMsg = "<div class='alert alert-warning'><strong>Une erreur est survenue</strong></div>";

    }

    }

?>
<script src="assets/js/editVideoAction.js"></script>
<div class="editVideoContainer column">
    <div class="topSection">
        <?php 
            // Affichage de la vidéo
            $videoPlayer = new VideoPlayer($video);

            echo $videoPlayer->create(false);


            // Affichage des vignettes
            $selectedThumbnail = new SelectThumbnail($con,$video);

            echo $selectedThumbnail->create();

        ?>
    </div>

    <div class="bottomSection">
        <?php
            $formProvider = new VideoFormProvider($con);
            echo $formProvider->createdEditDetailsForm($video);
        ?>
    </div>

</div>








<?php    require_once ('includes/footer.php'); ?>