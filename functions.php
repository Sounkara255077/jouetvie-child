<?php
/**
 * Eco Starter Pack — functions.php de référence
 *
 * Thème enfant de Twenty Twenty-Five · DFEF · EMU2 · 2026-2027
 *
 * ---------------------------------------------------------------------------
 * COMMENT UTILISER CE FICHIER ?
 * ---------------------------------------------------------------------------
 * 1. Dans le cadre de nos exercices, ne modifiez QUE le tableau des interrupteurs ci-dessous (true / false).
 *    Chaque interrupteur correspond à une séance du cours.
 *    En S8, vous pourrez aussi adapter la liste des thèmes (eco_themes).
 * 2. Le reste du fichier se lit, il ne se modifie pas à moins que cela soit réellement justifié. 
 *    Chaque bloc est numéroté et rattaché à sa séance.
 * 3. Page blanche ou « erreur critique » après une modification ?
 *    - Annulez votre dernière modification (Ctrl+Z, ou git checkout functions.php si déjà enregistré).
 *    - Point d'attention pour les erreurs classiques : point-virgule, virgule entre deux lignes du tableau, guillemets.
 *    - En cas d'urgence et d'erreur critique : renommez le dossier du thème en SFTP, WordPress repasse
 *      sur un votre thème parent et le site redevient accessible.
 *    - Travaillez toujours en local avant de toucher au serveur.
 *
 * @package eco-starter-pack
 */

// Sécurité : ce fichier ne s'exécute que dans le backend de WordPress.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/* ===========================================================================
 * 0. LES INTERRUPTEURS — la seule partie à modifier
 * ===========================================================================
 * true  = activé
 * false = désactivé
 * <!> Gardez la virgule à la fin de chaque ligne. */

function eco_reglages() {
	return array(

		// S4 · Composition — Propose « Carte éco » et « Carte mise en avant »
		// dans Groupe › Styles. Le client choisit en un clic (niveau 4).
		'styles_de_blocs'             => true,

		// S5 · Production — Charge UNIQUEMENT la grille Bootstrap (pas le reste).
		// À justifier dans la fiche technique avant de l'activer.
		'bootstrap_grille'            => false,

		// S7 · Dynamique — Ajoute jQuery comme dépendance de main.js
		// (seulement pour lire ou maintenir du code jQuery existant).
		'jquery'                      => false,

		// S8 · Connexion — Donne au JavaScript l'objet ECO :
		// ECO.themeUrl, ECO.restUrl, ECO.nonce. Indispensable pour fetch().
		'donnees_js'                  => false,

		// S8 · Connexion — Crée la route d'exercice POST /wp-json/eco/v1/defi
		// (apprentissage technique, contrat en section 6 du README).
		'route_ia'                    => false,

		// S8 · Connexion — Exercice : la route appelle l'IA de WordPress 7.0+
		// (Réglages › Connecteurs). Si false, ou si aucune IA n'est configurée,
		// la route renvoie un défi « plan B » calculé sans IA.
		'ia_wordpress'                => false,

		// Optionnel — Masque les compositions de Twenty Twenty-Five
		// dans l'éditeur, pour ne garder que celles de l'équipe.
		'masquer_compositions_parent' => false,

		// Optionnel — Masque les variations de style de Twenty Twenty-Five
		// (Evening, Noon…, leurs palettes et associations de polices) dans
		// Apparence › Éditeur › Styles. Seules celles du thème enfant restent.
		'masquer_styles_parent'       => true,

		// PROJET · Intégration IA de l'équipe (cours Déploiement IA)
		// Une fonctionnalité : 'coach', 'chatbot', 'assistant' ou 'camera' (bonus).
		// Deux au maximum, la seconde après validation du cours IA : array( 'assistant', 'coach' ).
		// '' (vide) = aucune. Crée la route POST /wp-json/eco/v1/ia (bloc 8).
		// Nécessite aussi donnees_js => true pour disposer de l'objet ECO.
		'integration_ia'              => '',

		// PROJET · Démo devant le jury — Bouton « Avec l'IA / Sans IA »
		// Ajoute ECO.bascule = true pour les personnes connectées au site
		// (l'équipe, les enseignant·es, le jury sur l'ordinateur de l'équipe).
		// La page affiche alors le bouton et envoie "mode": "simule" ou "ia".
		'bouton_bascule_ia'           => false,

	);
}

/**
 * S8 · Connexion — Les thèmes de défi acceptés par la route /eco/v1/defi.
 * Clé = valeur envoyée par le formulaire (sans accent ni espace),
 * valeur = nom affiché. Adaptez cette liste aux thèmes de votre projet :
 * c'est la seule autre partie de ce fichier que vous pouvez modifier.
 *
 * @return array<string,string>
 */
function eco_themes() {
	return array(
		'mobilite'     => 'Mobilité',
		'alimentation' => 'Alimentation',
		'energie'      => 'Énergie',
	);
}

/**
 * S8 · Connexion — Les niveaux d'un défi, identiques à data/defis.json.
 * Ne pas modifier : le formulaire, le JSON et la route utilisent ces valeurs.
 *
 * @return string[]
 */
function eco_niveaux() {
	return array( 'facile', 'moyen', 'difficile' );
}

/**
 * Lit un interrupteur. Exemple : eco_actif( 'jquery' ).
 *
 * @param string $cle Nom de l'interrupteur.
 * @return bool
 */
function eco_actif( $cle ) {
	$reglages = eco_reglages();
	return ! empty( $reglages[ $cle ] );
}


/* ===========================================================================
 * Outil : numéro de version d'un fichier (pour vider le cache du navigateur)
 * ===========================================================================
 * Au lieu d'incrémenter « Version » à la main, on utilise la date de dernière modification du fichier : 
 * dès que vous enregistrez, le navigateur recharge la nouvelle version. */

