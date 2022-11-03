<?php

class SelectThumbnail{

    private $con,$video;

    public function __construct($con,$video){
        $this->con = $con;
        $this->video =$video;

    }

    public function create(){
        $thumbnailData = $this->getThumbnailData();

        $html = "";

        foreach($thumbnailData as $data){
            $html .= $this->createThumbnailItem($data);
        }

        return "<div class='thumbnailItemContainer'>$html</div>";
    }

    private function getThumbnailData(){

        $videoId = $this->video->getId();
        $data = array();

        $query = $this->con->prepare('SELECT * FROM thumbnails WHERE videoId=:videoId');
        $query->bindParam(':videoId',$videoId);
        $query->execute();

        while($row = $query->fetch(PDO::FETCH_ASSOC)){
            $data[] = $row;
        }

        return $data;
    }

    private function createThumbnailItem($data){

        $id = $data['id'];
        $url = $data['filePath'];
        $videoId = $data['videoId'];

        // On ajoute une classe si l'image est sélectionnée

        $selected = $data['selected'] == 1 ? "selected" : "";

        return "<div class='thumbnailItem $selected' onclick='setNewThumbnail($id,$videoId,this)'>
                    <img src='$url'>
                </div>";

    }

}