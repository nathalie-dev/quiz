<?php

//class natquiz 
class natquiz {



    // constructeur
    public function __construct()
     {
       
     }

    // fonction recuperation des themes pour le formulaire de creation et de modification d'une question
    public function get_all_themes() {
        global $wpdb;
    
        $table_name = $wpdb->prefix . 'nat_quiz_themes';
            // liste des themes
            $themes = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table_name"
        ));
        return $themes;
    }

    public function get_name_theme($id) {
        global $wpdb;

        if(is_numeric($id)) {
            // recherche du nom du theme correspndant à l'id transmis
            $table_name = $wpdb->prefix . 'nat_quiz_themes';
            $themes = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM $table_name WHERE `id_themes` = $id"
            ));
           if(isset($themes->nom)) {
            return $themes->nom;
           } else {
            return 'Thème inexistant';
           }
        }
    }
    

    // fonction recuperation des questions pour le formulaire de creation et de modification d'une reponse
    public function get_all_questions($id_theme=null) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'nat_quiz_questions';
            // liste des questions
            if(is_numeric($id_theme)) {
                $questions = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE `theme_associer`=$id_theme"));
            } else {
                $questions = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name"));
            }
        return $questions;
    }

    public function get_name_question($id_theme,$id_question) {
        global $wpdb;

        if(is_numeric($id_theme) && is_numeric($id_question)) {
            // recherche de question correspndant à l'id transmis
            $table_name = $wpdb->prefix . 'nat_quiz_questions';
            $questions = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM $table_name WHERE `theme_associer` = $id_theme AND `id_questions` = $id_question"
            ));
           if(isset($questions->question)) {
            return $questions->question;
           } else {
            return 'Question inexistante';
           }
        }
    }

    // Fonction pour récupérer les réponses
    public function get_all_reponses() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'nat_quiz_reponses';
            // liste des réponses
            $reponses = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table_name"
        ));
        return $reponses;
    }
    
    public function get_name_reponse($id_theme,$id_question,$id_reponse) {
        global $wpdb;

        if(is_numeric($id_theme) && is_numeric($id_question) && is_numeric($id_reponse)) {
            // recherche de reponse correspndant à l'id transmis
            $table_name = $wpdb->prefix . 'nat_quiz_reponses';
            $reponses = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM $table_name WHERE `id_themes` = $id_theme AND `question_associer` = $id_question AND `reponse_associer` = $id_reponse"
            ));
           if(isset($reponses->reponse)) {
            return $reponses->reponse;
           } else {
            return 'Reponse inexistante';
           }
        }
    }


    /* function general */
   /* réécriture pour mod rewrite 
   public function RewriteClean($value) {
    $value = strtr(trim(strtolower($value)), 
              'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
              'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
    $value = preg_replace('/([^.a-z0-9_]+)/i', '-',$value);
    return $value; 
}	*/	

}