function eco_version( $chemin_relatif ) {
	$chemin = get_stylesheet_directory() . '/' . ltrim( $chemin_relatif, '/' );
	return file_exists( $chemin ) ? (string) filemtime( $chemin ) : wp_get_theme()->get( 'Version' );
}


/* ===========================================================================
 * 1. S3 · Structure — Charger le CSS du thème enfant
 * ===========================================================================
 * Twenty Twenty-Five charge déjà son propre style.css. 
 * Ce bloc ajoute le style.css du thème enfant, puis chaque composant de assets/css/components/.
 * Pour ajouter un composant : créez le fichier .css dans ce dossier, c'est tout. */

function eco_charger_styles() {
	$dependances = array();

	// S5 · Bloc 3 : la grille Bootstrap passe AVANT nos styles.
	if ( eco_actif( 'bootstrap_grille' ) ) {
		wp_enqueue_style(
			'bootstrap-grille',
			'https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap-grid.min.css',
			array(),
			'5.3.8'
		);
		$dependances[] = 'bootstrap-grille';
	}

	wp_enqueue_style(
		'eco-style',
		get_stylesheet_uri(),
		$dependances,
		eco_version( 'style.css' )
	);

	foreach ( eco_fichiers_composants() as $fichier ) {
		$nom = basename( $fichier, '.css' );
		wp_enqueue_style(
			'eco-' . $nom,
			get_stylesheet_directory_uri() . '/assets/css/components/' . $nom . '.css',
			array( 'eco-style' ),
			eco_version( 'assets/css/components/' . $nom . '.css' )
		);
	}
}
add_action( 'wp_enqueue_scripts', 'eco_charger_styles' );

/*
 * Liste les fichiers CSS du dossier assets/css/components/.
 *
 * @return string[] Chemins complets, triés par ordre alphabétique.
 */
function eco_fichiers_composants() {
	$fichiers = glob( get_stylesheet_directory() . '/assets/css/components/*.css' );
	if ( ! $fichiers ) {
		return array();
	}
	sort( $fichiers );
	return $fichiers;
}


/* ===========================================================================
 * 2. S4 · Composition — Les mêmes styles dans l'éditeur Gutenberg
 * ===========================================================================
 * Sans ce bloc, une carte .eco-card est stylée sur le site mais pas dans
 * l'éditeur : le client ne voit pas ce qu'il compose. */

function eco_styles_editeur() {
	$fichiers = array( 'style.css' );
	foreach ( eco_fichiers_composants() as $fichier ) {
		$fichiers[] = 'assets/css/components/' . basename( $fichier );
	}
	add_editor_style( $fichiers );
}
add_action( 'after_setup_theme', 'eco_styles_editeur' );


/* ===========================================================================
 * 3. S4 · Composition — Styles de bloc « Carte éco » (niveau 4)
 * ===========================================================================
 * Ajoute deux choix dans Groupe › Styles. 
 * WordPress pose alors la classe is-style-eco-card ou is-style-eco-card-featured, 
 * stylée dans assets/css/components/card.css. */

function eco_styles_de_blocs() {
	if ( ! eco_actif( 'styles_de_blocs' ) ) {
		return;
	}
	register_block_style(
		'core/group',
		array(
			'name'  => 'eco-card',
			'label' => 'Carte éco',
		)
	);
	register_block_style(
		'core/group',
		array(
			'name'  => 'eco-card-featured',
			'label' => 'Carte mise en avant',
		)
	);
}
add_action( 'init', 'eco_styles_de_blocs' );


/* ===========================================================================
 * 4. S3 → S6 · Charger main.js (en defer, en bas de page)
 * ===========================================================================
 * defer : le script attend que le HTML soit lu. 
 * Sans lui, querySelector renverrait null car l'élément n'existerait pas encore (S6).
 *
 * C'est le code de la fonction eco_enqueue_scripts() du chapitre 05 - Interactions
 * (slide 11), déjà écrit ici sous le nom eco_charger_scripts() :
 * main.js est donc chargé dès l'activation du thème. Ne recopiez pas la
 * fonction de la slide dans ce fichier : lisez-la ici, puis vérifiez
 * dans F12 › Réseau que main.js répond en 200 (slide 12).
 */

function eco_charger_scripts() {
	$dependances = eco_actif( 'jquery' ) ? array( 'jquery' ) : array(); // S7

	wp_enqueue_script(
		'eco-main',
		get_stylesheet_directory_uri() . '/assets/js/main.js',
		$dependances,
		eco_version( 'assets/js/main.js' ),
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);

/* -----------------------------------------------------------------------
 * 5. S8 · Connexion — L'objet ECO, écrit par PHP juste avant main.js
 * -----------------------------------------------------------------------
 * PHP connaît les vraies adresses du site : le même code JS fonctionne
 * en local, dans un sous-dossier et en ligne.
 * JAMAIS de clé d'API ici : tout ce que contient ECO est lisible par
 * n'importe quel visiteur (F12 › Sources). */

	if ( eco_actif( 'donnees_js' ) ) {
		$eco = array(
			'themeUrl' => get_stylesheet_directory_uri(),
			'restUrl'  => esc_url_raw( rest_url() ),
			'nonce'    => wp_create_nonce( 'wp_rest' ),
		);
		// PROJET · Le bouton « Avec l'IA / Sans IA » n'est proposé qu'aux personnes connectées.
		if ( eco_actif( 'bouton_bascule_ia' ) && eco_integrations() && is_user_logged_in() ) {
			$eco['bascule'] = true;
		}
		wp_add_inline_script( 'eco-main', 'const ECO = ' . wp_json_encode( $eco ) . ';', 'before' );
	}
}
add_action( 'wp_enqueue_scripts', 'eco_charger_scripts' );


