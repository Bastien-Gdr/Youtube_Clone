<?php

require_once('includes/config.php');
require_once('classes/FormSanitizer.php');
require_once('classes/Account.php');

$account = new Account($con);

if(isset($_POST['loginBtn'])){

    $username = FormSanitizer::sanitizeFormUsername($_POST['username']);
    $password = FormSanitizer::sanitizeFormPassword($_POST['password']);

    $isSuccessful = $account->login($username,$password);

    if($isSuccessful){
        // Ok
        $_SESSION['userLoggedIn'] = $username;

        // Redirection

        header('Location: index.php');


    }else{
        // pas ok
        echo 'erreur de connexion';
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

    <title>Connexion</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

    <main id="registerContainer">
        <section class="column">
            <div class="header">
                <img src="assets/images/logo-youtube.png" alt="logo">
                <h3>Connectez-vous !</h3>
                <small>Afin d'améliorer votre expérience</small>
            </div>

            <div id="registerForm" class="mt-3 text-center">
                <form action="signIn.php" method="post">
                    <?= $account->getError(Constants::$loginFailed) ?>
                    <div class="form-group">
                        <label for="">Votre identifiant :</label>
                        <input type="text" name="username" class="form-control form-control-lg" autocomplete="off" value="<?php getInputValue('username'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="">Votre mot de passe :</label>
                        <input type="password" name="password" class="form-control form-control-lg" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="loginBtn" class="btn btn-primary form-control form-control-lg" value="Se connecter">
                    </div>
                    
                    
                </form>
                <a href="signUp.php"><small>Vous n'avez pas encore de compte ? Inscrivez-vous ! </small></a>
            </div>
            
        </section>


    </main>





</body>

</html>