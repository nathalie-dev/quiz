<?php

class Theme
{
    public static $themes = [];

    // attributs
    public int $id_themes;  
    public String $nom;
    public String $descriptif;
    public String $date_creation;
    public String $image;


    // constructeur
    public function __construct(int $id_themes, String $nom, String $descriptif, String $date_creation, String $image)
    {
        $this->id_themes = $id_themes;
        $this->nom = htmlentities($nom);
        $this->descriptif = htmlentities($descriptif) ;
        $this->date_creation = $date_creation;
        $this->image = $image;
        //self :: $themes[] = $this;   
    }

    public function __toString()
    {
        return wp_json_encode($this);
    }


    //public function getThemesListe(){
    //   return self::$themes;
    //}
}
?>


