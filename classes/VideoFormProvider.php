<?php



class VideoFormProvider
{

    private $con;

    public function __construct($con){
    
    $this->con = $con;
   }
    // Lancer la connection à la bdd



    // Création du formulaire

    public function createUploadForm()
    {

        $fileInput = $this->createFileInput();
        $titleInput = $this->createTitleInput(null);
        $descriptionInput = $this->createDescriptionInput(null);
        $privacyInput = $this->createPrivacyInput(null);
        $categoriesInput = $this->createCategoriesInput(null);
        $uploadBtn = $this->createUploadBtn();

        return "<form action='validation.php' method='POST' enctype='multipart/form-data'>
                    $fileInput
                    $titleInput
                    $descriptionInput
                    $privacyInput
                    $categoriesInput
                    $uploadBtn
                </form>";
    }

    private function createFileInput()
    {

        return "<div class='form-group'>
                        <input type='file' class='form-control form-control-lg' name='fileInput' required>
                    </div>";
    }

    private function createTitleInput($value)
    {
        if($value == null) $value = "";

        return "<div class='form-group'>
                        <input type='text' class='form-control form-control-lg' name='titleInput' value='$value' placeholder='Titre de la vidéo' required>
                    </div>";
    }

    private function createDescriptionInput($value)
    {     
        if($value == null) $value = "";

        return "<div class='form-group'>
                        <textarea class='form-control form-control-lg' name='descriptionInput' placeholder='Description de la vidéo' rows='3' required>$value</textarea>
                    </div>";
    }

    private function createPrivacyInput($value)
    {
        if($value == null) $value = "";

        $privateSelected = ($value == 0) ? "selected='selected'" : "";
        $publicSelected = ($value == 1) ? "selected='selected'" : "";

        return "<div class='form-group'>
                    <select class='form-control form-control-lg' name='privacyInput' required >
                        <option value='0' $privateSelected>Privée</option>
                        <option value='1' $publicSelected>Public</option>                    
                    </select>        
                </div>";
    }

    private function createCategoriesInput($value)
    {
        if($value == null) $value = "";


        $query = $this->con->prepare('SELECT * FROM categories');
        $query->execute();

        $html = "<div class='form-group'>
        <select class='form-control form-control-lg' required name='categoriesInput'>";

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
            $name = $row['name'];
            $selected = ($id == $value) ? "selected='selected'" : "";

            $html .= "<option value='$id' $selected>$name</option>";
        }


        $html .= "</select></div>";

        return $html;
    }

    private function createUploadBtn()
    {

        return "<button type='submit' class='btn btn-primary' name='uploadBtn'>
                    Télécharger
                </button>";
    }


    // Edition du formulaire

    public function createdEditDetailsForm($video){

        $titleInput = $this->createTitleInput($video->getTitle());
        $descriptionInput = $this->createDescriptionInput($video->getDescription());
        $privacyInput = $this->createPrivacyInput($video->getPrivacy());
        $categoriesInput = $this->createCategoriesInput($video->getCategory());
        $saveDetailsBtn = $this->createSaveDetailsBtn();


        return "<form method='post'>
                    $titleInput
                    $descriptionInput
                    $privacyInput
                    $categoriesInput
                    $saveDetailsBtn
                </form>";
    }

    private function createSaveDetailsBtn(){
        return "<button type='submit' class='btn btn-primary' name='saveDetailsBtn'>
                    Sauvegarder
                </button>";
    }

}
