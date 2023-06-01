<?php

// Fonction pour ajouter ou éditer une question
function nat_quiz_save_question()
{
    global $wpdb;
    $final['error']  = false;
    $final['message'] = null;

    $table_name = $wpdb->prefix . 'nat_quiz_questions';

    $id_questions = isset($_POST['id_questions']) ? $_POST['id_questions'] : '';
    $theme_associer = isset($_POST['theme_associer']) ? $_POST['theme_associer'] : '';
    $question = isset($_POST['question']) ? $_POST['question'] : '';

    if (empty($theme_associer) ||empty($question))  {
        $final['message'] = 'Veuillez remplir tous les champs.';
        $final['error']  = true;
    }

    $data = array(
        'theme_associer' => $theme_associer,
        'question' => $question,
    );

    // Ajouter ou mettre à jour la question
    $table_name = $wpdb->prefix . 'nat_quiz_questions';
    if(!$final['error']) {
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
    }
    return $final;
}

// Fonction  pour activer ou désactiver une question
function nat_quiz_active_question($id, $action)
{
    global $wpdb;
    $final['error']  = false;
    $final['message'] = null;
    $table_name = $wpdb->prefix . 'nat_quiz_questions';

    if ($action == 'active') {
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
        $final['message'] = 'La question a bien été désactivée.';
    } else {
        $final['message'] = 'La question n\'est pas désactivée.';
        $final['error']  = true;
    }

    return $final;
}


// Fonction pour supprimer une question
function nat_quiz_delete_questions($id)
{
    global $wpdb;
    $final['error']  = false;
    $final['message'] = null;
    $table_name = $wpdb->prefix . 'nat_quiz_questions';

    if (!is_array($id)) {
        $final['error'] = true;
        $final['message'] = 'Erreur : l\'id n\'est pas un Array';
    }

    if (!$final['error']) {
        // condition pour verifier si tableau simple ou multidimensionnel
        if (isset($id[0]) && is_array($id[0])) {

            // effacement multi entrée
            foreach ($id as $delete_questions) {
                // boucle pour recuperer les ids
                foreach ($delete_questions as $key => $content) {

                    if ($wpdb->delete($table_name, array('id_questions' => $content))) {
                        $final['message'] .= 'La question id ' . $content . ' a bien été supprimée.<br>';
                    } else {
                        $final['message'] .= 'La question id ' . $content . ' n\'est pas supprimée.';
                        $final['error']  = true;
                    }
                }
            }
        } else {
            // effacement simple entrée
            if ($wpdb->delete($table_name, $id)) {
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
 //Fonction pour verif si une réponse existe
 function nat_quiz_verif_reponse_existe($id_questions)
 {
    global $wpdb;

    $table_name = $wpdb->prefix . 'nat_quiz_reponses';
    if ($id_questions > 0) {
        // si une reponse existe avec la question associer
        $questions = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE question_associer = %d",
            $id_questions
        ));
    }
    if (isset($questions->id_reponses)){
        return true;
    } else {
        return false;
    }
    
}

// retourne le numero de la reponse de la question x
 function nat_quiz_get_num_reponse($id_questions)
 {
    global $wpdb;

    $table_name = $wpdb->prefix . 'nat_quiz_reponses';
    if ($id_questions > 0) {
        // si une reponse existe avec la question associer
        $questions = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE question_associer = %d",
            $id_questions
        ));
    }
   return $questions->id_reponses;
}
?>

