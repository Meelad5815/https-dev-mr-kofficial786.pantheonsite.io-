<?php
/** Owner-triggered draft content setup. Nothing is published automatically. */

function mrk_service_catalog() {
	return array(
		'Web Development' => array( 'Website Development', 'WordPress Development', 'Shopify Development', 'E-commerce Development', 'Web Application Development', 'Python / Django Development', 'Business Website Development' ),
		'App & Software' => array( 'App Development', 'Custom Software', 'Database Applications' ),
		'Digital Services' => array( 'Graphic Design', 'Canva Design', 'CV / Resume Design', 'PDF / Word / Excel Services', 'Online Forms', 'Digital Documentation', 'Computer Services' ),
		'PLC & Automation' => array( 'PLC Programming', 'PLC Troubleshooting', 'Industrial Automation', 'Motor Automation', 'Control Systems', 'Sensor Systems', 'Control Panels' ),
		'Arduino / ESP32 / IoT' => array( 'Arduino Projects', 'ESP32 Projects', 'IoT Projects', 'Sensor Automation', 'Smart Automation', 'Automatic Water Tank Controller', 'Custom Electronics Projects' ),
		'IT Services' => array( 'Windows / PC Support', 'Software Installation', 'Technical Troubleshooting', 'Basic Networking', 'Software Solutions' ),
	);
}

function mrk_draft_service_content( $title ) {
	return sprintf(
		'<p><strong>Owner review required before publishing.</strong> Confirm that MRK currently provides %1$s, then replace this note with a factual short introduction.</p><h2>Problem</h2><p>Describe the specific problem this service addresses for the intended customer.</p><h2>Practical approach</h2><p>Describe the approved solution and boundaries without making promises that cannot be verified.</p><h2>Features and included work</h2><p>List only the features, deliverables, tools and documentation that MRK can genuinely provide.</p><h2>Process</h2><ol><li>Review the requirement.</li><li>Confirm a suitable scope and quotation.</li><li>Complete implementation, testing and delivery as agreed.</li></ol><h2>Who this is for</h2><p>Describe the customers or practical projects for which this confirmed service is suitable.</p><h2>Frequently asked questions</h2><h3>What should I include in an enquiry?</h3><p>Share the current requirement, relevant system or platform, desired outcome and constraints.</p>',
		esc_html( $title )
	);
}

function mrk_register_content_setup_page() {
	add_management_page( __( 'MRK Content Setup', 'mrk-digital' ), __( 'MRK Content Setup', 'mrk-digital' ), 'manage_options', 'mrk-content-setup', 'mrk_render_content_setup_page' );
}
add_action( 'admin_menu', 'mrk_register_content_setup_page' );

