<?php

require_once('classes/ProfileData.php');
require_once('classes/BtnProvider.php');


class ProfileProvider
{

    private $con, $currentUser, $profileData;

    public function __construct($con, $currentUser, $profileUsername)
    {
        $this->con = $con;
        $this->currentUser = $currentUser;
        $this->profileData = new ProfileData($con, $profileUsername);
    }

    public function create()
    {

        $profileUsername = $this->profileData->getProfileUsername();

        if (!$this->profileData->userExists()) {
            return "L'utilisateur n'existe pas dans notre base de données";
        }

        // 4 sections

        $coverPhotoSection = $this->createCoverPhotoSection();
        $headerPhotoSection = $this->createHeaderSection();
        $tabsPhotoSection = $this->createTabsSection();
        $contentPhotoSection = $this->createContentSection();

        return "<div class='profileContainer'>
                    $coverPhotoSection
                    $headerPhotoSection
                    $tabsPhotoSection
                    $contentPhotoSection
                </div>";
    }

    public function createCoverPhotoSection()
    {
        $coverImg = $this->profileData->getCoverPhoto();
        $name = $this->profileData->getProfileFullname();


        return "<div class='coverImgContainer' style='background:url($coverImg) #fff no-repeat scroll 0 0; background-size:cover'>
                    <span class='profileName'>$name</span>
                </div>";
    }

    public function createHeaderSection()
    {
        // Avatar - nom - nombre d'abonnés
        $profileAvatar = $this->profileData->getProfileAvatar();
        $name = $this->profileData->getProfileFullname();
        $subscriptionCount = $this->profileData->getProfileSubscriptionsAccount();
        $btn = $this->createHeaderBtn();

        return "<div class='profileHeader'>
                    <div class='userInfosContainer'>
                        <img src='$profileAvatar' class='profileImg'>
                        <div class='userInfos'>
                            <span class='title'>$name</span>
                            <span class='subscriberCount'>$subscriptionCount abonné(s)</span>
                        </div>
                    </div>
                    <div class='btnContainer'>
                        <div class='btnItem'>$btn</div>
                    </div>
                </div>";
    }

    public function createTabsSection()
    {

        return "<div class='profileTabs'>
                    <ul class='nav nav-tabs' id='tabsProfile' role='tablist'>
                        <li class='nav-item'>
                            <a class='nav-link active' aria-current='page' href='#videos' id='videos-tab' data-toggle='tab' role='tab' aria-controls='videos' aria-selected='true'>Vidéos</a>
                        </li>
                        <li class='nav-item'>
                            <a class='nav-link' aria-current='page' href='#about' id='about-tab' data-toggle='tab' role='tab' aria-controls='about' aria-selected='true'>À propos</a>
                        </li>
                    </ul>
                <div>";
    }

    public function createContentSection()
    {
        $videos = $this->profileData->getUserVideo();

        if(sizeof($videos) > 0){
            $videoGrid = new VideoGrid($this->con,$this->currentUser);
            $videoGridHtml = $videoGrid->create($videos,null,false);

        }else{
            $videoGridHtml = "<span class='alert alert-warning'>Cet utilisateur n'a encore posté aucune vidéo</span>";
        }

        $aboutSection = $this->createAboutSection();

        return "<div class='profileContent'>
                    <div class='tab-content' id='tabsContent'>
                        <div class='tab-pane active show' id='videos' role='tabpanel' aria-labelledby='videos-tab'>$videoGridHtml</div>
                        <div class='tab-pane' id='about' role='tabpanel' aria-labelledby='about-tab'>$aboutSection</div>
                    </div>
                </div>";
    }

    private function createHeaderBtn()
    {
        if ($this->currentUser->getUsername() == $this->profileData->getProfileUsername()) {
            return "";
        } else {
            return BtnProvider::createSubscribeBtn($this->con, $this->profileData->getProfileUser(), $this->currentUser);
        }
    }

    private function createAboutSection(){
        $html = "<div class='aboutContainer'>
                    <div class='title'><span>Détails</span></div>
                    <div class='values'>";

        $details = $this->profileData->getAllUserDetails();

        // Boucle 
        foreach($details as $key=>$value){
            $html .="<span>$key : $value </span>";
        }

        $html .= "</div></div>";

        return $html;
    }
}
