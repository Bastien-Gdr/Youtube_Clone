<?php require_once('includes/header.php');
      require_once('classes/SubscriptionProvider.php');

    $subscriptionProvider = new SubscriptionProvider($con,$user);
    $videos = $subscriptionProvider->getVideos();

    $videoGrid = new VideoGrid($con,$user->getUsername());

?>

    <div class="largeGridContainer">
        <?php 
            if(sizeof($videos) > 0){
                echo $videoGrid->createLarge($videos,"Vos abonnements",false);
            }else{
                echo "Aucune vidéo cette semaine";
            }
        ?>
    </div>



<?php require_once('includes/footer.php'); ?>