function mrk_render_content_setup_page() {
	if ( ! current_user_can( 'manage_options' ) ) { return; }
	$notice = '';
	if ( isset( $_POST['mrk_content_action'] ) ) {
		check_admin_referer( 'mrk_create_content_drafts' );
		$action = sanitize_key( wp_unslash( $_POST['mrk_content_action'] ) );
		$created = 'services' === $action ? mrk_create_service_drafts() : ( 'project' === $action ? mrk_create_project_draft() : ( 'articles' === $action ? mrk_create_article_drafts() : ( 'legal' === $action ? mrk_create_legal_page_drafts() : array() ) ) );
		$notice = sprintf( _n( 'Created %d new draft. Existing matching content was left unchanged.', 'Created %d new drafts. Existing matching content was left unchanged.', count( $created ), 'mrk-digital' ), count( $created ) );
	}
	?>
	<div class="wrap"><h1><?php esc_html_e( 'MRK draft content setup', 'mrk-digital' ); ?></h1>
	<p><?php esc_html_e( 'This optional action creates editable WordPress drafts only. It never publishes services, projects or articles, and it does not alter existing posts, pages or media.', 'mrk-digital' ); ?></p>
	<?php if ( $notice ) : ?><div class="notice notice-success"><p><?php echo esc_html( $notice ); ?></p></div><?php endif; ?>
	<h2><?php esc_html_e( '1. Service drafts', 'mrk-digital' ); ?></h2><p><?php esc_html_e( 'Creates only the 36 service drafts and their Service Area terms. Every item remains DRAFT — OWNER VERIFICATION REQUIRED.', 'mrk-digital' ); ?></p>
	<form method="post"><?php wp_nonce_field( 'mrk_create_content_drafts' ); ?><button class="button button-primary" type="submit" name="mrk_content_action" value="services"><?php esc_html_e( 'Create missing service drafts', 'mrk-digital' ); ?></button></form>
	<h2><?php esc_html_e( '2. Optional project draft', 'mrk-digital' ); ?></h2><p><?php esc_html_e( 'Creates only the clearly labelled Automatic Water Tank Controller project draft. Confirm all hardware and implemented features before publishing.', 'mrk-digital' ); ?></p>
	<form method="post"><?php wp_nonce_field( 'mrk_create_content_drafts' ); ?><button class="button" type="submit" name="mrk_content_action" value="project"><?php esc_html_e( 'Create Water Tank Controller draft', 'mrk-digital' ); ?></button></form>
	<h2><?php esc_html_e( '3. Optional article drafts', 'mrk-digital' ); ?></h2><p><?php esc_html_e( 'Creates the first five owner-review article drafts and missing categories. Review technical wording and add internal links only after related content is published.', 'mrk-digital' ); ?></p>
	<form method="post"><?php wp_nonce_field( 'mrk_create_content_drafts' ); ?><button class="button" type="submit" name="mrk_content_action" value="articles"><?php esc_html_e( 'Create first five article drafts', 'mrk-digital' ); ?></button></form>
	<h2><?php esc_html_e( '4. Optional legal and trust page drafts', 'mrk-digital' ); ?></h2><p><?php esc_html_e( 'Creates About, Contact, Privacy Policy, Terms & Conditions, Disclaimer and Affiliate Disclosure as drafts only when absent.', 'mrk-digital' ); ?></p>
	<form method="post"><?php wp_nonce_field( 'mrk_create_content_drafts' ); ?><button class="button" type="submit" name="mrk_content_action" value="legal"><?php esc_html_e( 'Create legal and trust page drafts', 'mrk-digital' ); ?></button></form></div>
	<?php
}

function mrk_create_service_drafts() {
	$created = array();
	foreach ( mrk_service_catalog() as $area => $services ) {
		$term = term_exists( $area, 'mrk_service_area' );
		if ( ! $term ) { $term = wp_insert_term( $area, 'mrk_service_area' ); }
		$term_id = is_array( $term ) ? $term['term_id'] : $term;
		foreach ( $services as $service ) {
			$slug = sanitize_title( $service );
			if ( get_page_by_path( $slug, OBJECT, 'mrk_service' ) ) { continue; }
			$post_id = wp_insert_post( array( 'post_type' => 'mrk_service', 'post_status' => 'draft', 'post_title' => $service, 'post_excerpt' => sprintf( 'Owner review required: confirm MRK availability for %s before publishing.', $service ), 'post_content' => mrk_draft_service_content( $service ) ) );
			if ( $post_id && ! is_wp_error( $post_id ) ) { wp_set_object_terms( $post_id, (int) $term_id, 'mrk_service_area' ); update_post_meta( $post_id, '_mrk_owner_verification_required', '1' ); update_post_meta( $post_id, '_mrk_meta_title', $service . ' | MRK Digital' ); update_post_meta( $post_id, '_mrk_meta_description', sprintf( 'Ask MRK Digital about %s after confirmed scope and availability.', $service ) ); $created[] = $post_id; }
		}
	}
	return $created;
}

function mrk_create_project_draft() {
	$created = array();
	$project_slug = 'automatic-water-tank-controller';
	if ( ! get_page_by_path( $project_slug, OBJECT, 'mrk_project' ) ) {
		$project_id = wp_insert_post( array( 'post_type' => 'mrk_project', 'post_status' => 'draft', 'post_title' => 'Automatic Water Tank Controller', 'post_excerpt' => 'Draft technical project outline pending owner verification of the actual build and implemented features.', 'post_content' => mrk_water_tank_project_content() ) );
		if ( $project_id && ! is_wp_error( $project_id ) ) { foreach ( array( 'Arduino', 'Automation' ) as $type ) { if ( ! term_exists( $type, 'mrk_project_type' ) ) { wp_insert_term( $type, 'mrk_project_type' ); } } wp_set_object_terms( $project_id, array( 'Arduino', 'Automation' ), 'mrk_project_type' ); update_post_meta( $project_id, '_mrk_owner_verification_required', '1' ); $created[] = $project_id; }
	}
	return $created;
}

