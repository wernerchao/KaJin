<?php
/**
 * The template for displaying all pages
 * @package Heinrich
 * since heinrich 1.0
 */

get_header(); ?>


<?php while ( have_posts() ) : the_post(); ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class( 'box article' ); ?>>

		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'big-thumbnail' ); ?>
		<?php endif; ?>

		<header class="entry-header">
			<h1 class="entry-title"><?php the_title(); ?></h1>
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php the_content(); ?>
			<?php wp_link_pages(); ?>
		</div><!-- .entry-content -->

	</article><!-- .box.article -->

		<?php if ( comments_open() || get_comments_number() ) : ?>
			<?php comments_template(); ?>
		<?php endif; ?>

	<?php endwhile; ?>

	<?php get_sidebar(); ?>
	</div><!-- #main .site-main -->


<?php get_footer(); ?>