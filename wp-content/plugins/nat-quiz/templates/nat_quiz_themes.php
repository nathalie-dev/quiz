<?php

// Fonction pour ajouter ou éditer un thème
function nat_quiz_save_theme()
{
    global $wpdb;
    $final['error']  = false;
    $final['message'] = null;

    $table_name = $wpdb->prefix . 'nat_quiz_themes';

    $id_themes = isset($_POST['id_themes']) ? $_POST['id_themes'] : '';
    $nom = isset($_POST['nom']) ? $_POST['nom'] : '';
    $descriptif = isset($_POST['descriptif']) ? $_POST['descriptif'] : '';
    $image = isset($_POST['image']) ? $_POST['image'] : '';

    if (empty($nom) || empty($descriptif) || empty($image)) {
        $final['message'] = 'Veuillez remplir tous les champs.';
        $final['error']  = true;
    }

    $data = array(
        'nom' => $nom,
        'descriptif' => $descriptif,
        'image' => $image
    );

    // Ajouter ou mettre à jour un thème
    $table_name = $wpdb->prefix . 'nat_quiz_themes';
    if (empty($id_themes)) {
        if ($wpdb->insert($table_name, $data)) {
            $final['message'] = 'Le thème est bien ajouté.';
        } else {
            $final['message'] = 'Le thème n\'est pas ajouté.';
            $final['error'] = true;
        }
    } else {
        if ($wpdb->update($table_name, $data, array('id_themes' => $id_themes))) {
            $final['message'] = 'Le thème a bien été édité.';
        } else {
            $final['message'] = 'Le thème n\'est pas édité.';
            $final['error']  = true;
        }
    }

    return $final;
}

// Fonction pour activer ou désactiver un thème /// 
function nat_quiz_active_theme($id, $action)
{
    global $wpdb;
    $final['error']  = false;
    $final['message'] = null;
    $table_name = $wpdb->prefix . 'nat_quiz_themes';

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

    if ($wpdb->update($table_name, $data, array('id_themes' => $id))) {
        $final['message'] = 'Le thème a bien été désactivé.';
    } else {
        $final['message'] = 'Le thème n\'est pas désactivé.';
        $final['error']  = true;
    }

    return $final;
}

// Fonction pour supprimer un thème
function nat_quiz_delete_themes($id)
{
    global $wpdb;
    $final['error']  = false;
    $final['message'] = null;
    $table_name = $wpdb->prefix . 'nat_quiz_themes';

    if (!is_array($id)) {
        $final['error']  = true;
        $final['message'] = 'Erreur : l\'id n\'est pas un Array';
    }


    if (!$final['error']) {
        // condition pour verifier si tableau simple ou multidimensionnel
        if (isset($id[0]) && is_array($id[0])) {

            // effacement multi entrée
            foreach ($id as $delete_themes) {
                // boucle pour recuperer les ids
                foreach ($delete_themes as $key => $content) {

                    if ($wpdb->delete($table_name, array('id_themes' => $content))) {
                        $final['message'] .= 'Le thème id ' . $content . ' à bien été supprimé.<br>';
                    } else {
                        $final['message'] .= 'Le thème id ' . $content . ' n\'est pas supprimé.';
                        $final['error']  = true;
                    }
                }
            }
        } else {
            // effacement simple entrée
            if ($wpdb->delete($table_name, $id)) {
                $final['message'] = 'Le thème a bien été supprimé.';
            } else {
                $final['message'] = 'Le thème n\'est pas supprimé.';
                $final['error']  = true;
            }
        }
    }
    return $final;
}

// Fonction pour récupérer un thème 
function nat_quiz_get_themes($id_themes = 0)
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'nat_quiz_themes';
    if ($id_themes > 0) {
        // si id_theme
        $themes = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE id_themes = %d",
            $id_themes
        ));
    } else {

        // liste des themes
        $themes = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table_name"
        ));
    }
    return $themes;
}

?>

