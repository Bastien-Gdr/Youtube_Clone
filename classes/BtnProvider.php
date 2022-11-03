<?php

class BtnProvider{

    public static $signInFunction = "notSignedIn()";

    public static function createLink($link){

        return User::isLoggedIn() ? $link : BtnProvider::$signInFunction;
    }


    public static function createBtn($text,$imgSrc,$action,$class){

        $image = ($imgSrc == null) ? "" : "<img src='$imgSrc'>";
        $action = BtnProvider::createLink($action);

        return "<button class='$class' onclick='$action'>
                    $image
                    <span class='text'>$text</span>
                </button>";

    }

    public static function createProfileBtn($con,$username){
        $user = new User($con,$username);
        $avatar = $user->getAvatar();
        $link = "profile.php?username=$username";

        return "<a href='$link'>
                    <img src='$avatar' class='avatarProfile'>
                </a>";
    }

    public static function createLinkBtn($text,$imgSrc,$href,$class){
        $image = ($imgSrc == null) ? "" : "<img src='$imgSrc'>";

        return "<a href='$href'>
                    <button class='$class'>
                        $image
                        <span class='text'>$text</span>
                    </button>
                 </a>";


    }

    public static function createEditBtn($videoId){

        $href = "editVideo.php?videoId=$videoId";
        $button = BtnProvider::createLinkBtn("Editer",null,$href,"btn btn-primary");

        return "<div class='editVideoBtn'>
                    $button
                </div>";
    }

    public static function createSubscribeBtn($con,$userTo,$userLoggedIn){

        $author = $userTo->getUsername();
        $currentUser = $userLoggedIn->getUsername();

        $isSuscribedTo = $userLoggedIn->isSubscribedTo($author);
        $textBtn = $isSuscribedTo ? "Se désabonner" : "S'abonner";
        $textBtn .= " ".$userTo->getSubscriberCount();

        $classBtn = $isSuscribedTo ? "unsubscribe button" : "subscribe button";
        $action = "subscribe(\"$author\",\"$currentUser\",this)";

        $button = BtnProvider::createBtn($textBtn,null,$action,$classBtn);

        return "<div class='subscribeBtn'>
                    $button
                </div>";

    }

}