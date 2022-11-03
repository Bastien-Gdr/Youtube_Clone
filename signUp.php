<?php

require_once('includes/config.php');
require_once('classes/FormSanitizer.php');
require_once('classes/Account.php');

$account = new Account($con);


if(isset($_POST['registerBtn'])){
    $firstname = FormSanitizer::sanitizeFormString($_POST['firstname']);
    $lastname = FormSanitizer::sanitizeFormString($_POST['lastname']);
    $username = FormSanitizer::sanitizeFormUsername($_POST['username']);
    $email = FormSanitizer::sanitizeFormEmail($_POST['email']);
    $confirmEmail = FormSanitizer::sanitizeFormEmail($_POST['confirmEmail']);
    $password = FormSanitizer::sanitizeFormPassword($_POST['password']);
    $confirmPassword = FormSanitizer::sanitizeFormPassword($_POST['confirmPassword']);


   
    $isSuccessful = $account->register($firstname,$lastname,$username,$email,$confirmEmail,$password,$confirmPassword);

    if($isSuccessful){
        // success
        $_SESSION['userLoggedIn'] = $username;

        // redirection vers la page d'index
        header('Location: index.php');

    }else{
        echo "Le formulaire n'a pas été envoyé";
    }

}

    function getInputValue($name){
        if(isset($_POST[$name])){
            echo $_POST[$name];
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Inscription Youtube</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

    <main id="registerContainer">
        <section class="column">
            <div class="header">
                <img src="assets/images/logo-youtube.png" alt="logo">
                <h3>Inscrivez-vous !</h3>
                <small>Cela vous permettra une meilleure expérience</small>
            </div>

            <div id="registerForm" class="mt-3 text-center">
                <form action="signUp.php" method="post">
                    <div class="form-group">
                        <label for="">Votre nom :</label>
                        <input type="text" name="firstname" class="form-control form-control-lg" autocomplete="off" value="<?php getInputValue('firstname')?>" required>    
                        <?php echo $account->getError(Constants::$firstnameMsg); ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="">Votre prénom :</label>
                        <input type="text" name="lastname" class="form-control form-control-lg" autocomplete="off" value="<?php getInputValue('lastname')?>" required>
                        <?php echo $account->getError(Constants::$lastnameMsg); ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="">Votre pseudo :</label>
                        <input type="text" name="username" class="form-control form-control-lg" autocomplete="off" value="<?php getInputValue('username')?>" required>
                        <?php echo $account->getError(Constants::$usernameExistMsg); ?>
                    </div>
                    <div class="form-group">
                        <label for="">Votre email :</label>
                        <input type="email" name="email" class="form-control form-control-lg" autocomplete="off" value="<?php getInputValue('email')?>" required>
                        <?php echo $account->getError(Constants::$emailInvalidMsg); ?>
                        <?php echo $account->getError(Constants::$emailTakenMsg); ?>
                    </div>
                    <div class="form-group">
                        <label for="">Confirmez votre email :</label>
                        <input type="email" name="confirmEmail" class="form-control form-control-lg" autocomplete="off" value="<?php getInputValue('confirmEmail')?>" required>
                        <?php echo $account->getError(Constants::$emailDifferentMsg); ?>
                    </div>
                    <div class="form-group">
                        <label for="">Votre mot de passe :</label>
                        <input type="password" name="password" class="form-control form-control-lg" autocomplete="off" required>
                        <?php echo $account->getError(Constants::$passwordInvalidMsg); ?>
                        <?php echo $account->getError(Constants::$passwordSmallerMsg); ?>
                    </div>
                    <div class="form-group">
                        <label for="">Confirmez votre mot de passe :</label>
                        <input type="password" name="confirmPassword" class="form-control form-control-lg" autocomplete="off" required>
                        <?php echo $account->getError(Constants::$passwordDifferentMsg); ?>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="registerBtn" class="btn btn-primary form-control form-control-lg" value="S'inscrire">
                    </div>
                    
                    
                </form>
                <a href="signIn.php"><small>Vous avez déjà un compte ? Connectez-vous ici ! </small></a>
            </div>
            
        </section>


    </main>





</body>

</html>