<?php

require_once ('classes/BtnProvider.php');

class VideoDetailsControls{

    public function __construct($video,$user){
        $this->video = $video;
        $this->user = $user;
    }

    public function create(){

        // Création de deux bouttons (like/dislike)

        $likeBtn = $this->createLikeBtn();
        $dislikeBtn = $this->createDislikeBtn();

        return "<div class='controls'>
                    $likeBtn
                    $dislikeBtn
                </div>";

    }


    private function createLikeBtn(){

        $videoId = $this->video->getId();
        $text = $this->video->getLikes();
        $imgSrc = "assets/images/icons/thumb_up.png";
        $action = "likeVideo(this,$videoId)";
        $class = "likeBtn";

        if($this->video->alreadyLiked()){
            $imgSrc = "assets/images/icons/thumb-up-active.png";
        }
        return BtnProvider::createBtn($text,$imgSrc,$action,$class);
    }
    


    private function createDislikeBtn(){

        $videoId = $this->video->getId();
        $text = $this->video->getDislikes();
        $imgSrc = "assets/images/icons/thumb_down.png";
        $action = "dislikeVideo(this,$videoId)";
        $class = "dislikeBtn";

        if($this->video->alreadyDisliked()){
            $imgSrc = "assets/images/icons/thumb-down-active.png";
        }
        return BtnProvider::createBtn($text,$imgSrc,$action,$class);
    }
}