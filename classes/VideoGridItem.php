<?php

class VideoGridItem{

    private $video,$largeMode;

    public function __construct($video,$largeMode){

        $this->video = $video;
        $this->largeMode = $largeMode;

    }

    public function create(){

        // Afficher une vignette, les détails, une url
        $thumbnail = $this->createThumbnail();
        $details = $this->createDetails();
        $url = "watch.php?id=".$this->video->getId();

        return "<a href='$url'>
                    <div class='gridItem'>
                        $thumbnail
                        $details
                    </div>
                </a>";

    }

    private function createThumbnail(){

        $thumbnail = $this->video->getThumbnail();
        $duration = $this->video->getDuration();

        return "<div class='thumbnail'>
                    <img src='$thumbnail'>
                    <div class='duration'>
                        <span>$duration</span>
                    </div>
                </div>";
    }

    private function createDetails(){

        $title = $this->video->getTitle();
        $username = $this->video->getUploadedBy();
        $views = $this->video->getViews();
        $views .= ($views >= 2) ? " vues" : " vue";
        $description = $this->createDescription();
        $time = $this->video->getUploadDate();

        return "<div class='details'>
                    <h4 class='title'>$title</h4>
                    <span class='username'>$username</span>
                    <div class='stats'>
                        <span class='viewCount'>$views</span></br>
                        <span class='time'>$time</span>
                    </div>
                    $description
                </div>";
    }

    private function createDescription(){
        if(!$this->largeMode){
            return "";
        }else{
            $description = $this->video->getDescription();
            $description = (strlen($description) > 250) ? substr($description,0,248) . " ..." : $description;

            return "<p class='description'>$description</p>";
        }
    }

}