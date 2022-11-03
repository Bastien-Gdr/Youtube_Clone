<?php
require_once('includes/header.php');
require_once('classes/Account.php');
require_once('classes/FormSanitizer.php');
require_once('classes/Constants.php');
require_once('classes/SettingsFormProvider.php');

if (!User::isLoggedIn()) {
    header('Location: signIn.php');
}

$detailsMsg = "";
$passwordMsg = "";
$settingsFormProvider = new SettingsFormProvider();

if(isset($_POST['saveDetailsBtn'])){

    $account = new Account($con);

    $firstname = FormSanitizer::sanitizeFormString($_POST['firstname']);
    $lastname = FormSanitizer::sanitizeFormString($_POST['lastname']);
    $email = FormSanitizer::sanitizeFormEmail($_POST['email']);

    if($account->updateDetails($firstname,$lastname,$email,$user->getUsername())){
        // Return true = Ok
        $detailsMsg = "<div class='alert alert-success'>
                            <strong>Vos données ont été mises à jour !</strong>
                       </div>";

    }else{
        // False => message d'erreur
        $errorMsg = $account->getFirstError();

        if($errorMsg = ""){
            $errorMsg = "Une erreur est survenue";
        }

        $detailsMsg = "<div class='alert alert-danger'>
                            <strong>Erreur : $errorMsg !</strong>
                       </div>";
    }    

}

    if(isset($_POST['savePasswordBtn'])){
        $account = new Account($con);
        $oldPassword = FormSanitizer::sanitizeFormPassword($_POST['oldPassword']);
        $newPassword = FormSanitizer::sanitizeFormPassword($_POST['newPassword']);
        $confirmPassword = FormSanitizer::sanitizeFormPassword($_POST['confirmPassword']);

        if($account->updatePassword($oldPassword,$newPassword,$confirmPassword,$user->getUsername())){
            // Retourne true
            $passwordMsg  ="<div class='alert alert-success'><strong>Votre mot de passe a bien été mis à jour !</strong></div>";

        }else{
          // False => message d'erreur
             $errorMsg = $account->getFirstError();

             if($errorMsg = ""){
                  $errorMsg = "Une erreur est survenue";
              }

              $passwordMsg = "<div class='alert alert-danger'>
                                <strong>Erreur : $errorMsg !</strong>
                             </div>";


        }

    }


?>

<div class="settingContainer column">
    <div class="formSection">
        <div class="message"><?php echo $detailsMsg; ?></div>
        <?php echo $settingsFormProvider->createUserDetailsForm(
           isset($_POST['firstname']) ? $_POST['firstname'] : $user->getFirstname(),
           isset($_POST['lastname']) ? $_POST['lastname'] : $user->getLastname(),
           isset($_POST['email']) ? $_POST['email'] : $user->getEmail()

        ); ?>
    </div>
</div>

<div class="settingContainer column">
    <div class="formSection">
    <div class="message"><?php echo $passwordMsg; ?></div>
        <?php echo $settingsFormProvider->createPasswordForm(); ?>
    </div>
</div>






<?php require_once('includes/header.php'); ?>