<?php
/**
 * Content Status
 * @package Heinrich
 * since heinrich 1.0
 */
?>


<div class="entry-content">
	<?php if ( !is_single() ) : ?>

		<?php the_excerpt(); ?>

	<?php else : ?>

		<?php the_content(); ?>

	<?php endif; ?>
</div><!-- .entry-content -->

<?php get_template_part( 'entry-meta' ); ?>