/* ===========================================================================
 * 6. S8 · Connexion — La route REST de l'exercice : POST /wp-json/eco/v1/defi
 * ===========================================================================
 * Contrat JSON (07 · Connexion, slide 14 · à recopier dans le README) :
 *   Requête  { "theme": "mobilite", "duree": 7, "niveau": "facile" }
 *   Réponse  { "titre": "…", "description": "…", "theme": "mobilite",
 *              "duree": 7, "niveau": "facile", "source": "ia|plan-b" }
 *   Erreurs  403 nonce absent ou invalide · 429 trop d'appels · 400 champ invalide
 *
 * La réponse a la même forme qu'un défi de data/defis.json : elle s'affiche
 * donc avec la même fonction carteDefi() (06 · Dynamique, slide 11).
 *
 * Côté JavaScript (slide 15) :
 *   fetch( ECO.restUrl + "eco/v1/defi", {
 *     method: "POST",
 *     headers: { "Content-Type": "application/json", "X-WP-Nonce": ECO.nonce },
 *     body: JSON.stringify( { theme, duree, niveau } ),
 *     signal: AbortSignal.timeout( 8000 )
 *   } );
 */

function eco_route_defi() {
	if ( ! eco_actif( 'route_ia' ) ) {
		return;
	}
	register_rest_route(
		'eco/v1',
		'/defi',
		array(
			'methods'             => 'POST',
			'callback'            => 'eco_repondre_defi',
			'permission_callback' => 'eco_verifier_nonce',
			// Chaque champ est décrit par un schéma : WordPress refuse (erreur 400)
			// tout ce qui sort du contrat, puis nettoie la valeur avant usage.
			'args'                => array(
				'theme'  => array(
					'required'          => true,
					'type'              => 'string',
					'enum'              => array_keys( eco_themes() ),
					'validate_callback' => 'rest_validate_request_arg',
					'sanitize_callback' => 'rest_sanitize_request_arg',
				),
				'duree'  => array(
					'type'              => 'integer',
					'default'           => 7,
					'minimum'           => 1,
					'maximum'           => 30,
					'validate_callback' => 'rest_validate_request_arg',
					'sanitize_callback' => 'rest_sanitize_request_arg',
				),
				'niveau' => array(
					'type'              => 'string',
					'default'           => 'facile',
					'enum'              => eco_niveaux(),
					'validate_callback' => 'rest_validate_request_arg',
					'sanitize_callback' => 'rest_sanitize_request_arg',
				),
			),
		)
	);
}
add_action( 'rest_api_init', 'eco_route_defi' );

/*
 * Le nonce gêne les appels venant d'un autre site. Attention : pour un
 * visiteur anonyme, il est lisible dans la page. Ce n'est pas une
 * protection d'accès, d'où la limite d'appels dans eco_repondre_defi().
 *
 * @param WP_REST_Request $requete La requête reçue.
 * @return bool|WP_Error */

function eco_verifier_nonce( $requete ) {
	$nonce = $requete->get_header( 'X-WP-Nonce' );
	if ( $nonce && wp_verify_nonce( $nonce, 'wp_rest' ) ) {
		return true;
	}
	return new WP_Error( 'eco_nonce', 'Jeton de sécurité absent ou expiré : rechargez la page.', array( 'status' => 403 ) );
}

/*
 * Répond à la requête selon le contrat JSON.
 *
 * Sobriété (projet éco-challenge) :
 * - 20 appels maximum par visiteur et par tranche de 10 minutes ;
 * - une même demande est servie depuis le cache pendant 24 h.
 *
 * @param WP_REST_Request $requete La requête reçue.
 * @return WP_REST_Response|WP_Error  */

function eco_repondre_defi( $requete ) {
	// Limite d'appels par visiteur (l'IA a un coût, financier et énergétique).
	$ip_hash   = md5( isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'inconnu' );
	$cle_quota = 'eco_quota_' . $ip_hash;
	$appels    = (int) get_transient( $cle_quota );
	if ( $appels >= (int) apply_filters( 'eco_quota_appels', 20 ) ) {
		return new WP_Error( 'eco_quota', 'Trop de demandes. Réessayez dans quelques minutes.', array( 'status' => 429 ) );
	}
	set_transient( $cle_quota, $appels + 1, 10 * MINUTE_IN_SECONDS );

	$theme  = $requete['theme'];
	$duree  = (int) $requete['duree'];
	$niveau = $requete['niveau'];

	// Même demande = même réponse, sans rappeler l'IA.
	$cle_cache = 'eco_defi_' . md5( $theme . '|' . $duree . '|' . $niveau );
	$en_cache  = get_transient( $cle_cache );
	if ( false !== $en_cache ) {
		return rest_ensure_response( $en_cache );
	}

	$texte = eco_generer_defi( $theme, $duree, $niveau );

	// L'IA n'écrit que le titre et la description : le reste est recopié
	// de la requête validée, l'IA ne peut donc pas sortir du contrat.
	$defi = array(
		'titre'       => $texte['titre'],
		'description' => $texte['description'],
		'theme'       => $theme,
		'duree'       => $duree,
		'niveau'      => $niveau,
		'source'      => $texte['source'],
	);

	// On ne garde en cache que les réponses de l'IA : le plan B ne coûte rien.
	if ( 'ia' === $defi['source'] ) {
		set_transient( $cle_cache, $defi, DAY_IN_SECONDS );
	}

	return rest_ensure_response( $defi );
}

/*
 * Écrit le titre et la description du défi. Ordre de priorité :
 * 1. le filtre « eco_generer_defi », pour tester un autre générateur dans
 *    l'exercice (le cours IA, lui, se branche sur eco_ia_reponse, bloc 8) ;
 * 2. l'IA de WordPress 7.0+ si l'interrupteur ia_wordpress est activé
 *    et qu'un fournisseur est configuré dans Réglages › Connecteurs ;
 * 3. sinon, le plan B : un défi écrit sans IA.
 *
 * @param string $theme  Un thème de eco_themes() (mobilite, alimentation…).
 * @param int    $duree  Durée du défi, de 1 à 30 jours.
 * @param string $niveau Un niveau de eco_niveaux() (facile, moyen, difficile).
 * @return array { titre, description, source } */

