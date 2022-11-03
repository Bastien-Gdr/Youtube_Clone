<?php

class subscriptionProvider{

    private $con,$currentUser;

    public function __construct($con,$currentUser){

        $this->con = $con;
        $this->currentUser = $currentUser;

    }

    public function getVideos(){

        $videos =array();

        $subscriptions = $this->currentUser->getSubscriptions();

        if(sizeof($subscriptions) > 0){

            // User 1, user 2, user 3...


            // SELECT * FROM videos WHERE uploadedBy = user1 OR uploadedBy = user2 OR uploadedBy = user3....
            $condition = "";

            $i =0;

            while($i < sizeof($subscriptions)){
                if($i == 0){
                    $condition .= "WHERE uploadedBy=?";
                }else{
                    $condition .= " OR uploadedBy=?";
                }

                $i ++;
            }

            $videoSql = "SELECT * FROM videos $condition ORDER BY uploadDate DESC";
            $videoQuery = $this->con->prepare($videoSql);

            $i = 1;

            foreach($subscriptions as $sub){

                $subUsername = $sub->getUsername();
                $videoQuery->bindValue($i,$subUsername);
                $i++;

            }

            $videoQuery->execute();

            while($row = $videoQuery->fetch(PDO::FETCH_ASSOC)){
                $video = new Video($this->con,$row,$this->currentUser);
                array_push($videos,$video);
            }

        }

        return $videos;

    }



}