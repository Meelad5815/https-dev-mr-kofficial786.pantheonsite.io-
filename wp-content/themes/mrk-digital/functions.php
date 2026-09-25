<?php
/** MRK Digital theme bootstrap. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function mrk_setup() {
	load_theme_textdomain( 'mrk-digital', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	register_nav_menus( array(
		'primary' => __( 'Primary navigation', 'mrk-digital' ),
		'footer'  => __( 'Footer navigation', 'mrk-digital' ),
	) );
}
add_action( 'after_setup_theme', 'mrk_setup' );

function mrk_assets() {
	wp_enqueue_style( 'mrk-digital', get_stylesheet_uri(), array(), '1.0.0' );
	wp_enqueue_style( 'mrk-layout', get_template_directory_uri() . '/assets/css/site.css', array( 'mrk-digital' ), '1.0.0' );
	wp_enqueue_script( 'mrk-digital', get_template_directory_uri() . '/assets/js/site.js', array(), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'mrk_assets' );

function mrk_content_types() {
	register_post_type( 'mrk_service', array(
		'labels' => array(
			'name' => __( 'Services', 'mrk-digital' ),
			'singular_name' => __( 'Service', 'mrk-digital' ),
			'add_new_item' => __( 'Add Service', 'mrk-digital' ),
		),
		'public' => true,
		'has_archive' => 'services',
		'rewrite' => array( 'slug' => 'services' ),
		'menu_icon' => 'dashicons-admin-tools',
		'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
		'show_in_rest' => true,
	) );

	register_post_type( 'mrk_project', array(
		'labels' => array(
			'name' => __( 'Projects', 'mrk-digital' ),
			'singular_name' => __( 'Project', 'mrk-digital' ),
			'add_new_item' => __( 'Add Project', 'mrk-digital' ),
		),
		'public' => true,
		'has_archive' => 'projects',
		'rewrite' => array( 'slug' => 'projects' ),
		'menu_icon' => 'dashicons-portfolio',
		'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
		'show_in_rest' => true,
	) );

	register_taxonomy( 'mrk_service_area', array( 'mrk_service' ), array(
		'labels' => array(
			'name' => __( 'Service areas', 'mrk-digital' ),
			'singular_name' => __( 'Service area', 'mrk-digital' ),
		),
		'public' => true,
		'hierarchical' => true,
		'rewrite' => array( 'slug' => 'service-area' ),
		'show_in_rest' => true,
	) );

	register_taxonomy( 'mrk_project_type', array( 'mrk_project' ), array(
		'labels' => array(
			'name' => __( 'Project types', 'mrk-digital' ),
			'singular_name' => __( 'Project type', 'mrk-digital' ),
		),
		'public' => true,
		'hierarchical' => true,
		'rewrite' => array( 'slug' => 'project-type' ),
		'show_in_rest' => true,
	) );
}
add_action( 'init', 'mrk_content_types' );

function mrk_customize( $wp_customize ) {
	$wp_customize->add_section( 'mrk_contact', array(
		'title' => __( 'MRK contact details', 'mrk-digital' ),
		'priority' => 30,
	) );

	foreach ( array(
		'whatsapp'       => 'WhatsApp number (international digits only)',
		'contact_email'  => 'Contact email',
		'service_area'   => 'Service area text',
		'public_address' => 'Optional public business address',
	) as $id => $label ) {
		$sanitize = 'contact_email' === $id
			? 'sanitize_email'
			: ( 'public_address' === $id ? 'sanitize_textarea_field' : 'sanitize_text_field' );

		$wp_customize->add_setting( 'mrk_' . $id, array(
			'sanitize_callback' => $sanitize,
		) );

		$wp_customize->add_control( 'mrk_' . $id, array(
			'label'   => __( $label, 'mrk-digital' ),
			'section' => 'mrk_contact',
			'type'    => 'contact_email' === $id ? 'email' : ( 'public_address' === $id ? 'textarea' : 'text' ),
		) );
	}
}
add_action( 'customize_register', 'mrk_customize' );

function mrk_whatsapp_url() {
	$number = preg_replace( '/\D+/', '', get_theme_mod( 'mrk_whatsapp' ) );
	return $number ? 'https://wa.me/' . $number : '';
}

function mrk_cta( $label = 'Get a Free Quote', $class = 'button button-primary' ) {
	$url = mrk_whatsapp_url();

	if ( $url ) {
		printf(
			'<a class="%1$s js-whatsapp" data-event="%2$s" href="%3$s" target="_blank" rel="noopener">%4$s<span aria-hidden="true"> ↗</span></a>',
			esc_attr( $class ),
			esc_attr( strtolower( str_replace( ' ', '-', $label ) ) ),
			esc_url( $url ),
			esc_html( $label )
		);
	} else {
		printf(
			'<a class="%1$s" href="%2$s">%3$s</a>',
			esc_attr( $class ),
			esc_url( home_url( '/contact/' ) ),
			esc_html( $label )
		);
	}
}

function mrk_meta() {
	if ( is_admin() ) {
		return;
	}

	$description = is_singular() ? get_post_meta( get_queried_object_id(), '_mrk_meta_description', true ) : '';
	if ( ! $description ) {
		$description = is_singular() ? get_the_excerpt() : get_bloginfo( 'description' );
	}
	if ( ! $description ) {
		$description = 'Web development, digital services, PLC, Arduino and automation solutions from MRK Digital.';
	}

	echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( $description ) ) . '">' . "\n";
	echo '<meta property="og:site_name" content="MRK Digital &amp; Online Services Center">' . "\n";
	echo '<meta property="og:type" content="' . ( is_singular( 'post' ) ? 'article' : 'website' ) . '">' . "\n";
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
}
add_action( 'wp_head', 'mrk_meta', 2 );

function mrk_schema() {
	$graph = array(
		array(
			'@type' => 'Organization',
			'name' => 'MRK Digital & Online Services Center',
			'url' => home_url( '/' ),
			'description' => 'Professional web, digital and automation solutions.',
		),
		array(
			'@type' => 'WebSite',
			'name' => 'MRK Digital',
			'url' => home_url( '/' ),
			'potentialAction' => array(
				'@type' => 'SearchAction',
				'target' => home_url( '/?s={search_term_string}' ),
				'query-input' => 'required name=search_term_string',
			),
		),
	);

	if ( is_singular() ) {
		$type = is_singular( 'post' ) ? 'Article' : ( is_singular( 'mrk_service' ) ? 'Service' : 'WebPage' );
		$item = array(
			'@type' => $type,
			'name' => get_the_title(),
			'url' => get_permalink(),
			'description' => wp_strip_all_tags( get_the_excerpt() ),
		);

		if ( 'Article' === $type ) {
			$item['headline'] = get_the_title();
			$item['datePublished'] = get_the_date( DATE_W3C );
			$item['dateModified'] = get_the_modified_date( DATE_W3C );
		}

		$graph[] = $item;
		$graph[] = array(
			'@type' => 'BreadcrumbList',
			'itemListElement' => array(
				array( '@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => home_url( '/' ) ),
				array( '@type' => 'ListItem', 'position' => 2, 'name' => get_the_title(), 'item' => get_permalink() ),
			),
		);
	}

	$schema = array(
		'@context' => 'https://schema.org',
		'@graph' => $graph,
	);

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>';
}
add_action( 'wp_head', 'mrk_schema', 30 );

function mrk_handle_quote() {
	if (
		! isset( $_POST['mrk_quote_nonce'] ) ||
		! wp_verify_nonce(
			sanitize_text_field( wp_unslash( $_POST['mrk_quote_nonce'] ) ),
			'mrk_quote'
		)
	) {
		wp_die( esc_html__( 'Security check failed.', 'mrk-digital' ) );
	}

	if ( ! empty( $_POST['website'] ) ) {
		wp_safe_redirect( add_query_arg( 'quote', 'sent', wp_get_referer() ?: home_url( '/contact/' ) ) );
		exit;
	}

	$name = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
	$contact = sanitize_text_field( wp_unslash( $_POST['contact'] ?? '' ) );
	$email = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$service = sanitize_text_field( wp_unslash( $_POST['service'] ?? '' ) );
	$budget = sanitize_text_field( wp_unslash( $_POST['budget'] ?? '' ) );
	$preferred_contact = sanitize_text_field( wp_unslash( $_POST['preferred_contact'] ?? '' ) );
	$description = sanitize_textarea_field( wp_unslash( $_POST['description'] ?? '' ) );

	if (
		! $name ||
		! $contact ||
		! $description ||
		( ! empty( $_POST['email'] ) && ! is_email( $email ) )
	) {
		wp_safe_redirect( add_query_arg( 'quote', 'invalid', wp_get_referer() ?: home_url( '/contact/' ) ) );
		exit;
	}

	$to = get_theme_mod( 'mrk_contact_email' ) ?: get_option( 'admin_email' );
	$subject = sprintf( '[MRK Quote] %s — %s', $service ?: 'General enquiry', $name );
	$body = "Name: $name\nContact: $contact\nEmail: $email\nService: $service\nBudget: $budget\nPreferred contact: $preferred_contact\n\nProject: $description";

	wp_mail( $to, $subject, $body );
	wp_safe_redirect( add_query_arg( 'quote', 'sent', wp_get_referer() ?: home_url( '/contact/' ) ) );
	exit;
}
add_action( 'admin_post_nopriv_mrk_quote', 'mrk_handle_quote' );
add_action( 'admin_post_mrk_quote', 'mrk_handle_quote' );

require_once get_template_directory() . '/inc/template-tags.php';
require_once get_template_directory() . '/inc/content-blueprints.php';