function eco_generer_defi( $theme, $duree, $niveau ) {
	// 1. Point d'accroche de l'exercice : add_filter( 'eco_generer_defi', … ).
	//    La fonction reçoit $theme, $duree, $niveau et renvoie
	//    array( 'titre' => …, 'description' => … ), ou null en cas d'échec.
	$personnalise = apply_filters( 'eco_generer_defi', null, $theme, $duree, $niveau );
	if ( is_array( $personnalise ) && ! empty( $personnalise['titre'] ) && ! empty( $personnalise['description'] ) ) {
		return array(
			'titre'       => sanitize_text_field( $personnalise['titre'] ),
			'description' => sanitize_text_field( $personnalise['description'] ),
			'source'      => 'ia',
		);
	}

	$themes = eco_themes();
	$nom    = isset( $themes[ $theme ] ) ? $themes[ $theme ] : $theme;

	// 2. IA de WordPress (clés gérées hors du code, dans Réglages › Connecteurs).
	if ( eco_actif( 'ia_wordpress' ) && function_exists( 'wp_ai_client_prompt' ) ) {
		$consigne = sprintf(
			'Propose un défi écologique concret. Thème : %1$s. Durée : %2$d jours. Niveau : %3$s. Réponds en français, uniquement avec ce JSON : {"titre": "5 mots maximum", "description": "une phrase de 25 mots maximum"}',
			$nom,
			$duree,
			$niveau
		);
		$prompt = wp_ai_client_prompt( $consigne )
			->using_system_instruction( 'Tu es le coach bienveillant du site éco-challenge.' )
			->using_max_tokens( 150 );

		if ( $prompt->is_supported_for_text_generation() ) {
			$texte = $prompt->generate_text();
			if ( ! is_wp_error( $texte ) ) {
				// Le modèle entoure parfois le JSON de ```json … ``` : on ne garde que l'objet.
				$debut   = strpos( (string) $texte, '{' );
				$fin     = strrpos( (string) $texte, '}' );
				$donnees = ( false !== $debut && false !== $fin ) ? json_decode( substr( $texte, $debut, $fin - $debut + 1 ), true ) : null;
				if ( is_array( $donnees ) && ! empty( $donnees['titre'] ) && ! empty( $donnees['description'] ) ) {
					return array(
						'titre'       => sanitize_text_field( $donnees['titre'] ),
						'description' => sanitize_text_field( $donnees['description'] ),
						'source'      => 'ia',
					);
				}
			}
		}
	}

	// 3. Plan B : toujours une réponse, même sans IA.
	$idees = array(
		'mobilite'     => array( 'Vélo au boulot', 'Pendant %d jours, laissez la voiture au garage et allez au travail à vélo ou à pied.' ),
		'alimentation' => array( 'Zéro plastique', 'Pendant %d jours, faites vos courses sans emballage jetable.' ),
		'energie'      => array( 'Douche express', 'Pendant %d jours, limitez vos douches à 5 minutes.' ),
	);
	$idee = isset( $idees[ $theme ] ) ? $idees[ $theme ] : array( 'Un petit geste par jour', 'Pendant %d jours, choisissez chaque jour un geste simple pour la planète.' );

	return array(
		'titre'       => $idee[0],
		'description' => sprintf( $idee[1], $duree ),
		'source'      => 'plan-b',
	);
}


/* ===========================================================================
 * 7. Optionnel — Masquer les compositions de Twenty Twenty-Five
 * ===========================================================================
 * Utile quand l'équipe a créé ses propres compositions et veut un éditeur
 * plus simple pour le client.
 * Attention : on ne les SUPPRIME pas. L'en-tête, le pied de page et les
 * modèles de Twenty Twenty-Five sont construits avec ces compositions ;
 * les supprimer viderait le site. On les retire seulement de l'inserteur
 * (« inserter » => false) : elles fonctionnent toujours, mais le client
 * ne les voit plus dans la liste. */

function eco_masquer_compositions_parent() {
	if ( ! eco_actif( 'masquer_compositions_parent' ) ) {
		return;
	}
	$registre = WP_Block_Patterns_Registry::get_instance();
	foreach ( $registre->get_all_registered() as $composition ) {
		$visible = ! isset( $composition['inserter'] ) || false !== $composition['inserter'];
		if ( $visible && 0 === strpos( $composition['name'], 'twentytwentyfive/' ) ) {
			$nom                     = $composition['name'];
			$composition['inserter'] = false;
			unregister_block_pattern( $nom );
			register_block_pattern( $nom, $composition );
		}
	}
}
add_action( 'init', 'eco_masquer_compositions_parent', 20 );


