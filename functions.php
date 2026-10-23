<?php
/**
 * Jouet&Vie – fonctions du thème enfant.
 *
 * @package jouetvie-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Sécurité : empêche l'accès direct au fichier.
}

/**
 * Charge la feuille de style du thème enfant.
 */
function jouetvie_enqueue_styles() {
	wp_enqueue_style(
		'jouetvie-child-style',
		get_stylesheet_uri(),
		array(),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'jouetvie_enqueue_styles' );
