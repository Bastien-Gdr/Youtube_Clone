<?php require_once 'includes/header.php';?>

<div class="home">
    <?php

    $subscriptionProvider = new SubscriptionProvider($con,$user);
    $subscriptionVideos = $subscriptionProvider->getVideos(); 

    $videoGrid = new VideoGrid($con,$user->getUsername());

    if(User::isLoggedIn() && sizeof($subscriptionVideos) > 0){
        echo $videoGrid->create($subscriptionVideos,"Vos abonnements",false);
    }

    echo $videoGrid->create(null,"Recommandations",false);
    ?>
</div>


            


<?php require_once 'includes/footer.php' ?>