/* ===========================================================================
 * 8. PROJET · Zone réservée — Intégration IA de l'équipe
 * ===========================================================================
 * Ce bloc ne fait RIEN tant que l'interrupteur integration_ia est vide :
 * les exercices des séances (blocs 1 à 7, route /eco/v1/defi de S8)
 * restent identiques.
 * Une ou plusieurs fonctionnalités : la route accepte chacun des types choisis.
 * Règle des deux cours (README, section 11) : deux fonctionnalités au maximum,
 * la seconde après validation du cours IA. La Caméra est un bonus du cours IA.
 *
 * Qui fait quoi (contrat commun des deux cours) :
 *   - Front-end, ce thème : la route commune, la page, le formulaire, les
 *     4 états (attente, résultat, erreur, repli) et la mention de transparence.
 *   - Déploiement IA, extension du cours IA : le prompt, la base de
 *     connaissances, l'appel au modèle, la version simulée et le contrat de
 *     chaque équipe, branchés sur les filtres eco_ia_entrees et eco_ia_reponse.
 *     Les clés restent dans Réglages › Connecteurs.
 *
 * Contrat JSON commun : POST /wp-json/eco/v1/ia · en-tête X-WP-Nonce
 * // À compléter par le professeur de développement et déploiement IA, selon les fonctionnalités.
 *   Requête  { "type": "coach|chatbot|assistant|camera", "entrees": { … }, "mode": "ia|simule" }
 *            mode est facultatif (ia par défaut) ; simule = version sans IA, choisie
 *            volontairement (bouton « Avec l'IA / Sans IA » du jury).
 *   Réponse  { "type": "…", "resultat": { … } ou null, "source": "ia|simule|plan-b",
 *              "avertissement": { "type": "fiches|hors-fiches", "texte": "…" } (facultatif) }
 *   Erreurs  400 champ invalide · 403 jeton absent ou expiré · 404 aucune intégration
 *            active · 429 trop d'appels. Caméra : eco_photo_poids, eco_photo_format,
 *            eco_photo_absente (400).
 *   Délais   l'extension abandonne l'IA après 10 s (plan B) ; la page attend 15 s.
 *
 *   coach      entrees  { "reponses": { "lieu": "interieur", … }, "precisions": "…" }
 *                       3 à 8 réponses · precisions facultatif, 200 caractères max
 *              resultat { "propositions": [ { "id", "titre", "description" } ×3 ], "message": "…" }
 *   chatbot    entrees  { "question": "…", "historique": [ { "question", "reponse" } ] }
 *                       question 300 car. max · historique facultatif, 3 échanges max
 *              resultat { "reponse": "…", "pages": [ { "titre", "url" } ] }   0 à 2 pages
 *   assistant  entrees  { "titre": "…", "texte": "…", "categories": [ "…" ] }
 *                       titre 80 car. max · texte 10 à 2000 car. · 20 catégories max
 *              resultat { "suggestion": "…", "categorie": "…" ou null, "securite": [ "…" ] }
 *   camera     avec photo : multipart/form-data, champs type=camera et photo
 *                       (JPEG ou PNG, 4 Mo max, contrôlés ici) · jamais de cache
 *              sans photo : JSON, entrees { "questionnaire": { "pattes": "6", … } }
 *                       3 à 8 réponses · toujours en version simulée
 *              resultat { "statut": "reconnu|visage|personne|floue|hors_sujet|inconnu",
 *                         "famille": { "id", "nom", "url" } ou null, "confiance", "explication" }
 *
 * Plan B : sans extension branchée, ou si l'IA ne répond pas, resultat vaut
 * null et la page affiche son repli (FAQ à boutons, publication sans aide,
 * questionnaire d'observation…). La vraie version simulée de chaque
 * fonctionnalité est fournie par l'extension du cours IA.
 *
 * Rappel important : Sur le front, on affiche toujours le résultat avec textContent, jamais innerHTML pour éviter les injections de code malveillant.
 * L'IA ne doit pas inventer de balises HTML. */

/*
 * Les fonctionnalités intelligentes choisies par l'équipe.
 * Accepte une valeur seule ('coach') ou une liste (array( 'assistant', 'coach' )).
 * Les valeurs inconnues et les doublons sont ignorés.
 *
 * @return string[] Par exemple array( 'assistant', 'coach' ), ou array() si aucune. */

function eco_integrations() {
	$reglages = eco_reglages();
	$valeur   = isset( $reglages['integration_ia'] ) ? $reglages['integration_ia'] : '';
	$valeurs  = is_array( $valeur ) ? $valeur : array( $valeur );
	$connues  = array( 'coach', 'chatbot', 'assistant', 'camera' );
	return array_values( array_unique( array_intersect( $valeurs, $connues ) ) );
}

/*
 * Le catalogue des défis actifs (data/defis.json), celui de l'exercice S8.
 * Le projet ne l'utilise plus pour son plan B : le Coach s'appuie sur le
 * catalogue de l'équipe, géré par l'extension du cours IA. La fonction reste
 * disponible pour les extensions qui l'appellent encore.
 *
 * @return array[] Défis dont « actif » vaut true. */

function eco_catalogue_defis() {
	$chemin = get_stylesheet_directory() . '/data/defis.json';
	if ( ! is_readable( $chemin ) ) {
		return array();
	}
	$donnees = json_decode( (string) file_get_contents( $chemin ), true );
	if ( empty( $donnees['defis'] ) || ! is_array( $donnees['defis'] ) ) {
		return array();
	}
	return array_values(
		array_filter(
			$donnees['defis'],
			function ( $defi ) {
				return ! empty( $defi['actif'] );
			}
		)
	);
}

/*
 * Crée la route du projet, seulement si l'équipe a choisi au moins une fonctionnalité.
 * « enum » n'accepte que les types de l'équipe : tout autre type → erreur 400.
 * Sans fonctionnalité, la route n'existe pas : WordPress répond 404.
 * « entrees » n'est pas obligatoire : la Caméra envoie une photo à la place. */

function eco_route_ia() {
	$integrations = eco_integrations();
	if ( empty( $integrations ) ) {
		return;
	}
	register_rest_route(
		'eco/v1',
		'/ia',
		array(
			'methods'             => 'POST',
			'callback'            => 'eco_repondre_ia',
			'permission_callback' => 'eco_verifier_nonce',
			'args'                => array(
				'type'    => array(
					'required'          => true,
					'type'              => 'string',
					'enum'              => $integrations,
					'validate_callback' => 'rest_validate_request_arg',
					'sanitize_callback' => 'rest_sanitize_request_arg',
				),
				'entrees' => array(
					'required'          => false,
					'type'              => 'object',
					'default'           => array(),
					'validate_callback' => 'rest_validate_request_arg',
				),
				'mode'    => array(
					'required'          => false,
					'type'              => 'string',
					'enum'              => array( 'ia', 'simule' ),
					'default'           => 'ia',
					'validate_callback' => 'rest_validate_request_arg',
					'sanitize_callback' => 'rest_sanitize_request_arg',
				),
			),
		)
	);
}
add_action( 'rest_api_init', 'eco_route_ia' );

