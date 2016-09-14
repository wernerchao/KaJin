<?php
/**
 * Content None
 * @package Heinrich
 * since heinrich 1.0
 */
?>


<article class="box article no-content">
	<header class="entry-header">
		<h1 class="entry-title"><?php _e( 'Oops, nothing found', 'heinrich' ); ?></h1>
	</header><!-- .entry-header -->


	<div class="entry-content">
	<?php if ( is_search() ) : ?>

		<p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with a different keyword.', 'heinrich' ); ?></p>

	<?php else : ?>

		<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'heinrich' ); ?></p>

	<?php endif; ?>

	</div><!-- .entry-content -->
</article><!-- .box.article -->