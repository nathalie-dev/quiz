<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clés secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur
 * {@link https://fr.wordpress.org/support/article/editing-wp-config-php/ Modifier
 * wp-config.php}. C’est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define( 'DB_NAME', 'quiz' );

/** Utilisateur de la base de données MySQL. */
define( 'DB_USER', 'root' );

/** Mot de passe de la base de données MySQL. */
define( 'DB_PASSWORD', '' );

/** Adresse de l’hébergement MySQL. */
define( 'DB_HOST', 'localhost' );

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Type de collation de la base de données.
  * N’y touchez que si vous savez ce que vous faites.
  */
define('DB_COLLATE', '');

/**#@+
 * Clés uniques d’authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '0xKto4vl96l:ESisa8/[zSCQ{&Z`l-S|78rr=s$%q*R?YkI;T=z} bEKpvfn9E.Q' );
define( 'SECURE_AUTH_KEY',  '!(&5z+Vh<r{4<LQOF-wEpH0 #`!zAH8ASY265v=H!U0tSmk^n:zs6h)tjL)%rO<>' );
define( 'LOGGED_IN_KEY',    'z^W{.pH:M,l/BYI??x+CXL/U_W}RL4*^`<3OevW6A2L:RVf)?R8$Y c@Vz773h/~' );
define( 'NONCE_KEY',        '3L(x<.*%fr!v6p0<FB+lQz5kVTjzMOSkDjOx$3H]^_M?Dg#i9}udwO|7s-YQn]_C' );
define( 'AUTH_SALT',        'i!$>Gxo,y)uFQpnH)o%K?$7`IL4Y^CZ7]XFc?xO:OpT]:`yg}5v<1?7ay* vi[B ' );
define( 'SECURE_AUTH_SALT', '8FM`ehiqae,f71n7zM8 +C qIS>U6T0hr~&2d A>cHX :8NyCm*T]9:[pNX{5<uJ' );
define( 'LOGGED_IN_SALT',   'QSD.k}Be>&N[5a2=/ WyftuK6E|gt(@=+3B`y~{Y2g8G[>P`cWQqlnVWhf[I!dH.' );
define( 'NONCE_SALT',       '^VH2zFf?)G@7&W*m7Po{YS_[.y?I,(QY/_3HX^j1oNMaJ(wZ?;M?HBE9cR8k#ZHm' );
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix = 'wp_';

/**
 * Pour les développeurs et développeuses : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortemment recommandé que les développeurs d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d’information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur la documentation.
 *
 * @link https://fr.wordpress.org/support/article/debugging-in-wordpress/
 */
define('WP_DEBUG', false);

/* C’est tout, ne touchez pas à ce qui suit ! Bonne publication. */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');
