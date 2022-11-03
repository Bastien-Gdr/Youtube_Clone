<?php require_once 'includes/header.php' ?>
<?php require_once 'classes/VideoFormProvider.php' ?>

<div class="column">
    <?php
     $formProvider = new VideoFormProvider($con);

     echo $formProvider->createUploadForm();
    

    ?>

</div>


<div class="modal fade" id="loadingModal" tabindex="-1" aria-labelledby="loadingModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body d-flex flex-column align-items-center">
        <p>Merci de patienter lors du traitement de la vidéo</p>
        <img src="assets/images/icons/loading-spinner.gif" alt="chargement">
      </div>
    </div>
  </div>
</div>

          


<?php require_once 'includes/footer.php' ?>