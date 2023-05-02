<?php


// Fonction pour ajouter ou éditer une question
function nat_quiz_save_question()
{
    global $wpdb;
    $final['error']  = false;
    $final['message'] = null;

    $table_name = $wpdb->prefix . 'nat_quiz_questions';

    $id_questions = isset($_POST['id_questions']) ? $_POST['id_questions'] : '';
    $theme_id = isset($_POST['theme_id']) ? $_POST['theme_id'] : '';
    $question = isset($_POST['question']) ? $_POST['question'] : '';
    $answers = isset($_POST['answers']) ? $_POST['answers'] : '';

    if (empty($theme_id) || empty($question) || empty($answers)) {
        $final['message'] = 'Veuillez remplir tous les champs.';
        $final['error']  = true;

    }

    $data = array(
        'theme_id' => $theme_id,
        'question' => $question,
        'answers' => $answers
    );

    // Ajouter ou mettre à jour la question
    if (empty($id_questions)) {
        if ($wpdb->insert($table_name, $data)) {
            $final['message'] = 'La question est bien ajoutée.';

    } else {
        $final['message'] = 'La question n\'est pas ajoutée.';
            $final['error'] = true;

    }
    } else {
        if ($wpdb->update($table_name, $data, array('id_questions' => $id_questions))) {
            $final['message'] = 'La question a bien été éditée.';
    } else {
        $final['message'] = 'Le question n\'est pas éditée.';
            $final['error']  = true;
    }

}
    return $final;
}

// Fonction pour ajouter ou éditer une question
function nat_quiz_active_question($id,$action) {
    global $wpdb;
    $final['error']  = false;
    $final['message'] = null;
    $table_name = $wpdb->prefix . 'nat_quiz_questions';

    if($action=='active') {
        // update sql de 0 vers 1
        $data = array(
            'active' => '1'
        );
        
    } else {
        // update sql de 1 vers 0
        $data = array(
            'active' => '0'
        );
    } 

    if ($wpdb->update($table_name, $data, array('id_questions' => $id))) {
        $final['message'] = 'La question a bien été désactivéé.';
    } else {
        $final['message'] = 'La question n\'est pas désactivée.';
        $final['error']  = true;
    }

    return $final;
}


// Fonction pour supprimer une question
function nat_quiz_delete_question($id)
{
    global $wpdb;
    $final['error']  = false;
    $final['message'] = null;
    $table_name = $wpdb->prefix . 'nat_quiz_questions';

    if(!is_array($id)) {
        $final['error']  = true;
        $final['message'] = 'Erreur : l\'id n\'est pas un Array';
    }

    if(!$final['error']) {
        // condition pour verifier si tableau simple ou multidimensionnel
        if(isset($id[0]) && is_array($id[0])) {
           
            // effacement multi entrée
           foreach($id as $delete_questions){
            // boucle pour recuperer les ids
                foreach($delete_questions as $key => $content){
                   
                    if($wpdb->delete($table_name, array('id_questions' => $content))) {
                        $final['message'] .= 'La question id '.$content.' à bien été suppriméé.<br>';
                    } else {
                        $final['message'] .= 'La question id '.$content.' n\'est pas supprimée.';
                        $final['error']  = true;
                    }
                }
           } 
        } else {
            // effacement simple entrée
            if($wpdb->delete($table_name, $id)) {
            $final['message'] = 'La question a bien été supprimée.';
            } else {
                $final['message'] = 'La question n\'est pas supprimée.';
                $final['error']  = true;
            }
        }
    
    }
    return $final;
}


// Fonction pour récupérer les questions d'un thème 
function nat_quiz_get_questions($id_questions = 0)
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'nat_quiz_questions';
    if ($id_questions > 0) {
        // si id_question
        $questions = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE id_questions = %d",
            $id_questions
        ));
    } else {

        // liste des questions
        $questions = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table_name"
        ));
    }
    return $questions;
}

?>

<div id="nat-quiz-responses">
    <?php
    $response['error'] = null;
    $response['message'] = null;
    $id_theme = null;
    $mode = "list";

    // recuperation de l'id question dans l'url
    if (isset(($_GET['id_question']))) {
        $id_question = $_GET['id_question'];
    }

    // recuperation de l'id question dans l'url
    if (isset(($_GET['idt']))) {
        $idt = $_GET['idt'];
    }

    // récuperation de la variable mode dans l'url
    if (isset(($_GET['mode']))) {
        $mode = $_GET['mode'];
    }

    // récuperation de la variable action dans l'url
    if (isset(($_GET['action']))) {
        $action = $_GET['action'];
    }

    if (!empty($_POST['nat-quiz-add-edit'])) {
        $response = nat_quiz_save_question();
    }

    // active / desactive question
    if($mode == 'list' && isset($idt) && $idt>0 && $action != '') {
        $response = nat_quiz_active_question($idt,$action);
    }

    // fonction pour supprimer une entrée
    if($mode == 'delete') {
        if(is_numeric($id_question)){
            $response = nat_quiz_delete_question(array('id_questions' => $id_question));
            //print_r($response);
        }else{
            $response['error'] = true;
            $response['message'] = 'L\'id question n\'est pas numérique.';
        }
        
    }

    // fonction supprimer de masse
    if(isset($_POST['all_delete_id_questions'])) {
        if(isset($_POST['id_questions_check']) && is_array($_POST['id_questions_check'])) {
         foreach($_POST['id_questions_check'] as $toto => $id_delete) {

            // creation du tableau des IDs
            $tmp['id_questions'] = $id_delete;
            
            $tabl_delete[] = $tmp;
        }
        $response = nat_quiz_delete_question($tabl_delete);
      
       }
    }

    // gestion des messages de retour
    if ($response['error']) {
        echo '<div class="error">' . $response['message'] . '</div>';
    } else {
       if($response['message'] != '') {
        echo '<div class="notice notice-success">' . $response['message'] . '</div>';
    }
    }

    // récuperation de la question de l'id correspondant
    $questions = nat_quiz_get_questions($id_question); 
    ?>