/*
 * Erreur 400 du contrat, avec un message déjà rédigé pour le visiteur.
 *
 * @param string $message Message affiché par la page.
 * @param string $code    Code de l'erreur (eco_entrees par défaut).
 * @return WP_Error */

function eco_erreur_entrees( $message, $code = 'eco_entrees' ) {
	return new WP_Error( $code, $message, array( 'status' => 400 ) );
}

/*
 * Réponses fermées d'un questionnaire (Coach, questionnaire de la Caméra) :
 * clés et valeurs en minuscules, sans accent ni espace.
 *
 * @param mixed $reponses Réponses brutes.
 * @return array Réponses propres. */

function eco_nettoyer_reponses( $reponses ) {
	$propres = array();
	foreach ( (array) $reponses as $cle => $valeur ) {
		if ( ( is_string( $valeur ) || is_int( $valeur ) ) && '' !== sanitize_key( (string) $valeur ) ) {
			$propres[ sanitize_key( $cle ) ] = sanitize_key( (string) $valeur );
		}
	}
	return $propres;
}

/*
 * Caméra : contrôle la photo envoyée en multipart/form-data, sans jamais la garder.
 * Le vrai type est lu dans le fichier, pas dans son nom.
 *
 * @param array $fichier Entrée « photo » de $_FILES.
 * @return array|WP_Error { tmp_name, size, type } ou erreur 400. */

function eco_controler_photo( $fichier ) {
	$poids_max = (int) apply_filters( 'eco_photo_poids_max', 4 * MB_IN_BYTES );
	$erreur    = isset( $fichier['error'] ) ? (int) $fichier['error'] : UPLOAD_ERR_NO_FILE;

	if ( in_array( $erreur, array( UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE ), true )
		|| ( UPLOAD_ERR_OK === $erreur && (int) $fichier['size'] > $poids_max ) ) {
		return eco_erreur_entrees( 'Cette photo dépasse 4 Mo. Choisissez une photo plus légère.', 'eco_photo_poids' );
	}
	if ( UPLOAD_ERR_OK !== $erreur || empty( $fichier['tmp_name'] ) || ! is_uploaded_file( $fichier['tmp_name'] ) || (int) $fichier['size'] <= 0 ) {
		return eco_erreur_entrees( 'Votre photo n\'a pas pu être envoyée. Essayez avec une autre photo.', 'eco_photo_absente' );
	}
	$type = wp_get_image_mime( $fichier['tmp_name'] );
	if ( ! in_array( $type, array( 'image/jpeg', 'image/png' ), true ) ) {
		return eco_erreur_entrees( 'Ce format n\'est pas accepté. Choisissez une photo JPEG ou PNG.', 'eco_photo_format' );
	}
	return array(
		'tmp_name' => $fichier['tmp_name'],
		'size'     => (int) $fichier['size'],
		'type'     => $type,
	);
}

/*
 * Vérifie et nettoie les champs communs du contrat, avant tout envoi à l'IA.
 * Les champs propres à chaque équipe passent ensuite par le filtre
 * eco_ia_entrees (extension du cours IA, voir eco_repondre_ia()).
 *
 * @param string          $type    coach, chatbot, assistant ou camera.
 * @param array           $entrees Entrées brutes reçues.
 * @param WP_REST_Request $requete La requête (fichiers de la Caméra).
 * @return array|WP_Error Entrées propres, ou erreur 400. */

function eco_nettoyer_entrees( $type, $entrees, $requete ) {
	if ( 'coach' === $type ) {
		$propres = eco_nettoyer_reponses( isset( $entrees['reponses'] ) ? $entrees['reponses'] : array() );
		if ( count( $propres ) < 3 || count( $propres ) > 8 ) {
			return eco_erreur_entrees( 'Le questionnaire doit compter de 3 à 8 réponses.' );
		}
		$propres = array( 'reponses' => $propres );
		if ( isset( $entrees['precisions'] ) && is_string( $entrees['precisions'] ) && '' !== trim( $entrees['precisions'] ) ) {
			$precisions = sanitize_textarea_field( $entrees['precisions'] );
			if ( mb_strlen( $precisions ) > 200 ) {
				return eco_erreur_entrees( 'Les précisions comptent 200 caractères au maximum.' );
			}
			$propres['precisions'] = $precisions;
		}
		return $propres;
	}

	if ( 'chatbot' === $type ) {
		$question = isset( $entrees['question'] ) && is_string( $entrees['question'] ) ? sanitize_text_field( $entrees['question'] ) : '';
		if ( '' === $question || mb_strlen( $question ) > 300 ) {
			return eco_erreur_entrees( 'La question doit compter de 1 à 300 caractères.' );
		}
		$propres = array( 'question' => $question );
		if ( ! empty( $entrees['historique'] ) && is_array( $entrees['historique'] ) ) {
			$historique = array();
			foreach ( array_slice( array_values( $entrees['historique'] ), -3 ) as $echange ) {
				$q = isset( $echange['question'] ) && is_string( $echange['question'] ) ? sanitize_text_field( $echange['question'] ) : '';
				$r = isset( $echange['reponse'] ) && is_string( $echange['reponse'] ) ? sanitize_textarea_field( $echange['reponse'] ) : '';
				if ( '' === $q || '' === $r || mb_strlen( $q ) > 300 || mb_strlen( $r ) > 1200 ) {
					return eco_erreur_entrees( 'L\'historique contient un échange invalide.' );
				}
				$historique[] = array(
					'question' => $q,
					'reponse'  => $r,
				);
			}
			$propres['historique'] = $historique;
		}
		return $propres;
	}

	if ( 'camera' === $type ) {
		$fichiers = $requete->get_file_params();
		if ( isset( $fichiers['photo'] ) ) {
			$photo = eco_controler_photo( $fichiers['photo'] );
			return is_wp_error( $photo ) ? $photo : array( 'photo' => $photo );
		}
		$propres = eco_nettoyer_reponses( isset( $entrees['questionnaire'] ) ? $entrees['questionnaire'] : array() );
		if ( empty( $propres ) ) {
			return eco_erreur_entrees( 'Votre photo n\'a pas pu être envoyée. Essayez avec une autre photo.', 'eco_photo_absente' );
		}
		if ( count( $propres ) < 3 || count( $propres ) > 8 ) {
			return eco_erreur_entrees( 'Le questionnaire d\'observation doit compter de 3 à 8 réponses.' );
		}
		return array( 'questionnaire' => $propres );
	}

	// assistant
	$titre = isset( $entrees['titre'] ) && is_string( $entrees['titre'] ) ? sanitize_text_field( $entrees['titre'] ) : '';
	$texte = isset( $entrees['texte'] ) && is_string( $entrees['texte'] ) ? sanitize_textarea_field( $entrees['texte'] ) : '';
	if ( mb_strlen( $titre ) > 80 ) {
		return eco_erreur_entrees( 'Le titre compte 80 caractères au maximum.' );
	}
	if ( mb_strlen( $texte ) < 10 || mb_strlen( $texte ) > 2000 ) {
		return eco_erreur_entrees( 'Le texte doit compter de 10 à 2000 caractères : l\'IA améliore un texte, elle ne l\'invente pas.' );
	}
	$categories = array();
	if ( isset( $entrees['categories'] ) && is_array( $entrees['categories'] ) ) {
		foreach ( array_slice( $entrees['categories'], 0, 20 ) as $categorie ) {
			if ( is_string( $categorie ) && '' !== sanitize_text_field( $categorie ) ) {
				$categories[] = sanitize_text_field( $categorie );
			}
		}
	}
	return array(
		'titre'      => $titre,
		'texte'      => $texte,
		'categories' => $categories,
	);
}

