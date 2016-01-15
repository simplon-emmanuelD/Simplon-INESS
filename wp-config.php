<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clefs secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur 
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C'est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d'installation. Vous n'avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', 'Simplon.INESS');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'root');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', 'emm=phh14');

/** Adresse de l'hébergement MySQL. */
define('DB_HOST', 'localhost');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8mb4');

/** Type de collation de la base de données. 
  * N'y touchez que si vous savez ce que vous faites. 
  */
define('DB_COLLATE', '');

/**#@+
 * Clefs uniques d'authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant 
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n'importe quel moment, afin d'invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '?5!%.qxX1WBRSC}o[^i-ACwM& gO4)5^GZ*V+{k~sSDLxhhyBIG^]U=?Vj)M;Icb');
define('SECURE_AUTH_KEY',  'to)xJIgtt2I/dLj)jfQX-*A5pzrcN(`KxRG+[~|E*>.+B9b?n]^P[B/W*-^tbPV<');
define('LOGGED_IN_KEY',    'b~icYP*~E`8t+@<M!j?m|Bcn~%u~Rh*8481jY{0z!XF1v~vOaxrE@?d ]?gh2LA5');
define('NONCE_KEY',        '^1=}&VBgmkoj%9p#d;1#,M=%)I2vVC?Ugw`pa,;?<C-z#IyjPG~QD@Jea!W7Ef1r');
define('AUTH_SALT',        'sVfaEFkERa|Eh/s(l|MY}<@g(]34i+*R(|Oo>o }};}>ng0~?QB4~>jF?}oJ-~rW');
define('SECURE_AUTH_SALT', '8E-Ic.dEIK{|_TsoVbuG8GJo:/y/Es#0Ct:zUP7+D3]9kJ&Ok*H.7IIV>h &++cZ');
define('LOGGED_IN_SALT',   'UMi/`puMo0|G6!1MA;30jXvh hHlg>|$$f<wL`6kC)?6%gYG+!19E-iHP5rAAao3');
define('NONCE_SALT',       'd-4alKNJ1Zg%jI4bFS/g;:l176}k+G;]|~f!xw|qDuOpA-|#2B a~+Ak=zgF1 )|');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique. 
 * N'utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés!
 */
$table_prefix  = 'wp_';

/** 
 * Pour les développeurs : le mode deboguage de WordPress.
 * 
 * En passant la valeur suivante à "true", vous activez l'affichage des
 * notifications d'erreurs pendant votre essais.
 * Il est fortemment recommandé que les développeurs d'extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de 
 * développement.
 */ 
define('WP_DEBUG', false); 

/* C'est tout, ne touchez pas à ce qui suit ! Bon blogging ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');

define('FS_METHOD', 'direct');
