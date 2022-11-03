<?php

class SettingsFormProvider{

    

    public function createUserDetailsForm($firstname,$lastname,$email){

        $firstnameInput = $this->createFirstnameInput($firstname);
        $lastnameInput = $this->createLastnameInput($lastname);
        $emailInput = $this->createEmailInput($email);
        $saveBtn = $this->createSaveUserBtn();


        return "<form action='settings.php' method='post'>
                    <span>Mise à jour des informations personnelles</span>
                    $firstnameInput
                    $lastnameInput   
                    $emailInput
                    $saveBtn     
                </form>";

    }

    public function createPasswordForm(){


        $oldPassword = $this->createPasswordInput("oldPassword","Ancien mot de passe");
        $newPassword = $this->createPasswordInput("newPassword","Nouveau mot de passe");
        $confirmPassword = $this->createPasswordInput("confirmPassword","Confirmez votre nouveau mot de passe");
        $savePasswordBtn = $this->createSavePasswordInput();

        return "<form action='settings.php' method='post'>
                    <span>Mise à jour du mot de passe</span>
                    $oldPassword
                    $newPassword
                    $confirmPassword
                    $savePasswordBtn
                </form>";
    }

    public function createPasswordInput($name,$placeholder){
        return "<div class='form-group'>
                    <input type='password' class='form-control form-control-lg' placeholder='$placeholder' name='$name' required>
                </div>";

    }

    public function createSavePasswordInput(){
        return "<button type='submit' class='btn btn-primary' name='savePasswordBtn' id='updatePassword'>Sauvegarder</button>";
    }

    public function createFirstnameInput($value){

        if($value == null) $value="";
        return "<div class='form-group'>
                    <input type='text' class='form-control form-control-lg' placeholder='Nom' name='firstname' value='$value' required>
                </div>";
    }

    public function createLastnameInput($value){
        if($value == null) $value="";
        return "<div class='form-group'>
                    <input type='text' class='form-control form-control-lg' placeholder='Prénom' name='lastname' value='$value' required>
                </div>";
    }

    public function createEmailInput($value){
        if($value == null) $value="";
        return "<div class='form-group'>
                    <input type='email' class='form-control form-control-lg' placeholder='Email' name='email' value='$value' required>
                </div>";
    }

    public function createSaveUserBtn(){
        return "<button type='submit' class='btn btn-primary' name='saveDetailsBtn' id='updateBtn'>Sauvegarder</button>";
    }

}