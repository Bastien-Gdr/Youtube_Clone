<?php require_once('includes/header.php');
      require_once('classes/LikedProvider.php');

    $likedProvider = new LikedProvider($con,$user);
    $videos = $likedProvider->getVideos();

    $videoGrid = new VideoGrid($con,$user->getUsername());

?>

    <div class="largeGridContainer">
        <?php 
            if(sizeof($videos) > 0){
                echo $videoGrid->createLarge($videos,"Vos vidéos likées",false);
            }else{
                echo "Je n'ai pas encore liké de vidéo";
            }
        ?>
    </div>



<?php require_once('includes/footer.php'); ?>