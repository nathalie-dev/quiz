<?php

/**
 * Plugin Name: NAT Quiz
 * Plugin URI: https://example.com/
 * Description: Extension WordPress pour créer des quiz.
 * Version: 1.0.0
 * Author: Votre nom
 * Author URI: https://example.com/
 * License: GPL2
 */
//define( 'WP_USE_THEMES', false );
//require_once( ABSPATH . 'wp-admin/admin-ajax.php' );

 require_once plugin_dir_path(__FILE__) . 'includes/functions.php'; 
 require_once plugin_dir_path( __FILE__ ) . 'models_classe/class_quiz.php' ;


// Créer le menu Quiz et les sous-menus
function nat_quiz_menu()
{
    add_menu_page(
        'Quiz',            // Titre de la page
        'Quiz',            // Titre du menu
        'manage_options',  // Capacité requise pour accéder au menu
        'nat-quiz',        // Slug de la page
        'nat_quiz_dashboard' // Fonction à appeler pour afficher la page
    );
    add_submenu_page(
        'nat-quiz',           // Slug de la page parente
        'Tableau de bord',     // Titre de la page
        'Tableau de bord',     // Titre du menu
        'manage_options',      // Capacité requise pour accéder au menu
        'nat-quiz',            // Slug de la page
        'nat_quiz_dashboard'   // Fonction à appeler pour afficher la page
    );
    add_submenu_page(
        'nat-quiz',           // Slug de la page parente
        'Thèmes quiz',        // Titre de la page
        'Thèmes quiz',        // Titre du menu
        'manage_options',      // Capacité requise pour accéder au menu
        'nat-quiz-themes',     // Slug de la page
        'nat_quiz_themes'      // Fonction à appeler pour afficher la page
    );
    add_submenu_page(
        'nat-quiz',           // Slug de la page parente
        'Question quiz',      // Titre de la page
        'Question quiz',      // Titre du menu
        'manage_options',      // Capacité requise pour accéder au menu
        'nat-quiz-questions', // Slug de la page
        'nat_quiz_questions'  // Fonction à appeler pour afficher la page
    );
    add_submenu_page(
        'nat-quiz',           // Slug de la page parente
        'Réponses quiz',      // Titre de la page
        'Réponses quiz',      // Titre du menu
        'manage_options',      // Capacité requise pour accéder au menu
        'nat-quiz-reponses',  // Slug de la page
        'nat_quiz_reponses'   // Fonction à appeler pour afficher la page
    );
    add_submenu_page(
        'nat-quiz',           // Slug de la page parente
        'Stats',              // Titre de la page
        'Stats',              // Titre du menu
        'manage_options',      // Capacité requise pour accéder au menu
        'nat-quiz-stats',     // Slug de la page
        'nat_quiz_stats'      // Fonction à appeler pour afficher la page
    );
    add_submenu_page(
        'nat-quiz',           // Slug de la page parente
        'Configuration',      // Titre de la page
        'Configuration',      // Titre du menu
        'manage_options',      // Capacité requise pour accéder au menu
        'nat-quiz-config',    // Slug de la page
        'nat_quiz_config'     // Fonction à appeler pour afficher la page
    );
    
}

// Ajout des menus et sous-menus
add_action('admin_menu', 'nat_quiz_menu');

// Fonction pour afficher le tableau de bord
function nat_quiz_dashboard()
{
    include( plugin_dir_path( __FILE__ ) . 'templates/nat_quiz_dashboard.php' );
}

// Fonction pour afficher les thèmes quiz
function nat_quiz_themes()
{
    include( plugin_dir_path( __FILE__ ) . 'templates/nat_quiz_themes.php' );
}

// Fonction pour afficher les questions quiz
function nat_quiz_questions()
{
    include( plugin_dir_path( __FILE__ ) . 'templates/nat_quiz_questions.php' );
}

// Fonction pour afficher les réponses quiz
function nat_quiz_reponses()
{
    include( plugin_dir_path( __FILE__ ) . 'templates/nat_quiz_reponses.php' );
}

