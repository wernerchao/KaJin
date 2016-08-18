<?php
/**
 * Content
 * @package Heinrich
 * since heinrich 1.0
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


<div class="entry-content">
	<?php if ( !is_single() ) : ?>
		<?php the_excerpt(); ?>
		<a href="<?php the_permalink(); ?>" class="read-more"><?php _e( 'Read more', 'heinrich' ); ?><span class="icon icon-arrow-right2"></span></a>

	<?php else : ?>

		<?php the_content(); ?>
		<?php wp_link_pages(); ?>

	<?php endif; ?>
</div><!-- .entry-content -->


<?php get_template_part( 'entry-meta' ); ?>