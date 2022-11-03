<?php require_once('includes/header.php');
require_once('classes/VideoPlayer.php');
require_once('classes/VideoDetailsSection.php');



if (!isset($_GET['id'])) {
    echo 'Aucune vidéo ne correspond à votre recherche';

    exit;
}


$video = new Video($con, $_GET['id'], $user);
$video->incrementViews();

?>
<script src="assets/js/videoAction.js"></script>
<div class="row">
    <section class="videoPlayer col-md-9">
        <?php
        $videoPlayer = new VideoPlayer($video);

        echo $videoPlayer->create(true);
        ?>

        <!-- 1ere zone d'info -->

        <?php
        $videoDetails = new VideoDetailsSection($con,$video,$user);

        echo $videoDetails->create();
        ?>


        <!-- 2eme zone d'info -->


    </section>

    <aside class="suggestions col-md-3">
        <h3>Suggestions</h3>

        <?php 
            $videoGrid = new VideoGrid($con,$user);

            echo $videoGrid->create(null,null,false);
        ?>

    </aside>
</div>


<?php require_once('includes/footer.php'); ?>