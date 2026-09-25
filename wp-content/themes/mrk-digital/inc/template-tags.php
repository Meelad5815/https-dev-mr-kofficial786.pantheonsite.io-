<?php
/** Reusable display helpers for MRK Digital. */

function mrk_default_menu() {
	$items = array(
		'Home'     => home_url( '/' ),
		'Services' => get_post_type_archive_link( 'mrk_service' ),
		'Projects' => get_post_type_archive_link( 'mrk_project' ),
		'Blog'     => get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' ),
		'About'    => home_url( '/about/' ),
		'Contact'  => home_url( '/contact/' ),
	);
	echo '<ul>';
	foreach ( $items as $label => $url ) {
		printf( '<li><a href="%1$s">%2$s</a></li>', esc_url( $url ), esc_html( $label ) );
	}
	echo '</ul>';
}

function mrk_breadcrumbs() {
	if ( is_front_page() ) { return; }
	$title = is_post_type_archive() ? post_type_archive_title( '', false ) : wp_get_document_title();
	printf( '<nav class="breadcrumbs shell" aria-label="%1$s"><a href="%2$s">%3$s</a><span aria-hidden="true"> / </span><span aria-current="page">%4$s</span></nav>', esc_attr__( 'Breadcrumb', 'mrk-digital' ), esc_url( home_url( '/' ) ), esc_html__( 'Home', 'mrk-digital' ), esc_html( $title ) );
}

function mrk_quote_form( $service = '' ) {
	?>
	<form class="quote-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
		<input type="hidden" name="action" value="mrk_quote">
		<?php wp_nonce_field( 'mrk_quote', 'mrk_quote_nonce' ); ?>
		<p class="honeypot" aria-hidden="true"><label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label></p>
		<div class="form-grid">
			<label><?php esc_html_e( 'Name*', 'mrk-digital' ); ?><input required name="name" autocomplete="name"></label>
			<label><?php esc_html_e( 'WhatsApp / contact*', 'mrk-digital' ); ?><input required name="contact" autocomplete="tel"></label>
			<label><?php esc_html_e( 'Email', 'mrk-digital' ); ?><input type="email" name="email" autocomplete="email"></label>
			<label><?php esc_html_e( 'Required service', 'mrk-digital' ); ?><select name="service"><option value=""><?php esc_html_e( 'Select a service', 'mrk-digital' ); ?></option><?php foreach ( mrk_service_options() as $option ) { printf( '<option value="%1$s"%2$s>%1$s</option>', esc_attr( $option ), selected( $service, $option, false ) ); } ?></select></label>
			<label><?php esc_html_e( 'Budget range', 'mrk-digital' ); ?><select name="budget"><option value="">Not sure yet</option><option>Under PKR 25,000</option><option>PKR 25,000–75,000</option><option>PKR 75,000–200,000</option><option>Above PKR 200,000</option></select></label>
			<label><?php esc_html_e( 'Preferred contact method', 'mrk-digital' ); ?><select name="preferred_contact"><option>WhatsApp</option><option>Phone</option><option>Email</option></select></label>
			<label class="wide"><?php esc_html_e( 'Project description*', 'mrk-digital' ); ?><textarea required name="description" rows="5" placeholder="Tell us about the problem, goals and useful project details."></textarea></label>
		</div>
		<button class="button button-primary" type="submit" data-event="quote_submit"><?php esc_html_e( 'Send quote request', 'mrk-digital' ); ?></button>
		<p class="form-note"><?php esc_html_e( 'We use these details only to respond to your enquiry.', 'mrk-digital' ); ?></p>
	</form>
	<?php
}

function mrk_service_options() {
	return array( 'Web development', 'App & software', 'Digital services', 'PLC & automation', 'Arduino / ESP32 / IoT', 'IT services' );
}

function mrk_contact_channels() {
	$email   = get_theme_mod( 'mrk_contact_email' );
	$address = get_theme_mod( 'mrk_public_address' );
	$area    = get_theme_mod( 'mrk_service_area' );
	if ( ! $email && ! $address && ! $area && ! mrk_whatsapp_url() ) { return; }
	echo '<section class="contact-details" aria-labelledby="contact-details-heading"><h2 id="contact-details-heading">Contact details</h2>';
	if ( mrk_whatsapp_url() ) { mrk_cta( 'WhatsApp MRK', 'button button-quiet' ); }
	if ( $email ) { printf( '<p><strong>Email:</strong> <a href="mailto:%1$s">%2$s</a></p>', esc_attr( antispambot( $email ) ), esc_html( antispambot( $email ) ) ); }
	if ( $area ) { printf( '<p><strong>Service areas:</strong> %s</p>', esc_html( $area ) ); }
	if ( $address ) { printf( '<p><strong>Public address:</strong> %s</p>', nl2br( esc_html( $address ) ) ); }
	echo '</section>';
}

function mrk_related_content( $post_type, $heading ) {
	$query = new WP_Query( array( 'post_type' => $post_type, 'posts_per_page' => 3, 'post__not_in' => array( get_the_ID() ), 'no_found_rows' => true ) );
	if ( ! $query->have_posts() ) { return; }
	echo '<section class="related-content"><h2>' . esc_html( $heading ) . '</h2><ul>';
	while ( $query->have_posts() ) { $query->the_post(); printf( '<li><a href="%1$s">%2$s</a></li>', esc_url( get_permalink() ), esc_html( get_the_title() ) ); }
	echo '</ul></section>';
	wp_reset_postdata();
}
function mrk_default_menu() { echo '<ul><li><a href="' . esc_url(home_url('/')) . '">Home</a></li><li><a href="' . esc_url(home_url('/services/')) . '">Services</a></li><li><a href="' . esc_url(home_url('/projects/')) . '">Projects</a></li><li><a href="' . esc_url(home_url('/blog/')) . '">Blog</a></li><li><a href="' . esc_url(home_url('/about/')) . '">About</a></li><li><a href="' . esc_url(home_url('/contact/')) . '">Contact</a></li></ul>'; }
function mrk_breadcrumbs() { if ( is_front_page() ) return; echo '<nav class="breadcrumbs shell" aria-label="Breadcrumb"><a href="' . esc_url(home_url('/')) . '">Home</a><span> / </span><span>' . esc_html(wp_get_document_title()) . '</span></nav>'; }
function mrk_quote_form( $service = '' ) { ?><form class="quote-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post"><input type="hidden" name="action" value="mrk_quote"><?php wp_nonce_field('mrk_quote','mrk_quote_nonce'); ?><div class="form-grid"><label>Name*<input required name="name" autocomplete="name"></label><label>WhatsApp / Contact*<input required name="contact" autocomplete="tel"></label><label>Email<input type="email" name="email" autocomplete="email"></label><label>Required service<select name="service"><option value="">Select a service</option><?php foreach(array('Web development','Digital services','PLC programming','Arduino / IoT','Industrial automation','IT support') as $option) printf('<option%s>%s</option>', selected($service,$option,false),esc_html($option)); ?></select></label><label class="wide">Project description*<textarea required name="description" rows="5" placeholder="Tell us about the problem, goals and useful project details."></textarea></label></div><button class="button button-primary" type="submit">Send quote request</button><p class="form-note">We use these details only to respond to your enquiry.</p></form><?php }
