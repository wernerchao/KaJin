<?php
/**
 * Entry Meta
 * @package Heinrich
 * since heinrich 1.0
 */
?>

<footer class="entry-meta">

	<ul class="meta">

		<li><a href="<?php the_permalink(); ?>">
		<?php the_time( get_option( 'date_format' ) ); ?>
		</a></li><!-- Datumsformat im Admin einstellen -->
		<li><?php the_category( ', ' ) ?></li>
		<?php edit_post_link( __( 'Edit Post', 'heinrich' ), '<li>', '</li>' ); ?>

	</ul><!-- .meta -->


	<?php if( is_single() && has_tag() ) : ?>
		<?php the_tags( '<ul class="meta-tags"><li>', '</li><li>', '</li></ul>' ); ?>
	<?php endif; ?>

</footer><!-- .entry-meta -->