</div>

<!-- formulaire pour créer un question -->
<?php
if ($mode == "edit") {
    echo '<h1>Edition de la question</h1><hr>';
?>

<form id="nat-quiz-question-form" class="wp-admin" method="post" action="">
    <input id="id_questions"  type="hidden" name="id_questions" value="<?php echo $questions->id_questions; ?>">
    
    <table>
            <tr>
                <td><label for="theme_id">ID du thème :</label></td>
                <td><input id="theme_id" type="text" name="theme_id" required="true" value="<?php echo $question->theme_id; ?>" /></td>
            </tr>

            <tr>
                <td><label for="question">Question : </label></td>
                <td><textarea name="question" required="true"><?php echo $question->question; ?></textarea></td>
            </tr>

            <tr>
                <td><label for="answers">Réponses (séparées par des virgules) :</label></td>
                <td><type="text" name="answers" required="true"><?php echo $question->question; ?></textarea></td>
            </tr>
    
    </table>
    
    <p class="submit">
            <button type="submit" name="nat-quiz-add-edit" value="1" class="button button-primary">Enregistrer</button>
    </p>
</form>

    <p class="submit">
            <a href="<?= admin_url() ?>admin.php?page=nat-quiz-questions" class="button button-primary">Retour</a>
    </p>
<hr>
<?php
} else {
?>

<?php } ?>


<!-- formulaire pour ajouter un question -->
<?php
if ($mode == "add") {
        echo '<h1>Ajouter une question Quiz</h1><hr>';
    ?>

    <form action="<?= admin_url() ?>admin.php?page=nat-quiz-questions" class="wp-admin" method="post">
    
        <table>
            <tr>
                <td><label for="theme_id">ID du thème :</label></td>
                <td><input id="theme_id" type="text" name="theme_id" required="true" value="" /></td>
            </tr>

            <tr>
                <td><label for="question">Question : </label></td>
                <td><textarea name="question" required="true"></textarea></td>
            </tr>

            <tr>
                <td><label for="answers">Réponses (séparées par des virgules) :</label></td>
                <td><textarea name="answers" required="true"></textarea></td>
            </tr>

        </table>

        <p class="submit">
            <button type="submit" name="nat-quiz-add-edit" value="1" class="button button-primary">Enregistrer</button>
        </p>
    </form>

        <p class="submit">
            <a href="<?= admin_url() ?>admin.php?page=nat-quiz-questions" class="button button-primary">Retour</a>
        </p>
<hr>
<?php 
} else {
?> 

 <?php } ?> 


<div id="questions-list">
    <!-- La liste des questions sera affichée ici -->
</div>
<?php
    if ($mode == "list") {
        echo '<h1>Liste des questions Quiz</h1>'; ?>
        <p class="submit">
            <a href="<?= admin_url() ?>admin.php?page=nat-quiz-questions&mode=add" class="button button-primary">Ajouter une nouvelle question</a>
        </p>
        <?php echo '<h2>Ici vous pouvez gérer les questions de votre quiz.</h2><hr>';
    ?>

    <form name="form" method="post" action="">
            <table class="wp-list-table widefat fixed striped">
                <tr><label><strong>Sélectionner la ou les questions à supprimer : </strong></label></tr><br/>
            </table>

            <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID Thème</th>
                    <th>Question</th>
                    <th>Reponses</th>
                    <th>Date de création</th>
                    <th>Active</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($questions as $question) {
                ?>
                <tr>
                    <td>
                        <div id="checkbox">
                            <input type="checkbox" class="verif_ok" name="id_questions_check[]" value="<?php echo $question->id_questions; ?>" id="id_<?php echo $question->id_questions; ?>" 
                        </div>
                    </td>
                    <td>
                        <?php echo $question->theme_id; ?>
                    </td>
                    <td>
                        <?php echo $question->question; ?>
                    </td>
                    <td>
                        <?php echo $question->answers; ?>
                    </td>
                    <td>
                        <?php echo $question->date_creation; ?>
                    </td>
                    <td>
                        <?php if($question->active==0) {?>
                           inactif 
                           <?php } else { ?>
                            actif
                        <?php } ?>
                    </td>
                    <td>

                        <a class="button button-secondary edit-question" href="<?= admin_url() ?>admin.php?page=nat-quiz-questions&id_question=<?php echo $question->id_questions; ?>&mode=edit&mode=edit">Modifier</a>

                        <a class="button button-secondary delete-question" href="<?= admin_url() ?>admin.php?page=nat-quiz-questions&id_question=<?php echo $question->id_questions; ?>&mode=delete" onclick="return(confirm('Etes-vous sûr de vouloir supprimer ?'));">Supprimer</a>

                        <button id="switchActif" onclick="activer_desactiverQuestions()">Activer / désactiver la question</button>

                    </td>
                    </tr>
                    
                <?php } ?>
            </tbody>
        </table>
<button type="submit" class="button button-secondary delete-questions" name="all_delete_id_questions" id="submit" disabled="true">Supprimer les questions sélectionnées</button> 
</form> 

<?php
    } else {
    ?>
 
    <?php } ?>
   
<!-- <pre><?php print_r($_POST)?></pre> -->

    

    