// Fonction pour afficher les stats
function nat_quiz_stats()
{
    include( plugin_dir_path( __FILE__ ) . 'templates/nat_quiz_stats.php' );
}

// Fonction pour afficher les utilisateurs
function nat_quiz_utilisateurs()
{
    include( plugin_dir_path( __FILE__ ) . 'templates/nat_quiz_utilisateurs.php' );
}

// Fonction pour afficher la configuration
function nat_quiz_config()
{
    global $wpdb,$_GET;
    include( plugin_dir_path( __FILE__ ) . 'templates/nat_quiz_config.php' );
}

// Fonction qui nettoie le nom des fichiers uplodés dans l'admin Worpress en effaçant la plus-part des caractères spéciaux
function wpc_sanitize_french_chars($filename) {
	
	/* Force le nom du fichier en UTF-8 (encodage Windows / OS X / Linux) */
	$filename = mb_convert_encoding($filename, "UTF-8");

	$char_not_clean = array('/À/','/Á/','/Â/','/Ã/','/Ä/','/Å/','/Ç/','/È/','/É/','/Ê/','/Ë/','/Ì/','/Í/','/Î/','/Ï/','/Ò/','/Ó/','/Ô/','/Õ/','/Ö/','/Ù/','/Ú/','/Û/','/Ü/','/Ý/','/à/','/á/','/â/','/ã/','/ä/','/å/','/ç/','/è/','/é/','/ê/','/ë/','/ì/','/í/','/î/','/ï/','/ð/','/ò/','/ó/','/ô/','/õ/','/ö/','/ù/','/ú/','/û/','/ü/','/ý/','/ÿ/', '/©/','/ /');
	$clean = array('a','a','a','a','a','a','c','e','e','e','e','i','i','i','i','o','o','o','o','o','u','u','u','u','y','a','a','a','a','a','a','c','e','e','e','e','i','i','i','i','o','o','o','o','o','o','u','u','u','u','y','y','copy', '_');

	$friendly_filename = preg_replace($char_not_clean, $clean, $filename);

	/* Après remplacement, nous détruisons les derniers résidus */
    //$friendly_filename = utf8_decode($friendly_filename); utf8_decode est obsolte à partir de php 8.2.0
	$friendly_filename = mb_convert_encoding($friendly_filename, 'UTF-8','ISO-8859-1' ); //friendly_filename = nom de fichier amical
	$friendly_filename = preg_replace('/\?/', '', $friendly_filename); //preg_replace = rechercher et remplacer par

	/* Pour mettre en minuscule*/
	$friendly_filename = strtolower($friendly_filename);

	return $friendly_filename;
}
add_filter('sanitize_file_name', 'wpc_sanitize_french_chars', 10);


// Fonction pour ajouter le css (front-end)
function css_public_natquiz() {
    // Ajouter un fichier CSS localisé par plugin_dir_url()
    wp_enqueue_style( 'main-css', plugin_dir_url( __FILE__ ) . 'natquiz-style.css' ); 
}
add_action( 'wp_enqueue_scripts', 'css_public_natquiz' );

// Fonction pour afficher un shortcode (c'est une balise interprétée automatiquement par WordPress qui permet d'afficher un contenu spécifique. C'est en quelque sorte un raccourci que WordPress reconnaîtra et affichera.)
function afficher_theme_quiz_shortcode($atts) {
    global $wpdb;
    // Récupérer l'ID du thème à partir de l'URL
    $theme_id = get_query_var('theme_id');
    if ($theme_id) {
        include( plugin_dir_path( __FILE__ ) . 'templates_public/nat_quiz_questions.php' );
    } else {
        include( plugin_dir_path( __FILE__ ) . 'templates_public/nat_quiz_themes.php' );
    }
   
}
add_shortcode('natquiz_themes', 'afficher_theme_quiz_shortcode');

//Fonction pour afficher un shortcode pour les questions

