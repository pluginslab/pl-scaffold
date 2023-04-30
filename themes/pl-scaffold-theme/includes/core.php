<?php
/**
 * Core setup, site hooks and filters.
 *
 * @package pl-scaffold-theme
 */

namespace PLScaffoldTheme\Core;

/**
 * Set up theme defaults and register supported WordPress features.
 *
 * @return void
 */
function setup() {
	$n = function( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	add_action( 'after_setup_theme', $n( 'i18n' ) );
	add_action( 'after_setup_theme', $n( 'theme_setup' ) );
	add_action( 'wp_enqueue_scripts', $n( 'scripts' ) );
	add_action( 'wp_enqueue_scripts', $n( 'styles' ) );
	add_action( 'admin_enqueue_scripts', $n( 'admin_styles' ) );
	add_action( 'admin_enqueue_scripts', $n( 'admin_scripts' ) );
	add_action( 'wp_head', $n( 'js_detection' ), 0 );
	add_action( 'wp_head', $n( 'add_manifest' ), 10 );
	add_action( 'wp_head', $n( 'embed_ct_css' ), 0 );

	add_filter( 'script_loader_tag', $n( 'script_loader_tag' ), 10, 2 );
}

/**
 * Makes Theme available for translation.
 *
 * Translations can be added to the /languages directory.
 * If you're building a theme based on "tenup-theme", change the
 * filename of '/languages/TenUpTheme.pot' to the name of your project.
 *
 * @return void
 */
function i18n() {
	load_theme_textdomain( 'pl-scaffold-theme', PL_SCAFFOLD_THEME_PATH . '/languages' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function theme_setup() {
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'editor-styles' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'gallery',
		)
	);

	add_editor_style( 'dist/backend.css' );

	remove_theme_support( 'core-block-patterns' );

	// by adding the `theme.json` file block templates automatically get enabled.
	// because the template editor will need additional QA and work to get right
	// the default is to disable this feature.
	remove_theme_support( 'block-templates' );

	// This theme uses wp_nav_menu() in three locations.
	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary Menu', 'pl-scaffold-theme' ),
		)
	);
}

/**
 * Enqueue scripts for front-end.
 *
 * @return void
 */
function scripts() {

	if ( ! file_exists( PL_SCAFFOLD_THEME_DIST_PATH . '/dist/' ) ) {
		return;
	}

	wp_enqueue_script(
		'frontend',
		PL_SCAFFOLD_THEME_TEMPLATE_URL . '/dist/frontend.js',
		array(),
		filemtime( PL_SCAFFOLD_THEME_DIST_PATH . 'frontend.js' ),
		true
	);

}

/**
 * Enqueue scripts for admin
 *
 * @return void
 */
function admin_scripts() {

	if ( ! file_exists( PL_SCAFFOLD_THEME_DIST_PATH . '/dist/' ) ) {
		return;
	}

	wp_enqueue_script(
		'backend',
		PL_SCAFFOLD_THEME_TEMPLATE_URL . '/dist/backend.js',
		array(),
		filemtime( PL_SCAFFOLD_THEME_DIST_PATH . 'backend.js' ),
		true
	);
}

/**
 * Enqueue styles for front-end.
 *
 * @return void
 */
function styles() {

	if ( ! file_exists( PL_SCAFFOLD_THEME_DIST_PATH . '/dist/' ) ) {
		return;
	}

	wp_enqueue_style(
		'styles',
		PL_SCAFFOLD_THEME_TEMPLATE_URL . '/dist/frontend.css',
		array(),
		filemtime( PL_SCAFFOLD_THEME_DIST_PATH . 'frontend.css' ),
	);
}

/**
 * Enqueue styles for admin
 *
 * @return void
 */
function admin_styles() {

	if ( ! file_exists( PL_SCAFFOLD_THEME_DIST_PATH . '/dist/' ) ) {
		return;
	}

	wp_enqueue_style(
		'styles',
		PL_SCAFFOLD_THEME_TEMPLATE_URL . '/dist/backend.css',
		array(),
		filemtime( PL_SCAFFOLD_THEME_DIST_PATH . 'backend.css' ),
	);
}

/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @return void
 */
function js_detection() {

	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}

/**
 * Add async/defer attributes to enqueued scripts that have the specified script_execution flag.
 *
 * @link https://core.trac.wordpress.org/ticket/12009
 * @param string $tag    The script tag.
 * @param string $handle The script handle.
 * @return string
 */
function script_loader_tag( $tag, $handle ) {
	$script_execution = wp_scripts()->get_data( $handle, 'script_execution' );

	if ( ! $script_execution ) {
		return $tag;
	}

	if ( 'async' !== $script_execution && 'defer' !== $script_execution ) {
		return $tag;
	}

	// Abort adding async/defer for scripts that have this script as a dependency. _doing_it_wrong()?
	foreach ( wp_scripts()->registered as $script ) {
		if ( in_array( $handle, $script->deps, true ) ) {
			return $tag;
		}
	}

	// Add the attribute if it hasn't already been added.
	if ( ! preg_match( ":\s$script_execution(=|>|\s):", $tag ) ) {
		$tag = preg_replace( ':(?=></script>):', " $script_execution", $tag, 1 );
	}

	return $tag;
}

