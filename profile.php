<?php require_once('includes/header.php');
      require_once('classes/ProfileProvider.php');

if(isset($_GET['username'])){
    $profileUsername = $_GET['username'];
}else{
    echo "L'utilisateur n'a pas été reconnu";
    exit;
}


    $profileProvider = new ProfileProvider($con,$user,$profileUsername);

    echo $profileProvider->create();


?>

    


<?php require_once('includes/footer.php'); ?>