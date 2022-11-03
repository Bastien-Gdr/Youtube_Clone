<?php

    class ProfileData{

        private $con,$profileUser;

        public function __construct($con,$profileUser){
            $this->con = $con;
            $this->profileUser = new User($con,$profileUser);

        }

        public function getProfileUsername(){

            // Retourne l'username de la classe User via getUsername()
            return $this->profileUser->getUsername();
        }

        public function userExists(){

            $profileUsername = $this->getProfileUsername();

            $query = $this->con->prepare("SELECT * FROM users WHERE username=:username");
            $query->bindParam(':username',$profileUsername);
            $query->execute();

            // Retourne vrai ou faux
            return $query->rowCount() != 0;

        }

        // Photo de couverture
        public function getCoverPhoto(){
            return "assets/images/banner4.jpg";
        }

        // Fullname
        public function getProfileFullname(){
            return $this->profileUser->getName();
        }

        // Avatar
        public function getProfileAvatar(){
            return $this->profileUser->getAvatar();
        }

        // Nombre d'abonnés
        public function getProfileSubscriptionsAccount(){
            return $this->profileUser->getSubscriberCount();
        }

        // Vidéos
        public function getUserVideo(){
            $username = $this->getProfileUsername();

            $query = $this->con->prepare("SELECT * FROM videos WHERE uploadedBy=:uploadedBy ORDER BY uploadDate DESC");
            $query->bindParam(':uploadedBy',$username);
            $query->execute();

            // On a les vidéos
            $videos = array();

            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                
                // $video= new Video($this->con,$row,$this->getProfileUsername());
                // array_push($videos,$video);

                $videos[]= new Video($this->con,$row,$this->getProfileUsername());
            }

            return $videos;
        }

        // Détails utilisateur
        public function getProfileUser(){
            return $this->profileUser;
        }

        public function getAllUserDetails(){
            return array(
                            "Nom" => $this->getProfileFullname(),
                            "Pseudo" => $this->getProfileUsername(),
                            "Abonné(s)" => $this->getProfileSubscriptionsAccount(),
                            "Nombre de vues" => $this->getTotalViews(),
                            "Date d'inscription" => $this->getSignUpDate()

            );
        }

        // Nombre de vues total
        private function getTotalViews(){
            $username = $this->getProfileUsername();

            $query = $this->con->prepare("SELECT sum(views) FROM videos WHERE uploadedBy=:uploadedBy");
            $query->bindParam(":uploadedBy",$username);
            $query->execute();

            return $query->fetchColumn();
        }

        // Date d'inscription
        private function getSignUpDate(){
            $date = $this->profileUser->getCreatedAt();


            return date("j/m/Y",strtotime($date));
        }



    }