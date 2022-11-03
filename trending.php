<?php require_once('includes/header.php');
      require_once('classes/TrendingProvider.php');

    $trendingProvider = new TrendingProvider($con,$user);
    $videos = $trendingProvider->getVideos();

    $videoGrid = new VideoGrid($con,$user->getUsername());

?>

    <div class="largeGridContainer">
        <?php 
            if(sizeof($videos) > 0){
                echo $videoGrid->createLarge($videos,"Videos tendances de la semaine",false);
            }else{
                echo "Aucune tendance cette semaine";
            }
        ?>
    </div>



<?php require_once('includes/footer.php'); ?>