<?php
/**
 * The template for displaying all single posts
 * @package Heinrich
 * since heinrich 1.0
 */

get_header(); ?>


<?php while ( have_posts() ) : the_post(); ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class( 'box article' ); ?>>

		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'big-thumbnail' ); ?>
		<?php endif; ?>
		
		<?php get_template_part( 'content', get_post_format() ); ?>

	</article><!-- .box.article -->
	
	<?php if ( comments_open() || get_comments_number() ) : ?>
		<?php comments_template(); ?>
	<?php endif; ?>

	<nav id="navigation-post" class="box navigation" role="navigation">
		<?php next_post_link('%link', '<span>' . __( 'Next Post', 'heinrich' ) . '</span> %title', FALSE); ?>
		<?php previous_post_link('%link', '<span>' . __( 'Previous Post', 'heinrich' ) . '</span> %title', FALSE); ?>
	</nav><!-- #navigation-post -->

<?php endwhile; ?>

<?php get_sidebar(); ?>
</div><!-- #main .site-main -->


<?php get_footer(); ?>