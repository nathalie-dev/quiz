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

    
    public function get_all_reponses($id_questions=null) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'nat_quiz_reponses';
            // liste des reponses
            if(is_numeric($id_questions)) {
                $reponses = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE `question_associer`=$id_questions"));
            } else {
                $reponses = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name"));
            }
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

    public function verif_good_response($id_question) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'nat_quiz_reponses';
            if(is_numeric($id_question)) {
                $reponses = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE `question_associer`=$id_question"));
            } 
        if(isset($reponses)) {
            return $reponses->bonne_reponse;  
        }
    } 

    public function add_score_by_user($user_id,$soumiss_quiz,$soumiss_result,$theme_id,$pcent_reusite) {
        global $wpdb;
        $final['error']  = false;
        $final['message'] = null;

        $table_name = $wpdb->prefix . 'nat_quiz_score';

        $user_id = isset($_POST['user_id']) ? $_POST['user_id']: '';
        $soumiss_quiz = isset($_POST['soumiss_quiz']) ? $_POST['soumiss_quiz']: '';
        $soumiss_result = isset($_POST['soumiss_result']) ? $_POST['soumiss_result']: '';
        $theme_id = isset($_POST['theme_id']) ? $_POST['theme_id']: '';
        $pcent_reusite = isset($_POST['pcent_reusite']) ? $_POST['pcent_reusite']: '';

        if (empty($user_id) || empty($soumiss_quiz) || empty($soumiss_result) || empty($theme_id) || empty($pcent_reusite)) {
            $final['error']  = 'Le score de l\'utilisateur ne sont pas enregistré';
            $final['message'] = true;
        }

        $data = array('user_id' => $user_id,
                      'id_theme' => $theme_id,
                      'questionnaire' => $soumiss_quiz.$soumiss_result,
                      'score' => $pcent_reusite);
    }
   

   
}

/*// Fonction pour ajouter ou éditer une réponse
function nat_quiz_save_reponse()
{
    global $wpdb;
    $final['error']  = false;
    $final['message'] = null;

    $table_name = $wpdb->prefix . 'nat_quiz_reponses';

    $id_reponses = isset($_POST['id_reponses']) ? $_POST['id_reponses'] : '';
    $theme_associer = isset($_POST['theme_associer']) ? $_POST['theme_associer'] : '';
    $question_associer = isset($_POST['question_associer']) ? $_POST['question_associer'] : '';
    $mauvaise_reponses = isset($_POST['mauvaise_reponses']) ? $_POST['mauvaise_reponses'] : '';
    $bonne_reponse = isset($_POST['bonne_reponse']) ? $_POST['bonne_reponse'] : '';

    if (empty($theme_associer) || empty($question_associer) ||empty($mauvaise_reponses) || empty($bonne_reponse)) {
        $final['message'] = 'Veuillez remplir tous les champs.';
        $final['error']  = true;
    }

    $data = array(
        'theme_associer' => $theme_associer,
        'question_associer' => $question_associer,
        'mauvaise_reponses' => $mauvaise_reponses,
        'bonne_reponse' => $bonne_reponse
    );


    // Ajouter ou mettre à jour la reponse
    $table_name = $wpdb->prefix . 'nat_quiz_reponses';
    if(!$final['error']) {
        if (empty($id_reponses)) {
            if ($wpdb->insert($table_name, $data)) {
                $final['message'] = 'Les réponses sont bien ajoutées.';
            } else {
                $final['message'] = 'Les réponses ne sont pas ajoutées.';
                $final['error'] = true;
            }
        } else {
            if ($wpdb->update($table_name, $data, array('id_reponses' => $id_reponses))) {
                $final['message'] = 'Les réponses ont bien été éditées.';
            } else {
                $final['message'] = 'Les réponses n\'ont pas été éditées.';
                $final['error']  = true;
            }
        
        }
    }
    return $final;
}