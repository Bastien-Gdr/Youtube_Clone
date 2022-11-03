<?php
     require_once ('includes/config.php');
     require_once ('classes/User.php');
     require_once ('classes/Video.php');
     require_once ('classes/VideoGrid.php');
     require_once ('classes/VideoGridItem.php');
     require_once ('classes/SubscriptionProvider.php');
     require_once ('classes/NavProvider.php');


    // $usernameLoggedIn = isset($_SESSION['userLoggedIn']) ? $_SESSION['userLoggedIn'] : '';

    $usernameLoggedIn = User::isLoggedIn() ? $_SESSION["userLoggedIn"] : "";

     $user = new User($con,$usernameLoggedIn);

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youtube</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <script src="assets/js/subscribe.js"></script>



</head>

<body>

    <div id="container">
        <header class="d-flex">
            <div>
                <button class="navShowHide">
                    <img src="assets/images/icons/menu.png" alt="menu">
                </button>
            </div>
        
                <a href="index.php" class="logoContainer">
                    <img src="assets/images/logo-youtube.png" alt="Youtube">
                </a>
        

            <div class="searchBarContainer">
                <form action="search.php" method="get">
                    <input type="text" name="term" placeholder="Rechercher" class="searchBar">
                    <button class="searchBtn">
                        <img src="assets/images/icons/search.png" alt="">
                    </button>
                </form>
            </div>

            <div class="rightIcons">
                <a href="upload.php">
                    <img src="assets/images/icons/upload.png" alt="">
                </a>
                <a href="signUp.php">
                    <img src="assets/images/default.png" alt="">
                </a>
            </div>


        </header>

        <aside id="sideNavContainer">
            <?php 
                $navProvider = new NavProvider($con,$user);

                echo $navProvider->create();

            ?>
        </aside>

        <section id="mainSectionContainer" class="leftPadding">
            <div id="mainContent" class="container-fluid">