<?php
/**
 * The template for displaying the header.
 *
 * @package pl-scaffold-theme
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="icon" type="image/png" href="<?php echo esc_url( get_template_directory_uri() ); ?>/dist/images/icon-16.png" sizes="16x16">
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<?php wp_body_open(); ?>

		<main id="main" role="main" tabindex="-1">

			<h1><?php bloginfo( 'name' ); ?></h1>
