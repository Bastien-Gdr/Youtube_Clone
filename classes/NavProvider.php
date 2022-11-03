<?php

class NavProvider{

    private $con,$currentUser;

    public function __construct($con,$currentUser){
        $this->con = $con;
        $this->currentUser = $currentUser;
    }

    // Création des liens : text - image - lien
    public function create(){
        $html = $this->createNavItem("Accueil","assets/images/icons/home.png","index.php");
        $html .= $this->createNavItem("Tendances","assets/images/icons/trending.png","trending.php");
        $html .= $this->createNavItem("Abonnements","assets/images/icons/subscriptions.png","subscriptions.php");
        $html .= $this->createNavItem("Vidéos likées","assets/images/icons/thumb_up.png","likeVideos.php");

        if(User::isLoggedIn()){
            $html .= $this->createNavItem("Paramètres","assets/images/icons/settings.png","settings.php");
            $html .= $this->createNavItem("Déconnexion","assets/images/icons/logout.png","logout.php");
            $html .= $this->createSubscriptionsSection();
        }



        return "<div class='navItems'>
                    $html
                </div>";
    }

    private function createNavItem($text,$icon,$link){
        return "<div class='navigation'>
                    <a href='$link'>
                    <img src='$icon'>
                        <span>$text</text>
                    </a>
                </div>";
    }

    private function createSubscriptionsSection(){
        $subscriptions = $this->currentUser->getSubscriptions();

        $title = "<span class='heading'>Abonnements</span>";

        foreach($subscriptions as $sub){
            $subUsername = $sub->getUsername();
            $subAvatar = $sub->getAvatar();

            $title .= $this->createNavItem($subUsername,$subAvatar,"profile.php?username=$subUsername");
        }

            return $title;

    }

}