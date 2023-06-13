<?php

// Fonction pour ajouter ou éditer une réponse
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

// Fonction pour activer ou désactiver les réponses
function nat_quiz_active_reponse($id, $action)
{
    global $wpdb;
    $final['error']  = false;
    $final['message'] = null;
    $table_name = $wpdb->prefix . 'nat_quiz_reponses';

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

    if ($wpdb->update($table_name, $data, array('id_reponses' => $id))) {
        $final['message'] = 'Les reponses ont bien été désactivées.';
    } else {
        $final['message'] = 'Les reponses n\'ont pas été désactivées.';
        $final['error']  = true;
    }

    return $final;
}


// Fonction pour supprimer les réponses
function nat_quiz_delete_reponses($id)
{
    global $wpdb;
    $final['error']  = false;
    $final['message'] = null;
    $table_name = $wpdb->prefix . 'nat_quiz_reponses';

    if (!is_array($id)) {
        $final['error']  = true;
        $final['message'] = 'Erreur : l\'id n\'est pas un Array';
    }

    if (!$final['error']) {
        // condition pour verifier si tableau simple ou multidimensionnel
        if (isset($id[0]) && is_array($id[0])) {

            // effacement multi entrée
            foreach ($id as $delete_reponses) {
                // boucle pour recuperer les ids
                foreach ($delete_reponses as $key => $content) {

                    if ($wpdb->delete($table_name, array('id_reponses' => $content))) {
                        $final['message'] .= 'Les réponses id ' . $content . ' ont bien été supprimées.<br>';
                    } else {
                        $final['message'] .= 'Les réponses id ' . $content . ' n\'ont pas été supprimées.';
                        $final['error']  = true;
                    }
                }
            }
        } else {
            // effacement simple entrée
            if ($wpdb->delete($table_name, $id)) {
                $final['message'] = 'Les réponses ont bien été supprimées.';
            } else {
                $final['message'] = 'Les réponses n\'ont pas été supprimées.';
                $final['error']  = true;
            }
        }
    }
    return $final;
}


// Fonction pour récupérer les réponses d'un thème 
function nat_quiz_get_reponses($id_reponses = 0)
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'nat_quiz_reponses';
    if ($id_reponses > 0) {
        // si id_reponse
        $reponses = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE id_reponses = %d",
            $id_reponses
        ));
    } else {

        // liste des réponses
        $reponses = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table_name"
        ));
    }
    return $reponses;
}

?>

