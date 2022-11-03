<?php

class User{

    private $con;
    private $sqlData = [];
    

    public function __construct($con,$username){
        $this->con = $con;

        $query = $this->con->prepare("SELECT * FROM users WHERE username=:username");
        $query->bindParam(':username',$username);
        $query->execute();

        
        $this->sqlData = $query->fetch(PDO::FETCH_ASSOC); 

    }

    public function getUsername(){
        return $this->sqlData['username'];
    }
    

    public function getName(){
        return $this->sqlData['firstname'] . " " . $this->sqlData['lastname'];
    }

    public function getFirstname(){
        return $this->sqlData['firstname'];
    }

    public function getLastname(){
        return $this->sqlData['lastname'];
    }

    public function getEmail(){
        return $this->sqlData['email'];
    }

    public function getAvatar(){
        return $this->sqlData['avatar'];
    }

    public function getCreatedAt(){
        return $this->sqlData['createdAt'];
    }

    public static function isLoggedIn(){
        return isset($_SESSION['userLoggedIn']);
    }
    
    public function isSubscribedTo($userTo){

        // retourne vrai ou faux

        $username = $this->getUsername();

        $query = $this->con->prepare("SELECT * FROM subscribers WHERE userTo=:userTo AND userFrom=:userFrom");
        $query->bindParam(":userTo",$userTo);
        $query->bindParam(":userFrom",$username);

        $query->execute();

        return $query->rowCount() > 0;

    }

    public function getSubscriberCount(){

        // retourne un nombre de lignes (nombre d'abonnés)

        $username = $this->getUsername();

        $query = $this->con->prepare("SELECT * FROM subscribers WHERE userTo=:userTo");
        $query->bindParam(":userTo",$username);

        $query->execute();

        return $query->rowCount();

    }

    public function getSubscriptions(){

        $query = $this->con->prepare("SELECT userTo FROM subscribers WHERE userFrom=:userFrom");
        $username = $this->getUsername();
        $query->bindParam(':userFrom',$username);

        $query->execute();

        $subscriptions = array();

        while($row = $query->fetch(PDO::FETCH_ASSOC)){
            $user = new User($this->con,$row['userTo']);
            array_push($subscriptions,$user);
        }

        return $subscriptions;


    }



}