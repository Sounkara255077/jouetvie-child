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

/**
 * Charge le composant carte (eco-card) sur le site.
 */
function jouetvie_enqueue_composants() {
	wp_enqueue_style(
		'jouetvie-card',
		get_stylesheet_directory_uri() . '/assets/css/components/card.css',
		array(),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'jouetvie_enqueue_composants' );

/**
 * Charge aussi le composant carte dans l'éditeur Gutenberg.
 */
function jouetvie_editor_styles() {
	add_editor_style( 'assets/css/components/card.css' );
}
add_action( 'after_setup_theme', 'jouetvie_editor_styles' );
