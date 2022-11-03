<?php

require_once('Constants.php');


class Account{

    private $con;
    private $errorArray = array();

    public function __construct($con){
        $this->con = $con;
    }

    // S'inscrire
        public function register($firstname,$lastname,$username,$email,$confirmEmail,$password,$confirmPassword){
            
            $this->validateFirstname($firstname);
            $this->validateLastname($lastname);
            $this->validateUsername($username);
            $this->validateEmail($email,$confirmEmail);
            $this->validatePassword($password,$confirmPassword);

            // S'il n'y a pas d'erreur

            if(empty($this->errorArray)){
                return $this->insertDetails($firstname,$lastname,$username,$email,$password);
            }else{
                return false;
            }

        }


    // Se connecter

    public function login($username,$password){
        $password = hash('sha512',$password);

        $query = $this->con->prepare("SELECT * FROM users WHERE username=:username AND password=:password");

        $query->bindParam(':username',$username);
        $query->bindParam(':password',$password);

        $query->execute();

        // On vérifir le nombre de lignes returnée
        if($query->rowCount() == 1){
            return true;
        }else{

            array_push($this->errorArray,Constants::$loginFailed);
            return false;
        }
    }


    // Insérer les détail dans la bdd

    public function insertDetails($firstname,$lastname,$username,$email,$password){
        
        // Crypter le mdp

        $password = hash("sha512",$password);
        $avatar = "assets/images/avatars/icons8-utilisateur-masculin-50.png";

        $query = $this->con->prepare("INSERT INTO users(firstname,lastname,username,email,password,avatar) 
                                      VALUES(:firstname,:lastname,:username,:email,:password,:avatar)");

        $query->bindParam(':firstname',$firstname);
        $query->bindParam(':lastname',$lastname);
        $query->bindParam(':username',$username);
        $query->bindParam(':email',$email);
        $query->bindParam(':password',$password);
        $query->bindParam(':avatar',$avatar);

        
        // La méthode doit retourner vrai ou faux

        return $query->execute();
        

        
    }


    // Valider tous les champs

    private function validateFirstname($firstname){
        // Avoir un minimum de 2 caractères
        if(strlen($firstname) > 25 || strlen($firstname) < 2){
            // non valide
            array_push($this->errorArray,Constants::$firstnameMsg);

        }
    }

    private function validateLastname($lastname){
        // Avoir un minimum de 2 caractères
        if(strlen($lastname) > 25 || strlen($lastname) < 2){
            // non valide
            array_push($this->errorArray,Constants::$lastnameMsg);

        }
    }

    private function validateUsername($username){
        // Avoir un minimum de 2 caractères
        if(strlen($username) > 25 || strlen($username) < 5){
            // non valide
            array_push($this->errorArray,Constants::$usernameMsg);
            return;
        }
        // requête pour vérifier si le pseudo existe déjà

        $query = $this->con->prepare("SELECT username FROM users WHERE username=:username");

        $query->bindParam("username",$username);
        $query->execute();


        // Si le serveur retourne une ligne, on affiche l'erreur
        if($query->rowCount() != 0){
            array_push($this->errorArray,Constants::$usernameExistMsg);
        }
    }


    private function validateEmail($email,$confirmEmail){

        // 1 : Les emails ne sont pas identiques
        if($email != $confirmEmail){
            array_push($this->errorArray,Constants::$emailDifferentMsg);
            return;
        }

        // 2 : l'email n'a pas le bon format

        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            array_push($this->errorArray,Constants::$emailInvalidMsg);
            return;
        }

        // 3: L'email existe déjà dans la bdd

        $query = $this->con->prepare("SELECT email FROM users WHERE email=:email");
        $query->bindParam(':email',$email);
        $query->execute();

        // Si le serveur retourne une ligne

        if($query->rowCount() != 0){
            array_push($this->errorArray,Constants::$emailTakenMsg);
        }

    }

    private function validatePassword($password,$confirmPassword){

        // 1 : Comparaison des mdt

        if($password != $confirmPassword){
            array_push($this->errorArray,Constants::$passwordDifferentMsg);
            return;
        }


        // 2 : Valider le format (expression regex)

        if(preg_match("/[^A-Za-z0-9]/",$password)){
            array_push($this->errorArray,Constants::$passwordInvalidMsg);
            return;
        }


        // 3 : Valider la taille du mdp
        if(strlen($password) < 8){
            array_push($this->errorArray,Constants::$passwordSmallerMsg);
        }
    }


    // Erreurs

    public function getError($error){

        if(in_array($error,$this->errorArray)){
            return '<span class="error">'.$error.'</span>';
        }
    }


    // Maj des détails

    public function updateDetails($firstname,$lastname,$email,$username){
        $this->validateFirstname($firstname);
        $this->validateLastname($lastname);
        $this->validateNewEmail($email,$username);

        if(empty($this->erroArray)){
            // Ok mettre à jour les données
            $query = $this->con->prepare("UPDATE users SET firstname=:firstname, lastname=:lastname, email=:email WHERE username=:username");
            $query->bindParam(":firstname",$firstname);
            $query->bindParam(":lastname",$lastname);
            $query->bindParam(":email",$email);
            $query->bindParam(":username",$username);

            return $query->execute();

        }else{
            return false;
        }

    }

    public function validateNewEmail($email,$username){
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            array_push($this->errorArray,Constants::$emailInvalidMsg);
            return;
        }

        $query = $this->con->prepare("SELECT email FROM users WHERE email=:email AND username !=:username");
        $query->bindParam(':email',$email);
        $query->bindParam(':username',$username);
        $query->execute();

        if($query->rowCount() != 0){
            array_push($this->errorArray,Constants::$emailTakenMsg);
        }

    }

    public function getFirstError(){
        if(empty($this->errorArray)){
            return $this->errorArray[0];
        }else{ 
            return "";
        }
    }

    // Maj du mdp

    public function updatePassword($oldPassword,$password,$confirmPassword,$username){
        $this->validateOldPassword($oldPassword,$username);
        $this->validatePassword($password,$confirmPassword);
  

        if(empty($this->erroArray)){
            // Ok mettre à jour les données
            $query = $this->con->prepare("UPDATE users SET password=:password WHERE username=:username");
            $password = hash("sha512",$password);
            $query->bindParam(":password",$password);
            $query->bindParam(":username",$username);

            return $query->execute();

        }else{
            return false;
        }

    }   

    public function validateOldPassword($oldPassword,$username){
        $password = hash('sha512',$oldPassword);
        $query = $this->con->prepare("SELECT * FROM users WHERE username=:username AND password=:password");
        $query->bindParam(':username',$username);
        $query->bindParam(':password',$password);
        $query->execute();

        if($query->rowCount() == 0){
            array_push($this->errorArray,Constants::$passwordIncorrect);
        }
    }

}