<div id="nat-quiz-responses">
    <?php
    $response['error'] = null;
    $response['message'] = null;
    $id_theme = null;
    $mode = "list";
    $action = null;

    // recuperation de l'id theme dans l'url
    if (isset(($_GET['id_theme']))) {
        $id_theme = $_GET['id_theme'];
    }

    // recuperation de l'idt theme dans l'url
    if (isset(($_GET['idt']))) {
        $idt = $_GET['idt'];
    }

    // recuperation de l'idd theme dans l'url
    if (isset(($_GET['idd']))) {
        $idd = $_GET['idd'];
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
        $response = nat_quiz_save_theme();
    }


    // active / desactive theme
    if ($mode == 'list' && isset($idt) && $idt > 0 && $action != '') {
        $response = nat_quiz_active_theme($idt, $action);
    }


    // fonction pour supprimer une entrée
    if ($action == 'delete') {
        if (is_numeric($idd)) {
            $response = nat_quiz_delete_themes(array('id_themes' => $idd));
            //print_r($response);
        } else {
            $response['error'] = true;
            $response['message'] = 'L\'id thème n\'est pas numérique.';
        }
    }

    // fonction supprimer de masse
    if (isset($_POST['all_delete_id_themes'])) {
        if (isset($_POST['id_themes_check']) && is_array($_POST['id_themes_check'])) {
            foreach ($_POST['id_themes_check'] as $caca => $id_delete) {

                // creation du tableau des IDs
                $tmp['id_themes'] = $id_delete;

                $tabl_delete[] = $tmp;
            }
            $response = nat_quiz_delete_themes($tabl_delete);
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


    // récuperation du theme de l'id correspondant
    $themes = nat_quiz_get_themes($id_theme);
    ?>
</div>

<!-- formulaire pour créer un theme -->
<?php
if ($mode == "edit") {
    echo '<h1>Edition du thème</h1><hr>';
?>

    <form action="<?= admin_url() ?>admin.php?page=nat-quiz-themes"  class="wp-admin" method="post">
        <input id="id_themes" type="hidden" name="id_themes" value="<?php echo $themes->id_themes; ?>" />


        <table>
            <tr>
                <td><label for="nom">Nom du thème : </label></td>
                <td><input id="nom" type="text" name="nom" required="true" value="<?=$themes->nom?>" /></td>
                
            </tr>
            <tr>
                <td><label for="descriptif">Descriptif : </label></td>
                <td><textarea id="descriptif" name="descriptif" required="true"><?php echo $themes->descriptif; ?></textarea></td>
            </tr>
        </table>

        <p class="submit">
            <button type="submit" name="nat-quiz-add-edit" value="1" class="button button-primary">Enregistrer</button>
        </p>
    </form>
    <p class="submit">
        <a href="<?= admin_url() ?>admin.php?page=nat-quiz-themes" class="button button-primary">Retour à la liste des thèmes</a>
    </p>
    <hr>
<?php
} else {
?>

<?php } ?>


<!-- formulaire pour ajouter un theme -->
<?php
if ($mode == "add") {
    echo '<h1>Ajouter un thème Quiz</h1><hr>';
?>

    <form action="<?= admin_url() ?>admin.php?page=nat-quiz-themes" class="wp-admin" method="post">
     <!-- <input type="hidden" name="nom" value="<?=$_GET['id_t']?>"> -->
        <table>
            <tr>
                <td><label for="nom">Nom du thème : </label></td>
                <td><input id="nom" type="text" name="nom" required="true" /></td> 
                <td>
                    <?php
                    $natquiz = new natquiz;
                    ?>
                </td>
            </tr>
            <tr>
                <td><label for="descriptif">Descriptif : </label></td>
                <td><textarea name="descriptif" required="true"></textarea></td>
            </tr>
            <tr>
                <td><label for="image">Choisir une photo correspondant au thème:</label></td>
                <td><input type="file" id="image" img src="/quiz/wp-content/plugins/nat-quiz/image/<?= $theme->image ?>" style="width:180px;height:120px"> 
                </td>
            </tr>
        </table>

        <p class="submit">
            <button type="submit" name="nat-quiz-add-edit" value="1" class="button button-primary">Enregistrer</button>
        </p>
    </form>
    
    <p class="submit">
        <a href="<?= admin_url() ?>admin.php?page=nat-quiz-themes" class="button button-primary">Retour à la liste des thèmes</a>
    </p>
    <hr>
<?php
} else {
?>

<?php } ?>

<!-- <pre><?php print_r($_POST) ?></pre> -->

<div id="themes-list">
    <!-- La liste des thèmes sera affichée ici -->
    <?php
    if ($mode == "list") {
        echo '<h1>Liste des thèmes Quiz</h1>'; ?>
         <p class="submit">
            <a href="<?= admin_url() ?>admin.php?page=nat-quiz-themes&mode=add" class="button button-primary">Ajouter un nouveau thème</a>
        </p>
       
        <form name="form" method="post" action="">
            <table class="wp-list-table widefat fixed striped">
                <tr><label><strong>Sélectionner le ou les thèmes à supprimer : </strong></label></tr><br />
            </table>


            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Descriptif</th>
                        <th>Image</th>
                        <th>Date de création</th>
                        <th>Active</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $natquiz = new natquiz;
                    foreach ($themes as $theme) {
                    ?>
                        <tr>
                            <td>
                                <div id="checkbox">
                                    <input type="checkbox" class="verif_ok" name="id_themes_check[]" value="<?php echo $theme->id_themes; ?>" id="id_<?php echo $theme->id_themes; ?>">
                                </div>
                            </td>
                            <td>
                                <?php echo $theme->nom; ?>
                            </br>
                            <a href ="<?= admin_url() ?>admin.php?page=nat-quiz-questions&mode=add&id_t=<?=$theme->id_themes?>" class="button button-primary">Ajouter une question</a>                            
                            </td>
                            <td>
                                <?php echo $theme->descriptif; ?>
                            </td>
                            <td>
                                <img src="/quiz/wp-content/plugins/nat-quiz/image/<?= $theme->image ?>" alt="négatif de photo" style="width:180px;height:120px">
                            </td>
                            <td>
                                <?php echo $theme->date_creation; ?>

                            </td>
                            <td>
                                <?php if ($theme->active == 0) { ?>
                                    <a class="button button-primary edit-theme" href="<?= admin_url() ?>admin.php?page=nat-quiz-themes&idt=<?php echo $theme->id_themes; ?>&mode=list&action=active">Activer</a>
                                <?php } else { ?>
                                    <a class="button button-secondary edit-theme" href="<?= admin_url() ?>admin.php?page=nat-quiz-themes&idt=<?php echo $theme->id_themes; ?>&mode=list&action=desactive">Désactiver</a>
                                <?php } ?>
                            </td>
                            <td>
                                <a class="button button-secondary edit-theme" href="<?= admin_url() ?>admin.php?page=nat-quiz-themes&id_theme=<?php echo $theme->id_themes; ?>&mode=edit">Modifier</a>

                                <a class="button button-secondary delete-theme" href="<?= admin_url() ?>admin.php?page=nat-quiz-themes&idd=<?php echo $theme->id_themes; ?>&action=delete&mode=list" onclick="return(confirm('Etes-vous sûr de vouloir supprimer ?'));">Supprimer</a>
                            </td>
                        </tr>

                    <?php } ?>
                </tbody>
            </table>
            <div id="delete_themes">
                <button type="submit" class="button button-secondary delete-themes" name="all_delete_id_themes" id="submit" disabled="true" onclick="return(confirm('Etes-vous sûr de vouloir supprimer ?'));">Supprimer les thèmes sélectionnés</button>
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
        // on définit la variable en faux
        var verif_coche = false;

        // quand on clique sur une case
        $(".verif_ok").click(function() {
            // boucle pour verifier les cases cochées
            $(".verif_ok").each(function(index) {
                // si verif_coche est egale à faux et qu'elle est cochée
                if (!verif_coche && $(this).is(':checked') == true) {
                    // alors elle est definit en vrai
                    verif_coche = true;
                }
                // si le nbre de cas cochée est egale a 0
                if ($('.verif_ok:checked').length == 0) {
                    // alors elle est definit en faux
                    verif_coche = false;
                }

            });
            // si verif_coche est vrai on active le bouton
            if (verif_coche) {
                $('button[name="all_delete_id_themes"]').attr('disabled', false);
            } else {
                // sinon on le desactive
                $('button[name="all_delete_id_themes"]').attr('disabled', true);
            }
        });
    });

</script>