function mrk_water_tank_project_content() {
	return '<p><strong>Owner review required before publishing.</strong> This is a technical project draft, not a claim of a client installation or production deployment. Confirm the actual hardware, wiring, software and implemented safety features before publication.</p><h2>Overview</h2><p>This draft describes a potential water tank control system using water-level sensors, an Arduino or ESP32 controller, a relay and a motor/pump.</p><h2>Problem</h2><p>Document the specific water-management problem the actual prototype or project was designed to address.</p><h2>System architecture</h2><p>Water level sensors → Arduino/ESP32 → relay → motor/pump.</p><h2>Components</h2><p>List the verified controller board, sensor type, relay rating, power supply and any enclosure or protection components used in the actual build.</p><h2>Working principle and control logic</h2><p>Explain the verified sensor states and relay-control logic. Do not publish dry-run protection, alarms or fault detection unless they are implemented and tested.</p><h2>Implemented features</h2><p>Confirm which of automatic motor on/off, water-level detection, overflow protection, manual control and status indication are present in the actual project.</p><h2>Safety considerations</h2><p>Document electrical isolation, relay/pump ratings, safe enclosure practices and qualified electrical review where relevant. This page must not replace professional electrical safety advice.</p><h2>Testing</h2><p>Record only tests actually performed, with real conditions and limitations. Add authentic images when available; until then, retain this clearly labelled draft state.</p>';
}

function mrk_create_article_drafts() {
	$created = array();
	$articles = array(
		'What Is PLC Programming?' => 'PLC',
		'PLC vs Arduino: What Is the Difference?' => 'PLC',
		'What Is Arduino and How Does It Work?' => 'Arduino',
		'Automatic Water Tank Controller Using Arduino' => 'Arduino',
		'How to Build a Professional Business Website' => 'Web Development',
	);
	foreach ( array( 'Web Development', 'WordPress', 'Shopify', 'PLC', 'Arduino', 'Automation', 'IoT', 'Digital Services', 'Freelancing', 'Computer & IT', 'Tutorials', 'Technology' ) as $category ) { if ( ! term_exists( $category, 'category' ) ) { wp_insert_term( $category, 'category' ); } }
	foreach ( $articles as $title => $category ) {
		if ( get_page_by_path( sanitize_title( $title ), OBJECT, 'post' ) ) { continue; }
		$post_id = wp_insert_post( array( 'post_type' => 'post', 'post_status' => 'draft', 'post_title' => $title, 'post_excerpt' => 'Owner-review educational draft. Verify technical statements, add authentic examples and link only to published MRK content.', 'post_content' => mrk_draft_article_content( $title ) ) );
		if ( $post_id && ! is_wp_error( $post_id ) ) { wp_set_post_categories( $post_id, array( (int) get_cat_ID( $category ) ) ); update_post_meta( $post_id, '_mrk_owner_verification_required', '1' ); $created[] = $post_id; }
	}
	return $created;
}

function mrk_draft_article_content( $title ) {
	return sprintf( '<p><strong>Draft — owner verification required.</strong> This educational article must be reviewed for accuracy, original examples and current technical context before publication.</p><h2>Direct answer</h2><p>Add a concise, factual answer to “%1$s” here.</p><h2>Explanation</h2><p>Explain the core concept in plain language and distinguish general educational information from any verified MRK project.</p><h2>Practical considerations</h2><p>Add appropriate examples, steps, compatibility considerations and safety notes where relevant.</p><h2>Common mistakes</h2><p>Document mistakes that are genuinely relevant to this topic and explain how to avoid them.</p><h2>Frequently asked questions</h2><h3>What should a reader consider next?</h3><p>Answer with helpful, non-promotional guidance.</p><h2>Related MRK help</h2><p>Add links only after the related confirmed service or project has been published.</p>', esc_html( $title ) );
}