function afficher_question_quiz_shortcode($atts) {
    global $wpdb;
    include( plugin_dir_path( __FILE__ ) . 'templates_public/nat_quiz_questions.php' );
}
add_shortcode('natquiz_questions', 'afficher_question_quiz_shortcode');


//Fonction pour afficher un shortcode pour les reponses
function afficher_reponse_quiz_shortcode($atts) {
    global $wpdb;
    include( plugin_dir_path( __FILE__ ) . 'templates_public/nat_quiz_reponses.php' );
}
add_shortcode('natquiz_reponses', 'afficher_reponse_quiz_shortcode');

// Fonction pour ajouter la variable dynamique 'theme_id' à la structure d'URL
function liste_all_themes_rewrite_rules() {
    add_rewrite_rule('^themes/([^/]*)/?', 'index.php?pagename=themes&theme_id=$matches[1]', 'top');
    flush_rewrite_rules();
}
add_action('init', 'liste_all_themes_rewrite_rules');

// Fonction pour ajouter la variable 'theme_id' aux variables de requête
function liste_all_themes_query_vars($vars) {
    $vars[] = 'theme_id';
    return $vars;
}
add_filter('query_vars', 'liste_all_themes_query_vars');

// Fonction d'installation du plugin
function nat_quiz_install() {
    // Récupération du contenu du fichier SQL
    $sql_file = plugin_dir_path( __FILE__ ) . 'install/nat_quiz_mysql_install.sql';
    $sql = file_get_contents( $sql_file );
    
    // Remplacement du préfixe de table par le préfixe de la base de données WordPress
    global $wpdb;
    $table_prefix = $wpdb->prefix;
    $sql = str_replace( 'wp_nat_quiz', $table_prefix . 'nat_quiz', $sql );
    
    // Séparation des instructions SQL en tableau
    $queries = explode( ';', $sql );
    
    // Exécution de chaque instruction SQL
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    foreach ( $queries as $query ) {
        if ( ! empty( $query ) ) {
            dbDelta( $query );
        }
    }
}


// Fonction de désinstallation du plugin
function nat_quiz_uninstall() {
    // Récupération du contenu du fichier SQL
    $sql_file = plugin_dir_path( __FILE__ ) . 'install/nat_quiz_mysql_uninstall.sql';
    $sql = file_get_contents( $sql_file );
    
    // Remplacement du préfixe de table par le préfixe de la base de données WordPress
    global $wpdb;
    $table_prefix = $wpdb->prefix;
    $sql = str_replace( 'wp_nat_quiz', $table_prefix . 'nat_quiz', $sql );
    
    // Séparation des instructions SQL en tableau
    $queries = explode( ';', $sql );
    
    // Exécution de chaque instruction SQL
    foreach ( $queries as $query ) {
        if ( ! empty( $query ) ) {
            $wpdb->query( $query );
        }
    }
    @rmdir('../wp-content/uploads/natquizfiles');
}


/*
* repertoire upload
*/

// verification si rep existe
if(!is_dir('../wp-content/uploads/natquizfiles')) {
    init_dir_natquizfiles();
}
// création du repertoire
function init_dir_natquizfiles() {
    if (!@mkdir('../wp-content/uploads/natquizfiles', 0700, true)) {
       // header('Location: '.admin_url().'/admin.php?page=nat-quiz&mode=error&type=dir');
       // exit();
    } 
}
// suppression du repertoire
if (!is_dir('../wp-content/uploads/natquizfiles')) {
    mkdir('../wp-content/uploads/natquizfiles');
}




// Enregistrement des fonctions d'installation et de désinstallation
register_activation_hook( __FILE__, 'nat_quiz_install' ); // fonction executée lors de l'installation du plugin
register_uninstall_hook( __FILE__, 'nat_quiz_uninstall' ); // fonction executée lors de la désinstallation du plugin
//register_deactivation_hook( __FILE__, 'nat_quiz_uninstall' ); //fonction executée lors de la desactivation du plugin