/**
 * Appends a link tag used to add a manifest.json to the head
 *
 * @return void
 */
function add_manifest() {
	echo "<link rel='manifest' href='" . esc_url( PL_SCAFFOLD_THEME_TEMPLATE_URL . '/manifest.json' ) . "' />";
}

/**
 * Inlines ct.css in the head
 *
 * Embeds a diagnostic CSS file written by Harry Roberts
 * that helps diagnose render blocking resources and other
 * performance bottle necks.
 *
 * The CSS is inlined in the head of the document, only when requesting
 * a page with the query param ?debug_perf=1
 *
 * @link https://csswizardry.com/ct/
 * @return void
 */
function embed_ct_css() {

	$debug_performance = rest_sanitize_boolean( filter_input( INPUT_GET, 'debug_perf', FILTER_SANITIZE_NUMBER_INT ) );

	if ( ! $debug_performance ) {
		return;
	};

	wp_register_style( 'ct', false ); // phpcs:ignore
	wp_enqueue_style( 'ct' );
	wp_add_inline_style( 'ct', 'head{--ct-is-problematic:solid;--ct-is-affected:dashed;--ct-notify:#0bce6b;--ct-warn:#ffa400;--ct-error:#ff4e42}head,head [rel=stylesheet],head script,head script:not([src])[async],head script:not([src])[defer],head script~meta[http-equiv=content-security-policy],head style,head>meta[charset]:not(:nth-child(-n+5)){display:block}head [rel=stylesheet],head script,head script~meta[http-equiv=content-security-policy],head style,head title,head>meta[charset]:not(:nth-child(-n+5)){margin:5px;padding:5px;border-width:5px;background-color:#fff;color:#333}head ::before,head script,head style{font:16px/1.5 monospace,monospace;display:block}head ::before{font-weight:700}head link[rel=stylesheet],head script[src]{border-style:var(--ct-is-problematic);border-color:var(--ct-warn)}head script[src]::before{content:"[Blocking Script – " attr(src) "]"}head link[rel=stylesheet]::before{content:"[Blocking Stylesheet – " attr(href) "]"}head script:not(:empty),head style:not(:empty){max-height:5em;overflow:auto;background-color:#ffd;white-space:pre;border-color:var(--ct-notify);border-style:var(--ct-is-problematic)}head script:not(:empty)::before{content:"[Inline Script] "}head style:not(:empty)::before{content:"[Inline Style] "}head script:not(:empty)~title,head script[src]:not([async]):not([defer]):not([type=module])~title{display:block;border-style:var(--ct-is-affected);border-color:var(--ct-error)}head script:not(:empty)~title::before,head script[src]:not([async]):not([defer]):not([type=module])~title::before{content:"[<title> blocked by JS] "}head [rel=stylesheet]:not([media=print]):not(.ct)~script,head style:not(:empty)~script{border-style:var(--ct-is-affected);border-color:var(--ct-warn)}head [rel=stylesheet]:not([media=print]):not(.ct)~script::before,head style:not(:empty)~script::before{content:"[JS blocked by CSS – " attr(src) "]"}head script[src][src][async][defer]{display:block;border-style:var(--ct-is-problematic);border-color:var(--ct-warn)}head script[src][src][async][defer]::before{content:"[async and defer is redundant: prefer defer – " attr(src) "]"}head script:not([src])[async],head script:not([src])[defer]{border-style:var(--ct-is-problematic);border-color:var(--ct-warn)}head script:not([src])[async]::before{content:"The async attribute is redundant on inline scripts"}head script:not([src])[defer]::before{content:"The defer attribute is redundant on inline scripts"}head [rel=stylesheet][href^="//"],head [rel=stylesheet][href^=http],head script[src][src][src^="//"],head script[src][src][src^=http]{border-style:var(--ct-is-problematic);border-color:var(--ct-error)}head script[src][src][src^="//"]::before,head script[src][src][src^=http]::before{content:"[Third Party Blocking Script – " attr(src) "]"}head [rel=stylesheet][href^="//"]::before,head [rel=stylesheet][href^=http]::before{content:"[Third Party Blocking Stylesheet – " attr(href) "]"}head script~meta[http-equiv=content-security-policy]{border-style:var(--ct-is-problematic);border-color:var(--ct-error)}head script~meta[http-equiv=content-security-policy]::before{content:"[Meta CSP defined after JS]"}head>meta[charset]:not(:nth-child(-n+5)){border-style:var(--ct-is-problematic);border-color:var(--ct-warn)}head>meta[charset]:not(:nth-child(-n+5))::before{content:"[Charset should appear as early as possible]"}link[rel=stylesheet].ct,link[rel=stylesheet][media=print],script[async],script[defer],script[type=module],style.ct{display:none}' );

}
