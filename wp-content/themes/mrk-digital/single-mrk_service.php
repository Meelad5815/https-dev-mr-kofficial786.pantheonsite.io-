<?php get_header(); mrk_breadcrumbs(); ?>
<main id="content" class="shell section article">
	<?php while ( have_posts() ) : the_post(); ?>
		<article>
			<header>
				<p class="eyebrow">MRK service</p><h1><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?><p class="lead"><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
				<div class="hero-actions"><?php mrk_cta( 'Request a Quote' ); ?><?php if ( mrk_whatsapp_url() ) { mrk_cta( 'WhatsApp MRK', 'button button-quiet' ); } ?></div>
			</header>
			<?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'large', array( 'class' => 'featured-image', 'loading' => 'lazy' ) ); } ?>
			<div class="entry-content"><?php the_content(); ?></div>
			<section class="service-framework" aria-labelledby="service-approach"><h2 id="service-approach">A clear service approach</h2><div class="three-up"><div><h3>Problem</h3><p>Start by defining the practical issue, existing setup and intended outcome.</p></div><div><h3>Solution</h3><p>MRK confirms an appropriate route only after reviewing the real requirement.</p></div><div><h3>Process</h3><p>Agree scope, communication and deliverables before work starts.</p></div></div><h3>What can be included</h3><p>Features, tools, implementation steps and documentation depend on the approved scope. Publish exact inclusions in this service page’s main content so clients can make an informed enquiry.</p><h3>Suitable customers</h3><p>Individuals, businesses and practical technical projects whose requirements match the service’s confirmed availability.</p></section>
			<?php mrk_related_content( 'mrk_project', 'Related projects' ); mrk_related_content( 'post', 'Related guides' ); ?>
			<section class="service-faq"><h2>Frequently asked questions</h2><details><summary>How is the right solution confirmed?</summary><p>MRK reviews the requirement, relevant constraints and the expected outcome before confirming scope.</p></details><details><summary>What should I include in my enquiry?</summary><p>Share the current problem, desired result, relevant platform or hardware, and any deadlines or constraints.</p></details></section>
			<section class="service-inquiry"><div><p class="eyebrow">Need this service?</p><h2>Discuss your requirements</h2><p>Share the problem you need solved, relevant systems or platforms, and the outcome you are working toward.</p></div><?php mrk_quote_form( get_the_title() ); ?></section>
		</article>
	<?php endwhile; ?>
</main>
<?php get_footer(); ?>
