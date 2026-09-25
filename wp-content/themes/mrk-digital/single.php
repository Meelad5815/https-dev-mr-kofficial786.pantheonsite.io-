<?php get_header(); mrk_breadcrumbs(); ?>
<main id="content" class="shell section article">
	<article>
		<?php while ( have_posts() ) : the_post(); ?>
			<header>
				<p class="eyebrow"><?php echo esc_html( get_the_date() ); ?></p>
				<h1><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?><p class="lead"><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
			</header>
			<?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'large', array( 'class' => 'featured-image' ) ); endif; ?>
			<div class="entry-content"><?php the_content(); ?></div>
			<aside class="article-cta">
				<h2>Need help with this topic?</h2>
				<p>Share your requirement with MRK to discuss a practical next step.</p>
				<?php mrk_cta( 'Get in Touch' ); ?>
			</aside>
		<?php endwhile; ?>
	</article>
</main>
<?php get_footer(); ?>