function mrk_create_legal_page_drafts() {
	$created = array();
	$pages = array( 'About' => 'Describe MRK Digital & Online Services Center using owner-verified background, service availability and approach. Do not claim qualifications, clients or results that cannot be verified.', 'Contact' => 'Add owner-approved contact instructions. Theme contact fields and the quote form are displayed when this page is published.', 'Privacy Policy' => 'Owner/legal review required. Explain actual data collection, form processing, analytics and retention practices.', 'Terms & Conditions' => 'Owner/legal review required. State only actual service, payment, delivery and support conditions.', 'Disclaimer' => 'Owner/legal review required. Add only applicable limitations and avoid unsupported legal claims.', 'Affiliate Disclosure' => 'Owner/legal review required. Publish before using any affiliate links and disclose only actual affiliate relationships.' );
	foreach ( $pages as $title => $guidance ) { if ( get_page_by_path( sanitize_title( $title ), OBJECT, 'page' ) ) { continue; } $post_id = wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'draft', 'post_title' => $title, 'post_content' => '<p><strong>Draft — owner review required before publishing.</strong></p><p>' . esc_html( $guidance ) . '</p>' ) ); if ( $post_id && ! is_wp_error( $post_id ) ) { update_post_meta( $post_id, '_mrk_owner_verification_required', '1' ); $created[] = $post_id; } }
	return $created;
}

function mrk_owner_verification_state( $states, $post ) {
	$draft_content_type = in_array( $post->post_type, array( 'mrk_service', 'mrk_project' ), true ) && 'draft' === $post->post_status;
	if ( $draft_content_type || get_post_meta( $post->ID, '_mrk_owner_verification_required', true ) ) { $states[] = __( 'OWNER VERIFICATION REQUIRED', 'mrk-digital' ); }
	return $states;
}
add_filter( 'display_post_states', 'mrk_owner_verification_state', 10, 2 );

function mrk_add_seo_meta_box() {
	foreach ( array( 'post', 'page', 'mrk_service', 'mrk_project' ) as $screen ) {
		add_meta_box( 'mrk-seo-details', __( 'MRK SEO details', 'mrk-digital' ), 'mrk_render_seo_meta_box', $screen, 'normal', 'default' );
	}
}
add_action( 'add_meta_boxes', 'mrk_add_seo_meta_box' );

function mrk_render_seo_meta_box( $post ) {
	wp_nonce_field( 'mrk_save_seo_details', 'mrk_seo_nonce' );
	$title       = get_post_meta( $post->ID, '_mrk_meta_title', true );
	$description = get_post_meta( $post->ID, '_mrk_meta_description', true );
	?>
	<p><label for="mrk-meta-title"><strong><?php esc_html_e( 'SEO title', 'mrk-digital' ); ?></strong></label><input class="widefat" id="mrk-meta-title" name="mrk_meta_title" value="<?php echo esc_attr( $title ); ?>" maxlength="65"></p>
	<p><label for="mrk-meta-description"><strong><?php esc_html_e( 'Meta description', 'mrk-digital' ); ?></strong></label><textarea class="widefat" id="mrk-meta-description" name="mrk_meta_description" rows="3" maxlength="170"><?php echo esc_textarea( $description ); ?></textarea></p>
	<p class="description"><?php esc_html_e( 'Use factual, page-specific wording. Leave title blank to use the normal WordPress title.', 'mrk-digital' ); ?></p>
	<?php
}

function mrk_save_seo_meta_box( $post_id ) {
	if ( ! isset( $_POST['mrk_seo_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mrk_seo_nonce'] ) ), 'mrk_save_seo_details' ) || defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE || ! current_user_can( 'edit_post', $post_id ) ) { return; }
	foreach ( array( 'mrk_meta_title' => '_mrk_meta_title', 'mrk_meta_description' => '_mrk_meta_description' ) as $field => $key ) {
		if ( isset( $_POST[ $field ] ) ) { update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) ); }
	}
}
add_action( 'save_post', 'mrk_save_seo_meta_box' );

function mrk_document_title( $parts ) {
	if ( is_singular() ) { $custom = get_post_meta( get_queried_object_id(), '_mrk_meta_title', true ); if ( $custom ) { $parts['title'] = $custom; } }
	return $parts;
}
add_filter( 'document_title_parts', 'mrk_document_title' );
