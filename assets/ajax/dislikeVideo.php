<?php
    require_once ('../../includes/config.php');
    require_once ('../../classes/Video.php');
    require_once ('../../classes/User.php');

    $username = $_SESSION['userLoggedIn'];
    $videoId = $_POST['videoId'];
    $currentUser = new User($con,$username);
    $video = new Video($con,$videoId,$currentUser);


    echo $video->dislike();