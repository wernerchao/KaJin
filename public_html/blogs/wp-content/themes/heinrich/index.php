<?php
/**
 * The template for displaying all posts
 * @package Heinrich
 * since heinrich 1.0
 */

get_header(); ?>

<?php if ( !is_home() ) : ?>
	<?php get_template_part( 'pagetitle' ); ?>
<?php endif; ?>

<?php if (have_posts()) : ?>
	<?php $post = $posts[0]; $c=0;?>
	<?php while (have_posts()) : the_post(); ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class( 'box article' ); ?>>

	<?php $c++; ?>
		<?php if( $c==1 && has_post_thumbnail() ) : ?>
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail( 'big-thumbnail' ); ?></a>

		<?php else :?>

			<?php if ( has_post_thumbnail() ) : ?>
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail( 'small-thumbnail' ); ?></a>
			<?php endif; ?>

		<?php endif; ?>
		
	<?php get_template_part( 'content', get_post_format() ); ?>
	</article><!-- .box.article -->

	<?php endwhile; ?>
<?php endif; ?>


<!-- Include the "No posts found" template if there is no content. -->
<?php if ( !have_posts() ) : ?>
	<?php get_template_part( 'content', 'none' ); ?>
<?php endif; ?>


<?php if (heinrich_show_posts_nav()) : ?>
	<nav id="navigation-page" class="box navigation" role="navigation">
		<?php next_posts_link(); ?>
		<?php previous_posts_link(); ?>
	</nav><!-- #navigation-page.box.navigation -->
<?php endif; ?>


<?php get_sidebar(); ?>
</div><!-- #main .site-main -->


<?php get_footer(); ?>