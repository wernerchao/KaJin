<?php
/**
 * Sidebar
 * @package Heinrich
 * since heinrich 1.0
 */
?>

<div id="sidebar">
<?php if ( ! dynamic_sidebar( 'sidebar' ) ) : ?>
<aside class="box widget widget_recent_entries" role="complementary">
	<?php the_widget( 'WP_Widget_Recent_Posts' ); ?>
</aside><!-- .box -->

<aside class="box widget widget_meta" role="complementary">
	<?php the_widget( 'WP_Widget_Meta' ); ?>
</aside><!-- .box -->

<?php endif; // end sidebar widget area ?>
</div><!-- #sidebar -->