<?php

class VideoProcessor{

    private $con;
    // O.5 Gb = 500 Mb = 5000000 bites;
    private $sizeLimit = 5000000;

    private $allowedTypes = array('mp4','flv','mkv','vob','ogv','ogg','avi','mov','mpeg','mpg');
    private $ffmpegPath = "ffmpeg/ffmpeg";
    private $ffprobePath = "ffmpeg/ffprobe";

    public function __construct($con){
        $this->con = $con;
    }


    //uploader les infos

    public function upload($videoUploadData){

        $targetDir = 'uploads/videos/';
        $videoData = $videoUploadData->videoDataArray;
        $tempFilePath = $targetDir. uniqid(). basename($videoData['name']);
        $tempFilePath = str_replace(' ','_',$tempFilePath);

        // echo $tempFilePath;
        $isValid = $this->processData($videoData,$tempFilePath);

        if(!$isValid){return false;}

        // isValid est true, on déplace la vidéo dans le répertoire uploads/videos

        if(move_uploaded_file($videoData['tmp_name'],$tempFilePath)){
            // On défini un chemin final qui sera insérer dans la bdd

            $finalPath = $targetDir. uniqid().".mp4";

            if(!$this->insertVideoData($videoUploadData,$finalPath)){
                echo "L'insertion de la vidéo dans la bdd a échoué";
                return false;
            }

            if(!$this->convertVideoToMp4($tempFilePath,$finalPath)){
                echo "La vidéo n'a pas pu être convertie";
                return false;
            }

            // supprimer le fichier temporaire

            if(!$this->deleteFile($tempFilePath)){
                echo 'La première vidéo n\a pas été effacée';
                return false;
            }   
            

            // Générer des vignettes

            if(!$this->generateThumbnails($finalPath)){
                echo "Les vignettes n'ont pas pu être générées";
                return false;
            }


            // Calcul de la durée de la vidéo

        }
        return true;


    }


    
    private function processData($videoData,$filePath){

        $videoType = pathInfo($filePath,PATHINFO_EXTENSION);

        // Valider la taille et le type
        if(!$this->isValidSize($videoData)){
            echo '<div class="alert alert-danger">Le fichier est trop lourd, la taille maximale est de : '. $this->sizeLimit . 'bites.</div>';
            return false;
        }elseif(!$this->isValidType($videoType)){
            echo '<div class="alert alert-danger">Ce format de fichier n\'est pas accepté</div>';
            return false;
        }elseif($this->hasError($videoData)){
            echo "Code erreur : " . $videoData['error'];
            return false;
        }

        return true;

    }

    private function isValidSize($data){
        return $data['size'] <= $this->sizeLimit;
    }

    private function isValidType($type){
        $lowercase = strtolower($type);
        return in_array($lowercase,$this->allowedTypes);

    }

    // Stockage des erreurs
    private function hasError($data){
        return $data['error'] !=0;
    }

    // insertion dans la base de données

    private function insertVideoData($uploadData,$filePath){

            $query = $this->con->prepare("INSERT INTO videos(title,uploadedBy,description,privacy,category,filePath) 
                                          VALUES(:title,:uploadedBy,:description,:privacy,:category,:filePath)");

            $query->bindParam(':title',$uploadData->title);
            $query->bindParam(':uploadedBy',$uploadData->uploadedBy);
            $query->bindParam(':description',$uploadData->description);
            $query->bindParam(':privacy',$uploadData->privacy);
            $query->bindParam(':category',$uploadData->category);
            $query->bindParam(':filePath',$filePath);


            return $query->execute();

        }


    // Convertir la vidéo en MP4

    public function convertVideoToMp4($tempFilePath,$finalPath){

        $cmd = "$this->ffmpegPath -i $tempFilePath $finalPath 2>&1";

        $outputLog = array();

        exec($cmd,$outputLog,$returnCode);

        if($returnCode !=0){
            // La commande a échoué

            foreach($outputLog as $line){
                echo $line. "<br>";
            }
            return false;
        }
        
        return true;
    }

    private function deleteFile($filePath){
        // Supprimer un fichier ne php = unlink()

        if(!unlink($filePath)){
            echo 'Le fihcier n\'a pas pu être effacé';
        }

        return true;
    }

    private function generateThumbnails($filePath){
        $thumbnailSize = "200x120";
        $numThumbnails = 3;
        $pathToThumbnail = "uploads/videos/thumbnails";

        $duration = $this->getVideoDuration($filePath);

        $videoId  = $this->con->lastInsertId();
        $this->updateDuration($duration,$videoId);


        // Génerer les vignettes
        for($num = 1;$num<=$numThumbnails;$num++){

        // Configurer le chemin de vignettes
        $imageName = uniqid().".jpg";
        $interval =($duration * 0.8)/$numThumbnails * $num;
        $fullThumbnailPath = "$pathToThumbnail/$videoId-$imageName";

        // Générer les vignettes
        $cmd = "$this->ffmpegPath -i $filePath -ss $interval -s $thumbnailSize -vframes 1 $fullThumbnailPath 2>&1";
        $outputLog = array();
        exec($cmd,$outputLog,$returnCode);

        if($returnCode != 0){
            // Echec
            foreach($outputLog as $line){
                echo $line ."</br>";
            }
        }

        $selected = $num == 1 ? 1 : 0;

        $query = $this->con->prepare("INSERT INTO thumbnails(videoId,filePath,selected) VALUES(:videoId,:filePath,:selected)");
        $query->bindParam(':videoId',$videoId);
        $query->bindParam(':filePath',$fullThumbnailPath);
        $query->bindParam(':selected',$selected);

         

        $success = $query->execute();

        if(!$success){
            echo "Les vignettes n'ont pas été crées et envoyées dans la bdd \n";
            return false;
        }

        }
        return true;
    }

    private function getVideoDuration($filePath){
        // executer une commande sans retourner d'erreur (uniquement l'output)

        return (int) shell_exec("$this->ffprobePath -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 $filePath");
    }

    private function updateDuration($duration,$videoId){
        $hours = floor($duration /3600);
        $mins = floor(($duration - ($hours * 3600))/60);

        // % = modulo (il permet d'obtenir le reste d'une division)
        $secs = floor($duration % 60);

        // Conditions ternaires => () ? :
        $hours = ($hours < 1) ? "" : $hours.":";
        $mins = ($mins < 10) ? "0".$mins.":" : $mins.":";
        $secs = ($secs < 10) ? "0".$secs : $secs;

        $duration = $hours.$mins.$secs;

        $query = $this->con->prepare("UPDATE videos SET duration=:duration WHERE id=:videoId");
        $query->bindParam(":duration",$duration);
        $query->bindParam(":videoId",$videoId);
        $query->execute();

    }
}