<?php

class TrendingProvider{

    private $con,$currentUser;

    public function __construct($con,$currentUser){
        $this->con = $con;
        $this->currentUser = $currentUser;
    }

    public function getVideos(){
        $videos = array();

        $query = $this->con->prepare("SELECT * FROM videos WHERE uploadDate >= now() - INTERVAL 7 DAY 
                                      ORDER BY views DESC LIMIT 15");

        $query->execute();

        while($row = $query->fetch(PDO::FETCH_ASSOC)){
            $video = new Video($this->con,$row,$this->currentUser);

            array_push($videos,$video);
        }

        return $videos;
    }


}