<div id="nat-quiz-responses">
    <?php
    $response['error'] = null;
    $response['message'] = null;
    $id_reponse = null;
    $mode = "list";
    $action = null;

    // récuperation de l'id réponse dans l'url
    if (isset(($_GET['id_reponse']))) {
        $id_reponse = $_GET['id_reponse'];
    }

    // récuperation de l'idt réponse dans l'url
    if (isset(($_GET['idt']))) {
        $idt = $_GET['idt'];
    }

    // recuperation de l'idd reponse dans l'url
    if (isset(($_GET['idd']))) {
        $idd = $_GET['idd'];
    }

    //// recuperation de l'id_t reponse dans l'url
    if (isset(($_GET['id_t']))) {
        $id_t = $_GET['id_t'];
    }

    // recuperation de l'id_q reponse dans l'url
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
        $response = nat_quiz_save_reponse();
    }

    // active / desactive les réponses
    if ($mode == 'list' && isset($idt) && $idt > 0 && $action != '') {
        $response = nat_quiz_active_reponse($idt, $action);
    }

    // fonction pour supprimer une entrée
    if ($action == 'delete') {
        if (is_numeric($idd)) {
            $response = nat_quiz_delete_reponses(array('id_reponses' => $idd));
            //print_r($response);
        } else {
            $response['error'] = true;
            $response['message'] = 'L\'id reponse n\'est pas numérique.';
        }
    }

    // fonction supprimer de masse
    if (isset($_POST['all_delete_id_reponses'])) {
        if (isset($_POST['id_reponses_check']) && is_array($_POST['id_reponses_check'])) {
            foreach ($_POST['id_reponses_check'] as $toto => $id_delete) {

                // creation du tableau des IDs
                $tmp['id_reponses'] = $id_delete;

                $tabl_delete[] = $tmp;
            }
            $response = nat_quiz_delete_reponses($tabl_delete);
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
    $reponses = nat_quiz_get_reponses($id_reponse);
    ?>
</div>

<!-- formulaire pour créer les réponses -->
<?php
if ($mode == "edit") {
    echo '<h1>Edition des réponses</h1><hr>';
?>

    <form action="<?= admin_url() ?>admin.php?page=nat-quiz-reponses"  class="wp-admin" method="post">
        <input id="theme_associer" type="hidden" name="theme_associer" value="<?php echo $reponses->theme_associer; ?>">
        <input id="id_reponses" type="hidden" name="id_reponses" value="<?php echo $reponses->id_reponses; ?>">

        <table>
            <tr> 
                <!--<pre><?php print_r($_POST) ?></pre>-->
                <td><label for="theme_associer">Thème relié à la question :</label></td>
                <td>
                    <?php 
                    $natquiz = new natquiz;
                    echo stripslashes($natquiz->get_name_theme($reponses->theme_associer));
                    ?>
                </td>
            </tr>
            <tr>
                <td><label for="question_associer">Question relié à la réponse :</label></td>
                <td><select id="question_associer" name="question_associer">
                    <option value="">-- Choix de la question --</option>
                    <?php 
                    $allquestions = $natquiz->get_all_questions($reponses->theme_associer);
                    foreach($allquestions as $key => $th) {
                        if($reponses->question_associer==$th->id_questions) {   
                            echo '<option value="'.$th->id_questions.'" selected>'.stripslashes($th->question).'</option>';
                        } else {
                            echo '<option value="'.$th->id_questions.'">'.stripslashes($th->question).'</option>';
                        }
                    }
                    ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="mauvaise_reponses">Mauvaises réponses (Une réponse par ligne) :</label></td>
                <td><textarea style="background-color:lightcoral ;width:100%;" id="mauvaise_reponses" name="mauvaise_reponses" rows="2" required="true"><?php echo stripslashes($reponses->mauvaise_reponses); ?></textarea></td>
            </tr>
            <tr>
                <td><label for="bonne_reponse">Bonne réponse :</label></td>
                <td><input type="text" style="background-color:lightgreen ;width:100%;" id="bonne_reponse" name="bonne_reponse" required="true" value="<?php echo stripslashes($reponses->bonne_reponse); ?>"></td>
            </tr>
        </table>

        <p class="submit">
            <button type="submit" name="nat-quiz-add-edit" value="1" class="button button-primary">Enregistrer</button>
        </p>
    </form>

    <p class="submit">
        <a href="<?= admin_url() ?>admin.php?page=nat-quiz-reponses" class="button button-primary">Retour</a>
        <a href="<?= admin_url() ?>admin.php?page=nat-quiz-questions" class="button button-primary">Retour à la liste des questions</a>
    </p>
    <hr>
<?php
} else {
?>

<?php } ?>


<!-- formulaire pour ajouter des réponses -->
<?php
if ($mode == "add") {
    echo '<h1>Ajouter des réponses Quiz</h1><hr>';
?>

    <form action="<?=admin_url()?>admin.php?page=nat-quiz-reponses" class="wp-admin" method="post">
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
            <tr>
                <td><label for="question_associer">Question reliée à la réponse :</label></td>
                <td><select id="question_associer" name="question_associer">
                <option value="">-- Choix de la question --</option>
                    <?php 
                    $natquiz = new natquiz;
                    $questions = $natquiz->get_all_questions($_GET['id_t']);
                    foreach($questions as $key => $th) { 
                        if(stripslashes($_GET['id_q'])==$th->id_questions)  {
                            echo '<option value="'.$th->id_questions.'" selected>'.$th->question.'</option>' ;
                        } else {
                            echo '<option value="'.$th->id_questions.'">'.$th->question.'</option>';
                        }
                    }
                    ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="mauvaise_reponses">Mauvaise réponse (Une réponse par ligne):</label></td>
                <td><textarea style="background-color:lightcoral;width:100%;" name="mauvaise_reponses" rows="2" required="true"></textarea></td>
            </tr>
            <tr>
                <td><label for="bonne_reponse">Bonne réponse:</label></td>
                <td><input type="text" style="background-color:lightgreen;width:100%;" name="bonne_reponse" required="true"></td>
            </tr>

        </table>

        <p class="submit">
            <button type="submit" name="nat-quiz-add-edit" value="1" class="button button-primary">Enregistrer</button>
        </p>
        </table>
    </form>

    <p class="submit">
        <a href="<?= admin_url() ?>admin.php?page=nat-quiz-themes" class="button button-primary">Retour à la liste des thèmes</a>
        <a href="<?= admin_url() ?>admin.php?page=nat-quiz-questions" class="button button-primary">Retour à la liste des questions</a>
        <a href="<?= admin_url() ?>admin.php?page=nat-quiz-reponses" class="button button-primary">Retour à la liste des réponses</a>
    </p>
    <hr>
<?php
} else {
?>

<?php } ?>


<div id="reponses-list">
    <!-- La liste des réponses sera affichée ici -->
    <?php
    if ($mode == "list") {
        echo '<h1>Liste des réponses Quiz</h1>'; ?>
        <!-- <p class="submit">
            <a href="<?= admin_url() ?>admin.php?page=nat-quiz-reponses&mode=add" class="button button-primary">Ajouter des nouvelles réponses</a>
        </p> -->
        <?php echo '<h2>Ici vous pouvez gérer les réponses de votre quiz.</h2><hr>';
        ?>

        <form name="form" method="post" action="">
            <table class="wp-list-table widefat fixed striped">
                <tr><label><strong>Sélectionner la ou les réponses à supprimer : </strong></label></tr><br/>
                <button type="button" class="button button-secondary select_all_themes" name="all_coche_id_themes" id="all_coche"  >Cocher toutes les réponses</button>

                <td><label for="theme_associer">Choisissez un thème :</label></td>
                <td><select id="filtre_theme" name="select_theme">
                    <option value=""> Afficher tous les thèmes </option>
                    <?php 
                    $natquiz = new natquiz;
                    $allthemes = $natquiz->get_all_themes();
                    foreach($allthemes as $key => $th) {
                        if($questions->theme_associer==$th->id_themes) {
                            echo '<option value="filtre_'.$th->id_themes.'" selected>'.$th->nom.'</option>';
                        } else {
                            echo '<option value="filtre_'.$th->id_themes.'">'.$th->nom.'</option>';
                        }
                    }
                    ?>
                    </select>
                </td>
            </table>

            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Thème</th>
                        <th>Question</th>
                        <th>Mauvaises réponses</th>
                        <th>Bonne réponse</th>
                        <th>Score</th>
                        <th>Active</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                     $natquiz = new natquiz;
                    foreach ($reponses as $reponse) {
                    ?>
                        <tr class="applyfiltre filtre_<?=$reponse->theme_associer?>">
                            <td>
                                <div id="checkbox">
                                    <input type="checkbox" class="verif_ok" name="id_reponses_check[]" value="<?php echo $reponse->id_reponses; ?>" id="id_<?php echo $reponse->id_reponses; ?>">
                                </div>
                            </td>
                            <td>
                                <?php echo $natquiz->get_name_theme($reponse->theme_associer); ?>
                            </td>
                            <td>
                                
                                <?php echo stripslashes($natquiz->get_name_question($reponse->theme_associer,$reponse->question_associer)); ?>
                            </td>
                            <td>
                                <?php 
                                $mv_reponses = explode("\n",stripslashes($reponse->mauvaise_reponses)); 
                                echo '<ul>';
                                foreach($mv_reponses as $mv_rep) {
                                    echo '<li>'.$mv_rep.'</li>';
                                }
                                echo '</ul>';
                                ?>
                            </td>
                            <td>
                                <?php echo stripslashes($reponse->bonne_reponse); ?>
                            </td>
                            <td>
                                <?php echo $reponse->score; ?>
                            </td>
                            <td>
                                <?php if ($reponse->active == 0) { ?>
                                    <a class="button button-primary edit-reponse" href="<?= admin_url() ?>admin.php?page=nat-quiz-reponses&idt=<?php echo $reponse->id_reponses; ?>&mode=list&action=active">Activer</a>
                                <?php } else { ?>
                                    <a class="button button-secondary edit-reponse" href="<?= admin_url() ?>admin.php?page=nat-quiz-reponses&idt=<?php echo $reponse->id_reponses; ?>&mode=list&action=desactive">Désactiver</a>
                                <?php } ?>
                            </td>
                            <td>
                                <a class="button button-secondary edit-reponse" href="<?= admin_url() ?>admin.php?page=nat-quiz-reponses&id_reponse=<?php echo $reponse->id_reponses; ?>&mode=edit&mode=edit">Modifier</a>

                                <a class="button button-secondary delete-reponse" href="<?= admin_url() ?>admin.php?page=nat-quiz-reponses&idd=<?php echo $reponse->id_reponses; ?>&action=delete&mode=list" onclick="return(confirm('Etes-vous sûr de vouloir supprimer ?'));">Supprimer</a>
                            </td>
                        </tr>

                    <?php } ?>
                </tbody>
            </table>
            <div id="delete_reponses">
                <button type="submit" class="button button-secondary delete-reponses" name="all_delete_id_reponses" id="submit" disabled="true" onclick="return(confirm('Etes-vous sûr de vouloir supprimer ?'));">Supprimer les réponses sélectionnées</button>
            </div>
        </form>

    <?php
    } else {
    ?>

    <?php } ?>

    <!-- <pre><?php print_r($_POST) ?></pre> -->
    
</div>

<script>
    jQuery(document).ready(function($) {

        $(document).on('change', '#filtre_theme', function() {
            var filtre_theme = $(this).val();
            if(filtre_theme == '') {
                $(".applyfiltre").show(); 
            } else {
                $(".applyfiltre").hide();
                $("."+$(this).val()).show();
            }
  //console.log($(this).val());

        });


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
                $('button[name="all_delete_id_reponses"]').attr('disabled', false);
            } else {
                // sinon on le desactive
                $('button[name="all_delete_id_reponses"]').attr('disabled', true);
            }
        });

//script pou cocher toutes les cases en une seule fois 
    // pour verifier les cases cochées
    $("#all_coche").click(function() {
        $(':checkbox').each(function(index) {
            this.checked = true;
        });
    }); 

    });

</script>