/*
 * Lit ce que renvoie l'extension du cours IA par le filtre eco_ia_reponse.
 * Deux formes acceptées :
 *   - le « resultat » seul (array) : il est annoncé source « ia » ;
 *   - une enveloppe array( 'resultat' => … ou null, 'source' => 'ia|simule|plan-b',
 *     'avertissement' => array( 'type' => 'fiches|hors-fiches', 'texte' => '…' ) ).
 * null, ou rien de valable : resultat null, source « simule » ou « plan-b » selon le mode.
 *
 * @param mixed  $sortie Valeur renvoyée par le filtre.
 * @param string $mode   ia ou simule.
 * @return array { resultat, source, avertissement } */

function eco_lire_reponse_extension( $sortie, $mode ) {
	$repli = array(
		'resultat'      => null,
		'source'        => 'simule' === $mode ? 'simule' : 'plan-b',
		'avertissement' => null,
	);
	if ( ! is_array( $sortie ) || empty( $sortie ) ) {
		return $repli;
	}
	if ( ! array_key_exists( 'resultat', $sortie ) && ! array_key_exists( 'source', $sortie ) ) {
		return array(
			'resultat'      => $sortie,
			'source'        => 'ia',
			'avertissement' => null,
		);
	}

	$resultat = isset( $sortie['resultat'] ) && is_array( $sortie['resultat'] ) && ! empty( $sortie['resultat'] ) ? $sortie['resultat'] : null;
	$source   = isset( $sortie['source'] ) && in_array( $sortie['source'], array( 'ia', 'simule', 'plan-b' ), true ) ? $sortie['source'] : null;
	if ( null === $source ) {
		$source = null === $resultat ? $repli['source'] : ( 'simule' === $mode ? 'simule' : 'ia' );
	}

	$avertissement = null;
	if ( isset( $sortie['avertissement']['type'], $sortie['avertissement']['texte'] )
		&& in_array( $sortie['avertissement']['type'], array( 'fiches', 'hors-fiches' ), true ) ) {
		$avertissement = array(
			'type'  => $sortie['avertissement']['type'],
			'texte' => mb_substr( sanitize_text_field( (string) $sortie['avertissement']['texte'] ), 0, 300 ),
		);
	}

	return array(
		'resultat'      => $resultat,
		'source'        => $source,
		'avertissement' => $avertissement,
	);
}

/*
 * Caméra : dernier contrôle du résultat, quel que soit le réglage de l'IA.
 * Statut hors liste → résultat refusé (plan B). Visage ou personne : rien
 * d'autre n'est renvoyé, même si l'IA a ajouté une famille ou une explication.
 *
 * @param array $resultat Résultat de l'extension.
 * @return array|null Résultat contrôlé, ou null s'il est refusé. */

function eco_controler_camera( $resultat ) {
	$statuts = array( 'reconnu', 'visage', 'personne', 'floue', 'hors_sujet', 'inconnu' );
	if ( ! isset( $resultat['statut'] ) || ! in_array( $resultat['statut'], $statuts, true ) ) {
		return null;
	}
	if ( in_array( $resultat['statut'], array( 'visage', 'personne' ), true ) ) {
		return array(
			'statut'      => $resultat['statut'],
			'famille'     => null,
			'confiance'   => '',
			'explication' => '',
		);
	}
	return $resultat;
}

/*
 * Répond selon le contrat commun. Même limite d'appels que la route S8.
 *
 * @param WP_REST_Request $requete La requête reçue.
 * @return WP_REST_Response|WP_Error */

