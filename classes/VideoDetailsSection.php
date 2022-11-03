<?php

require_once ('classes/VideoDetailsControls.php');

class VideoDetailsSection{

    private $con,$video,$user;

    public function __construct($con,$video,$user){
        $this->con = $con;
        $this->video = $video;
        $this->user = $user;

    }

    // Méthode create = 1ere + 2eme zone

    public function create(){

        
        return $this->createFirstZone(). $this->createSecondZone();
    }

    // 1ere zone
    private function createFirstZone(){

        // Titre - nombre de vues

        $title = $this->video->getTitle();
        $views = $this->video->getViews();
        $views .= ($views > 1) ? " vues" : " vue";
        $videoDetailsControls = new VideoDetailsControls($this->video,$this->user);
        $controls = $videoDetailsControls->create();


        return "<div class='videoInfos'>
                    <h1>$title</h1>
                    <div class='bottomSection d-flex justify-content-between'>
                        <span class='viewCount'>$views</span>
                        <span>$controls</span>
                    </div>
                </div>";

    }

    // 2eme zone

    private function createSecondZone(){

        // Description, date, pseudo, lien vers le profil
        $description = $this->video->getDescription();
        $uploadDate = $this->video->getUploadDate();
        $uploadedBy = $this->video->getUploadedBy();
        $profileBtn = BtnProvider::createProfileBtn($this->con,$uploadedBy);

        // 1er cas : l'utilisateur est l'auteur de la vidéo
        if($uploadedBy == $this->user->getUsername()){
            $actionBtn = BtnProvider::createEditBtn($this->video->getId());
        }else{
           // 2eme cas l'utilisateur n'est pas l'auteur => btn "s'abonner"
        $userTo = new User($this->con,$uploadedBy);

           $actionBtn = BtnProvider::createSubscribeBtn($this->con,$userTo,$this->user);
        }

        return "<div class='secondarySection'>
                    <div class='topContent'>
                        $profileBtn

                        <div class='uploadInfos'>
                            <span class='author'>
                                <a href='profile.php?username=$uploadedBy'>$uploadedBy</a>
                            </span>
                            <span class='date'>Publiée le $uploadDate</span>
                        </div>
                        $actionBtn
                    </div>
                    <div class='description'>
                        $description
                    </div>
                </div>";
        
    }
}