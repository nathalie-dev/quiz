<?php

// Fonction pour ajouter ou éditer un thème
function nat_quiz_save_theme()
{
    global $wpdb;
    $final['error']  = false;
    $final['message'] = null;

    
    $table_name = $wpdb->prefix . 'nat_quiz_themes';

    // isset verifie qu'une variable est définie donc qu'elle existe bien
    $id_themes = isset($_POST['id_themes']) ? $_POST['id_themes'] : '';
    $nom = isset($_POST['nom']) ? $_POST['nom'] : '';
    $descriptif = isset ($_POST['descriptif']) ? $_POST['descriptif'] : '';
    $image = isset($_FILES['image']) ? $_FILES['image'] : '';
   
    //
    // si id_themes non défini mode création 
    //
 
    // empty verifie que la variable n'est pas vide // qu'elle n'est pas null
    if (empty($id_themes)) {

        //pour mettre en ligne et copier une photo via un formulaire
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            if ($_FILES['image']['size'] <= 3000000) { //le poids de l'image doit etre inférieur ou égal a 3 mégaoctet
                $informationsImage = pathinfo($_FILES['image']['name']);
                $extensionImage = $informationsImage ['extension'];
                $extensionArray = array ('png','gif','jpg','webp','jpeg');
                $new_name_image = wpc_sanitize_french_chars(strtolower($nom)).'.'.$extensionImage;
            }
        }

        // si les champs sont vide  on affiche un message d'erreur
        if (empty($nom) || empty($descriptif) || empty($new_name_image)) {
            $final['message'] = 'Veuillez remplir tous les champs.';
            $final['error']  = true;
        }

        // si le format d'image n'est pas correct on affiche un message d'erreur
        if (!in_array($extensionImage, $extensionArray)) {
            $final['message'] = 'Format d\'image non pris en charge.';
            $final['error']  = true;
        }

        // données qui sont injectées dans la bdd
        $data = array(
            'nom' => $nom,
            'descriptif' => $descriptif,
            'image' => $new_name_image
        );

    } else {
        //
        // sinon mode edition
        //

        //pour mettre en ligne et copier une photo via un formulaire
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            if ($_FILES['image']['size'] <= 3000000) { //le poids de l'image doit etre inférieur ou égal a 3 mégaoctet
                $informationsImage = pathinfo($_FILES['image']['name']);
                $extensionImage = $informationsImage ['extension'];
                $extensionArray = array ('png','gif','jpg','webp','jpeg');
                $new_name_image = wpc_sanitize_french_chars(strtolower($nom)).'.'.$extensionImage;
            }
        }

        // si les champs sont vide  on affiche un message d'erreur
        if (empty($nom) || empty($descriptif)) {
            $final['message'] = 'Veuillez remplir tous les champs.';
            $final['error']  = true;
        }

        // si le format d'image n'est pas correct on affiche un message d'erreur
        if ( $_FILES['image']['error']==0 && !in_array($extensionImage, $extensionArray)) {
            $final['message'] = 'Format d\'image non pris en charge.';
            $final['error']  = true;
        }

        // données qui sont injectées dans la bdd
        if(isset($new_name_image)) {
            $data = array(
                'nom' => $nom,
                'descriptif' => $descriptif,
                'image' => $new_name_image
            );
        } else {
            $data = array(
                'nom' => $nom,
                'descriptif' => $descriptif
            );
        }



    }
    // Ajouter ou mettre à jour un thème
    $table_name = $wpdb->prefix . 'nat_quiz_themes';
    if (empty($id_themes) && !$final['error']) {
        if ($wpdb->insert($table_name, $data)) {
            $final['message'] = 'Le thème est bien ajouté.';
            if (in_array($extensionImage, $extensionArray)) {
                move_uploaded_file($_FILES['image']['tmp_name'], '../wp-content/uploads/natquizfiles/'.basename($new_name_image)); 
                $final['message'] .= '<br> L\'image a bien été ajoutée.';
            }
    /*$_FILES['image']['tmp_name'] Lorsqu'on appuie sur le bouton Envoyer, la photo est copiée sur le serveur distant dans un dossier temporaire nommé tmp 
    $_FILES['image']['name'] Cette variable contient le nom d'origine du fichier mis en ligne, par exemple fleur.jpg
    copy($_FILES['image']['tmp_name'], $_FILES['image']['name']); La fonction PHP copy(source, destination) va dupliquer la photo mise en ligne dans le dossier tmp du serveur dans le dossier où se trouve la page. */
            
        } else {
            $final['message'] = 'Le thème n\'est pas ajouté.';
            $final['error'] = true;
        }
    
    } else {
        if (is_numeric($id_themes) && !$final['error']) {
            if ($wpdb->update($table_name, $data, array('id_themes' => $id_themes))) {
                $final['message'] = 'Le thème a bien été édité.';
            
            //pour mettre en ligne et copier une photo via un formulaire
            if ($_FILES['image']['error']==0 && in_array($extensionImage, $extensionArray)) {
                move_uploaded_file($_FILES['image']['tmp_name'], '../wp-content/uploads/natquizfiles/'.basename($new_name_image)); 
                $final['message'] .= '<br> L\'image a bien été éditée.';
            }

                } else {
                    $final['message'] = 'Le thème n\'est pas édité.';
                    $final['error']  = true;
                }
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

 // Fonction pour supprimer une image de thème
 function delete_theme_image($id) {
    global $wpdb;
    $the_theme = nat_quiz_get_themes($id);
    if($the_theme->id_themes>0) {
        $file = '../wp-content/uploads/natquizfiles/'.$the_theme->image.''; 
        if (file_exists($file)) {
            if(unlink($file)) {

            // update sql case image
            $data = array(
                'image' => ''
            );
            
            $table_name = $wpdb->prefix . 'nat_quiz_themes';
            if ($wpdb->update($table_name, $data, array('id_themes' => $id))) {
                $final['message'] = 'Le thème a bien été édité.';
            }

                $final['message'] .= '<br>Suppression du fichier reussi';
                $final['error']  = false;    
            }
        } else {
                $final['message'] = 'Echec de la suppression du fichier';
                $final['error']  = true;   
        }  
    }
    return $final;
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

    // récuperation de la variable delete_image dans l'url
    if (isset(($_GET['delete_image']))) {
        $delete_image = $_GET['delete_image'];
    }

    if (!empty($_POST['nat-quiz-add-edit'])) { 
        $response = nat_quiz_save_theme();
    }

    // pour supprimer une image
    if ($mode == 'edit' && isset($id_theme) && $id_theme > 0 && isset($delete_image) && $delete_image != '') {
        $response = delete_theme_image($id_theme);
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

    <form action="<?= admin_url() ?>admin.php?page=nat-quiz-themes"  class="wp-admin" method="post" enctype="multipart/form-data">
        <input id="id_themes" type="hidden" name="id_themes" value="<?php echo $themes->id_themes; ?>" />


        <table>
            <tr>
                <td><label for="nom">Nom du thème : </label></td>
                <td><input id="nom" type="text" name="nom" required="true" value="<?=stripslashes($themes->nom)?>" /></td>    
            </tr>
            <tr>
                <td><label for="descriptif">Descriptif : </label></td>
                <td><textarea id="descriptif" name="descriptif" rows="8" required="true"><?php echo stripslashes($themes->descriptif); ?></textarea></td>
            </tr>
            <tr>
                <?php if($themes->image!='') { ?>
                    <td><label for="image">Image actuelle</label></td>
                    <td><img src="../wp-content/uploads/natquizfiles/<?=$themes->image?>" style="width:200px;"/>
                    <br><a href="<?=admin_url()?>admin.php?page=nat-quiz-themes&id_theme=<?= $themes->id_themes?>&mode=edit&delete_image=ok" class="button button-primary">Supprimer l'image</a>
                <?php } else { ?>
                    <td colspan="2">Aucune image pour ce thème</div>
                <?php } ?>
            </tr>
            <tr>
                <td><label for="image">Choisir une photo correspondant au thème:</label></td>
                <td><input type="file" id="imagetheme" name="image"></td>
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

    <form action="<?= admin_url() ?>admin.php?page=nat-quiz-themes" class="wp-admin" method="post" enctype="multipart/form-data">
     <!-- L'attribut et la valeur enctype='multipart/form-data' sont ici indispensables car ils permettent au formulaire d'ajouter des données binaires, c'est à dire autre chose que du texte, comme des images, des musiques, ou autres fichiers informatiques -->
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
                <td><textarea name="descriptif" rows="5" required="true"></textarea></td>
            </tr>
            <tr>
                <td><label for="image">Choisir une photo correspondant au thème:</label></td>
                <td><input type="file" id="imagetheme" name="image" required="true"></td>
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

                <button type="button" class="button button-secondary select_all_themes" name="all_coche_id_themes" id="all_coche" >Cocher tous les thèmes</button>
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
                                <?php echo stripslashes($theme->nom); ?>
                            </br>
                            <a href ="<?= admin_url() ?>admin.php?page=nat-quiz-questions&mode=add&id_t=<?=$theme->id_themes?>" class="button button-primary">Ajouter une question</a>                            
                            </td>
                            <td>
                                <?php echo stripslashes($theme->descriptif); ?>
                            </td>
                            <td>
                                <img src=../wp-content/uploads/natquizfiles/<?php echo $theme->image; ?> alt="Photo correspondant au thème" width=100% height=10%> 
 
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

   <!--<pre><?php print_r($_POST) ?></pre> 
   <pre><?php print_r($_FILES) ?></pre> -->
   
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
                // si le nbre de case cochée est egale a 0
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

//script pour cocher toutes les cases en une seule fois 

    // pour verifier les cases cochées
    
    $("#all_coche").click(function() {
        $(':checkbox').each(function(index) { 
            this.checked = true;
        });
    }); 

});

</script>