function eco_repondre_ia( $requete ) {
	$ip_hash   = md5( isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'inconnu' );
	$cle_quota = 'eco_quota_' . $ip_hash;
	$appels    = (int) get_transient( $cle_quota );
	if ( $appels >= (int) apply_filters( 'eco_quota_appels', 20 ) ) {
		return new WP_Error( 'eco_quota', 'Trop de demandes en peu de temps. Patientez quelques minutes.', array( 'status' => 429 ) );
	}
	set_transient( $cle_quota, $appels + 1, 10 * MINUTE_IN_SECONDS );

	$type    = $requete['type'];
	$mode    = 'simule' === $requete['mode'] ? 'simule' : 'ia';
	$brutes  = is_array( $requete['entrees'] ) ? $requete['entrees'] : array();
	$entrees = eco_nettoyer_entrees( $type, $brutes, $requete );
	if ( is_wp_error( $entrees ) ) {
		return $entrees;
	}

	// Champs propres à l'équipe (une commune, une date…), validés par l'extension
	// du cours IA d'après le contrat de l'équipe :
	//   add_filter( 'eco_ia_entrees', 'ma_fonction', 10, 3 );
	//   function ma_fonction( $entrees, $type, $brutes ) { … return $entrees; } // ou WP_Error (400)
	// $brutes n'est pas nettoyé : l'extension nettoie chaque champ qu'elle ajoute.
	$entrees = apply_filters( 'eco_ia_entrees', $entrees, $type, $brutes );
	if ( is_wp_error( $entrees ) ) {
		$donnees = $entrees->get_error_data();
		if ( ! is_array( $donnees ) || empty( $donnees['status'] ) ) {
			$entrees->add_data( array_merge( (array) $donnees, array( 'status' => 400 ) ) );
		}
		return $entrees;
	}

	// Le questionnaire d'observation de la Caméra est toujours la version sans IA.
	if ( 'camera' === $type && ! isset( $entrees['photo'] ) ) {
		$mode = 'simule';
	}

	// Sobriété : même demande = même réponse pendant 24 h, sans rappeler l'IA.
	// Seulement en mode ia, jamais pour la Caméra (la photo n'est jamais gardée).
	$avec_cache = 'ia' === $mode && 'camera' !== $type;
	$cle_cache  = 'eco_ia_' . md5( $type . '|' . wp_json_encode( $entrees ) );
	if ( $avec_cache ) {
		$en_cache = get_transient( $cle_cache );
		if ( false !== $en_cache ) {
			return rest_ensure_response( $en_cache );
		}
	}

	// Branchement du cours Déploiement IA, dans son extension :
	//   add_filter( 'eco_ia_reponse', 'ma_fonction', 10, 4 );
	//   function ma_fonction( $resultat, $type, $entrees, $mode ) { … }
	// La fonction renvoie le « resultat » du contrat (annoncé « ia »), ou une
	// enveloppe array( 'resultat' => …, 'source' => 'ia|simule|plan-b', 'avertissement' => … ),
	// ou null en cas d'échec. En mode « simule », elle n'appelle jamais l'IA.
	$lu = eco_lire_reponse_extension( apply_filters( 'eco_ia_reponse', null, $type, $entrees, $mode ), $mode );

	if ( 'camera' === $type && null !== $lu['resultat'] ) {
		$lu['resultat'] = eco_controler_camera( $lu['resultat'] );
		if ( null === $lu['resultat'] ) {
			$lu['source'] = 'simule' === $mode ? 'simule' : 'plan-b';
		}
	}

	$reponse = array(
		'type'     => $type,
		'resultat' => $lu['resultat'],
		'source'   => $lu['source'],
	);
	if ( null !== $lu['avertissement'] ) {
		$reponse['avertissement'] = $lu['avertissement'];
	}

	if ( $avec_cache && 'ia' === $reponse['source'] && null !== $reponse['resultat'] ) {
		set_transient( $cle_cache, $reponse, DAY_IN_SECONDS );
	}
	return rest_ensure_response( $reponse );
}

/* ===========================================================================
 * 9. Outil — L'aperçu du thème dans Apparence › Thèmes
 * ===========================================================================
 * WordPress ne cherche que « screenshot.png » (ou .jpg, .webp…). Ce bloc
 * lui indique notre fichier screenshot-eco-palette.png.
 * Limite : functions.php ne se charge que quand le thème est actif. Avant
 * l'activation, la vignette reste grise ; elle apparaît dès l'activation. */

function eco_apercu_theme( $themes ) {
	$slug    = get_stylesheet();
	$fichier = 'screenshot-eco-palette.png';
	if ( isset( $themes[ $slug ] ) && file_exists( get_stylesheet_directory() . '/' . $fichier ) ) {
		$themes[ $slug ]['screenshot'] = array( get_stylesheet_directory_uri() . '/' . $fichier . '?ver=' . eco_version( $fichier ) );
	}
	return $themes;
}
add_filter( 'wp_prepare_themes_for_js', 'eco_apercu_theme' );

/* ===========================================================================
 * 10. Optionnel — Masquer les variations de style de Twenty Twenty-Five
 * ===========================================================================
 * WordPress ajoute les variations du parent (dossier styles/) à celles de
 * l'enfant, sans réglage pour les retirer. L'Éditeur de site les reçoit par
 * l'API REST (…/global-styles/themes/…/variations) : on filtre cette réponse
 * pour ne garder que les variations présentes dans styles/ du thème enfant.
 * Le style par défaut (theme.json) reste toujours proposé. */

function eco_masquer_styles_parent( $reponse, $gestionnaire, $requete ) {
	if ( ! eco_actif( 'masquer_styles_parent' ) || is_wp_error( $reponse ) ) {
		return $reponse;
	}
	if ( ! preg_match( '#^/wp/v2/global-styles/themes/[^/]+/variations$#', $requete->get_route() ) ) {
		return $reponse;
	}

	// Titres des variations du thème enfant (styles/*.json).
	$titres = array();
	foreach ( (array) glob( get_stylesheet_directory() . '/styles/*.json' ) as $fichier ) {
		$donnees = json_decode( (string) file_get_contents( $fichier ), true );
		$titres[] = isset( $donnees['title'] ) ? $donnees['title'] : basename( $fichier, '.json' );
	}

	$variations = array_filter(
		(array) $reponse->get_data(),
		function ( $variation ) use ( $titres ) {
			return isset( $variation['title'] ) && in_array( $variation['title'], $titres, true );
		}
	);
	$reponse->set_data( array_values( $variations ) );
	return $reponse;
}
add_filter( 'rest_request_after_callbacks', 'eco_masquer_styles_parent', 10, 3 );
