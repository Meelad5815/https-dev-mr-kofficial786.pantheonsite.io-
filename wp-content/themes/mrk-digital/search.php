<?php get_header(); ?>
<main id="content">
<section class="section shell">
<div class="section-heading">
<p class="eyebrow"><?php esc_html_e('Search','mrk-digital'); ?></p>
<h1><?php printf( esc_html__('Search results for: %s','mrk-digital'), esc_html(get_search_query()) ); ?></h1>
</div>
<?php if ( have_posts() ) : ?>
<div class="card-grid posts-grid">
<?php while ( have_posts() ) : the_post(); ?>
<article class="post-card">
<p class="eyebrow"><?php echo esc_html(get_the_date()); ?></p>
<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
<p><?php echo esc_html(wp_trim_words(get_the_excerpt(),22)); ?></p>
<a href="<?php the_permalink(); ?>"><?php esc_html_e('Read more →','mrk-digital'); ?></a>
</article>
<?php endwhile; ?>
</div>
<?php the_posts_pagination(); ?>
<?php else : ?>
<article class="project-empty">
<h2><?php esc_html_e('No matching results found.','mrk-digital'); ?></h2>
<p><?php esc_html_e('Try a different search phrase or contact MRK to discuss your requirement.','mrk-digital'); ?></p>
<a class="button button-primary" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Contact MRK','mrk-digital'); ?></a>
</article>
<?php endif; ?>
</section>
</main>
<?php get_footer(); ?>
