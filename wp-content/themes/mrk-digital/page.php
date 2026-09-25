<?php
get_header();
mrk_breadcrumbs();
?>
<main id="content" class="shell section article">
	<article>
		<?php while ( have_posts() ) : the_post(); ?>
			<h1><?php the_title(); ?></h1>
			<div class="entry-content"><?php the_content(); ?></div>
			<?php if ( is_page( 'contact' ) ) : ?>
				<?php mrk_contact_channels(); ?>
				<?php mrk_quote_notice(); ?>
				<?php mrk_quote_form(); ?>
			<?php endif; ?>
		<?php endwhile; ?>
	</article>
</main>
<?php get_footer(); ?>
