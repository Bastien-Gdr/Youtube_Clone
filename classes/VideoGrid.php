<?php 

class VideoGrid{
    
    private $con,$currentUser;
    private $gridClass = "videoGrid";
    private $largeMode = false;

    public function __construct($con,$currentUser){

        $this->con = $con;
        $this->currentUser = $currentUser;
    }

    public function create($videos,$title,$filter){

        // Vérifier s'il y a des vidéos / sinon affichage aléatoire
        if($videos == null){
            // pas d'abonnements, de likes.... => affichage aléatoire
            $gridItems = $this->generateItems();

        }else{
            $gridItems = $this->generateItemsFromVideos($videos);
        }

        // Header spécifique

        $header = "";

        if($title != null){
            $header = $this->createGridHeader($title,$filter);
        }

        return "$header <div class='$this->gridClass'>$gridItems</div>";
    }
       
    
    
    // Permet de générer les vidéos aléatoirement
    private function generateItems(){

        $query = $this->con->prepare("SELECT * FROM videos ORDER BY RAND() LIMIT 15");
        $query->execute();

        $html = "";

        while($row = $query->fetch(PDO::FETCH_ASSOC)){
            $video = new Video($this->con,$row,$this->currentUser);
            $item = new VideoGridItem($video,$this->largeMode);
            $html .= $item->create();

        } 
        
        return $html;
    }

    private function generateItemsFromVideos($videos){

        $html = "";

        foreach($videos as $video){
            $item = new VideoGridItem($video,$this->largeMode);
            $html .= $item->create();
        }

        return $html;

    }

    private function createGridHeader($title,$filter){

        $showFilter = "";

        if($filter){
            $link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

            $urlArray = parse_url($link); // découpe l'url 
            $query = $urlArray['query'];
            parse_str($query,$params);

            // Suppprime un élément du tableau (orderBy)
            unset($params['orderBy']);

            $newQuery = http_build_query($params);
            $newUrl = basename($_SERVER['PHP_SELF'])."?".$newQuery;
            
            $showFilter = "<div class='right'>
                                <span>Trier par</span>
                                <a href='$newUrl&orderBy=uploadeDate'>Date d'ajout</a>
                                <a href='$newUrl&orderBy=views'>Les plus populaires</a>
                            </div>";

        }

        // Création du filtre
        return "<div class='gridHeader'>
                    <div class='left'>$title</div>
                    $showFilter
                </div>";

    }

    public function createLarge($videos,$title,$filter){
        // On ajoute la classe large
        $this->gridClass .= " large";
        $this->largeMode = true;

        return $this->create($videos,$title,$filter);
    }


    




}