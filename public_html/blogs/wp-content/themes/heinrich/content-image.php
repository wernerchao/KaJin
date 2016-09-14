<?php
/**
 * Content Image
 * @package Heinrich
 * since Heinrich 1.0
 */
?>


<header class="entry-header">

	<?php if ( !is_single() ) : ?>
		<h2 class="entry-title">
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark"><?php the_title(); ?></a>
		</h2>
	<?php elseif ( is_single() ) : ?>
		<h1 class="entry-title"><?php the_title(); ?></h1>
	<?php endif; ?>
</header><!-- .entry-header -->


<?php if ( is_single() ) : ?>
	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages(); ?>
	</div><!-- .entry-content -->
<?php endif; ?>


<?php get_template_part( 'entry-meta' ); ?>