<div id="nat-quiz-responses">
    <?php
    $response['error'] = null;
    $response['message'] = null;
    $id_question = null;
    $mode = "list";
    $action = null;

    // recuperation de l'id question dans l'url
    if (isset(($_GET['id_question']))) {
        $id_question = $_GET['id_question'];
    }

    // recuperation de l'idt question dans l'url
    if (isset(($_GET['idt']))) {
        $idt = $_GET['idt'];
    }

    // recuperation de l'idd question dans l'url
    if (isset(($_GET['idd']))) {
        $idd = $_GET['idd'];
    }

    // recuperation de l'id_t question dans l'url
    if (isset(($_GET['id_t']))) {
        $id_t = $_GET['id_t'];
    }

    // recuperation de l'id_q question dans l'url
    if (isset(($_GET['id_q']))) {
        $id_q = $_GET['id_q'];
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
    if ($mode == 'list' && isset($idt) && $idt > 0 && $action != '') {
        $response = nat_quiz_active_question($idt, $action);
    }

    // fonction pour supprimer une entrée
    if ($action == 'delete') {
        if (is_numeric($idd)) {
            $response = nat_quiz_delete_questions(array('id_questions' => $idd));
            //print_r($response);
        } else {
            $response['error'] = true;
            $response['message'] = 'L\'id question n\'est pas numérique.';
        }
    }

    // fonction supprimer de masse
    if (isset($_POST['all_delete_id_questions'])) {
        if (isset($_POST['id_questions_check']) && is_array($_POST['id_questions_check'])) {
            foreach ($_POST['id_questions_check'] as $toto => $id_delete) {

                // creation du tableau des IDs
                $tmp['id_questions'] = $id_delete;

                $tabl_delete[] = $tmp;
            }
            $response = nat_quiz_delete_questions($tabl_delete);
        }
    }

    // gestion des messages de retour
    if ($response['error']) {
        echo '<div class="error">' . $response['message'] . '</div>';
    } else {
        if ($response['message'] != '') {
            echo '<div class="notice notice-success">' . $response['message'] . '</div>';
        }
    }

    // récuperation de la question de l'id correspondant
    $questions = nat_quiz_get_questions($id_question);
    ?>
</div>

<!-- formulaire pour modifier un question -->
<?php
if ($mode == "edit") {
    echo '<h1>Edition de la question</h1><hr>';
?>
<!-- <pre><?php print_r($_POST) ?></pre>-->
    <form action="<?= admin_url() ?>admin.php?page=nat-quiz-questions"  class="wp-admin" method="post">
        <input id="id_questions" type="hidden" name="id_questions" value="<?php echo $questions->id_questions; ?>">
        <table>
            <tr>
                <td><label for="theme_associer">Thème relié à la question :</label></td>
                <td><select id="theme_associer" name="theme_associer">
                    <option value="">-- Choix du thème --</option>
                    <?php 
                    $natquiz = new natquiz;
                    $allthemes = $natquiz->get_all_themes();
                    foreach($allthemes as $key => $th) {
                        if($questions->theme_associer==$th->id_themes) {
                            echo '<option value="'.$th->id_themes.'" selected>'.$th->nom.'</option>';
                        } else {
                            echo '<option value="'.$th->id_themes.'">'.$th->nom.'</option>';
                        }
                    }
                    ?>
                    </select>
                </td>
            </tr> 
            <tr>
                <td><label for="question">Question : </label></td>
                <td><textarea name="question" required="true"><?php echo $questions->question; ?></textarea></td>
            </tr>
    </table>
        <p class="submit">
            <button type="submit" name="nat-quiz-add-edit" value="1" class="button button-primary">Enregistrer</button>
        </p>
    </form>

    <p class="submit">
        <a href="<?= admin_url() ?>admin.php?page=nat-quiz-questions" class="button button-primary">Retour</a>
        <a href="<?= admin_url() ?>admin.php?page=nat-quiz-themes" class="button button-primary">Retour à la liste des thèmes</a>
    </p>
    <hr>
<?php
} else {
?>

<?php } ?>

       <!-- <pre><?php print_r($_POST); ?></pre> -->

<!-- formulaire pour ajouter un question -->
<?php
if ($mode == "add") {
    echo '<h1>Ajouter une question Quiz</h1><hr>';
?>

    <form action="<?= admin_url() ?>admin.php?page=nat-quiz-questions" class="wp-admin" method="post">
    <input type="hidden" name="theme_associer" value="<?=$_GET['id_t']?>">
        <table>
             <tr>
                <td><label for="theme_associer">Thème relié à la question :</label></td>
                
                <td>
                    <?php 
                        $natquiz = new natquiz;
                        echo $natquiz->get_name_theme($_GET['id_t']);
                    ?>   
                </td>
            </tr> 
            </br>
            <tr>
                <td><label for="question">Question : </label></td>
                <td><textarea name="question" required="true"></textarea></td>
            </tr>
        </table>
        <p class="submit">
            <button type="submit" name="nat-quiz-add-edit" value="1" class="button button-primary">Enregistrer</button>
        </p>  
    </form>

    <p class="submit">
        <a href="<?= admin_url() ?>admin.php?page=nat-quiz-themes&id_t=<?$themes->nom?>" class="button button-primary">Retour au thème</a>
        <a href="<?= admin_url() ?>admin.php?page=nat-quiz-themes" class="button button-primary">Retour à la liste des thèmes</a>
        <a href="<?= admin_url() ?>admin.php?page=nat-quiz-questions" class="button button-primary">Retour à la liste des questions</a>
    </p>
    <hr>
<?php
} else {
?>

<?php } ?>


<div id="questions-list">
    <!-- La liste des questions sera affichée ici -->
    <?php
    if ($mode == "list") {
        echo '<h1>Liste des questions Quiz</h1>'; ?>
        <!-- <p class="submit">
            <a href="<?= admin_url() ?>admin.php?page=nat-quiz-questions&mode=add" class="button button-primary">Ajouter une nouvelle question</a>
        </p> -->
        <?php echo '<h2>Ici vous pouvez gérer les questions de votre quiz.</h2><hr>';
        ?>

        <form name="form" method="post" action="">
            <table class="wp-list-table widefat fixed striped">
                <tr><label><strong>Sélectionner la ou les questions à supprimer : </strong></label></tr><br />
            </table>

            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Thème</th>
                        <th>Question</th>
                        <th>Date de création</th>
                        <th>Active</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $natquiz = new natquiz;
                    foreach ($questions as $question) {
                    ?>
                        <tr>
                            <td>
                                <div id="checkbox">
                                    <input type="checkbox" class="verif_ok" name="id_questions_check[]" value="<?php echo $question->id_questions; ?>" id="id_<?php echo $question->id_questions; ?>">
                                </div>
                            </td>
                            <td>
                                <?php echo $natquiz->get_name_theme($question->theme_associer); ?>
                            </td>
                            <td>
                                <?php echo  $question->question; ?>
                            <!-- <?php echo $natquiz->get_name_theme($question->id_questions); ?> -->
                            <!-- <?php echo $natquiz->get_name_theme($question->theme_associer); ?> -->
                               
                                </br>
                                <?php if (nat_quiz_verif_reponse_existe($question->id_questions)) { ?>
                                    <a href="<?= admin_url() ?>admin.php?page=nat-quiz-reponses&id_reponse=<?=nat_quiz_get_num_reponse($question->id_questions)?>&mode=edit" class="button button-primary">Editer des réponses</a>
                                <?php } else { ?>
                                    <a href="<?= admin_url() ?>admin.php?page=nat-quiz-reponses&mode=add&id_t=<?=$question->theme_associer?>&id_q=<?=$question->id_questions?>" class="button button-primary">Ajouter des réponses</a>
                                <?php } ?>
                            </td>
                            <td>
                                <?php echo $question->date_creation; ?>
                            </td>
                            <td>
                                <?php if ($question->active == 0) { ?>
                                    <a class="button button-primary edit-question" href="<?= admin_url() ?>admin.php?page=nat-quiz-questions&idt=<?php echo $question->id_questions; ?>&mode=list&action=active">Activer</a>
                                <?php } else { ?>
                                    <a class="button button-secondary edit-question" href="<?= admin_url() ?>admin.php?page=nat-quiz-questions&idt=<?php echo $question->id_questions; ?>&mode=list&action=desactive">Désactiver</a>
                                <?php } ?>
                            </td>
                            <td>
                                <a class="button button-secondary edit-question" href="<?= admin_url() ?>admin.php?page=nat-quiz-questions&id_question=<?php echo $question->id_questions; ?>&mode=edit&mode=edit">Modifier</a>

                                <a class="button button-secondary delete-question" href="<?= admin_url() ?>admin.php?page=nat-quiz-questions&idd=<?php echo $question->id_questions; ?>&action=delete&mode=list" onclick="return(confirm('Etes-vous sûr de vouloir supprimer ?'));">Supprimer</a>
                            </td>
                        </tr>

                    <?php } ?>
                </tbody>
            </table>
            <div id="delete_questions">
                <button type="submit" class="button button-secondary delete-questions" name="all_delete_id_questions" id="submit" disabled="true" onclick="return(confirm('Etes-vous sûr de vouloir supprimer ?'));">Supprimer les questions sélectionnées</button>
            </div>
        </form>

    <?php
    } else {
    ?>

    <?php } ?>
    nat : 
<?php if(isset($_SESSION['nat'])) echo $_SESSION['nat']; ?>
     <!-- <pre><?php print_r($_POST) ?></pre> -->
</div>

<script>
    jQuery(document).ready(function($) {
        // on définit la variable en faux
        var verif_coche = false;

        // quand on click sur une case
        $(".verif_ok").click(function() {
            // boucle pour verifier les cases cochées
            $(".verif_ok").each(function(index) {
                // si verif_coche est égal à faux et qu'elle est cochée
                if (!verif_coche && $(this).is(':checked') == true) {
                    // alors elle est definit en vrai
                    verif_coche = true;
                }
                // si le nbre de case cochée est égal à 0
                if ($('.verif_ok:checked').length == 0) {
                    // alors elle est definit en faux
                    verif_coche = false;
                }
            }); 
            // si verif_coche est vrai on active le bouton
            if (verif_coche) {
                $('button[name="all_delete_id_questions"]').attr('disabled', false);
            } else {
                // sinon on le desactive
                $('button[name="all_delete_id_questions"]').attr('disabled', true);
            }
        });
    });

     
</script>