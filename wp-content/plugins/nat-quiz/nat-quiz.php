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
}

// Enregistrement des fonctions d'installation et de désinstallation
register_activation_hook( __FILE__, 'nat_quiz_install' ); // fonction executée lors de l'installation du plugin
register_uninstall_hook( __FILE__, 'nat_quiz_uninstall' ); // fonction executée lors de la désinstallation du plugin
//register_deactivation_hook( __FILE__, 'nat_quiz_uninstall' ); //fonction executée lors de la desactivation du plugin



