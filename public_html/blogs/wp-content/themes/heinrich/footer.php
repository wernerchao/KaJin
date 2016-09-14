<?php
/**
 * Footer: Social Link Menu, Footer Widget Area and Site Info
 *
 * @package Heinrich
 * @since Heinrich 1.0
 */
?>

<footer id="site-footer">

	<?php if ( has_nav_menu( 'secondary' ) ) : ?>
		<nav id="sociallinks-menu" class="sociallinks" role="navigation">

			<?php
			$heinrich_menu_locations = get_nav_menu_locations();
			$heinrich_menu = get_term_by( 'id', $heinrich_menu_locations[ 'secondary' ], 'nav_menu', ARRAY_A ); ?>
	
			<h2><?php echo $heinrich_menu[ 'name' ];?></h2>

			<?php
			// Social Links Menu.
			wp_nav_menu( array(
			'theme_location' => 'secondary',
			'container' => false,
			'depth'          => -1
			) ); ?>

		</nav><!-- #sociallinks-menu.sociallinks-area -->
	<?php endif; ?>


	<?php if ( is_active_sidebar( 'footer' ) ) : ?>
		<section id="footer-widgets" class="widget-area">
			<div class="footer-widgets-wrapper">
				<?php dynamic_sidebar( 'footer' ); ?>
			</div><!-- .footer-widgets-wrapper -->
		</section><!-- #footer-widgets.widget-area -->
	<?php endif; ?>


	<section id="site-info">
		<p role="contentinfo">&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></p>
		<p><?php printf( __( 'Powered by %1$s &amp; %2$s', 'heinrich' ), '<a href="http://wordpress.org">WordPress</a>', '<a href="http://heinrich.winklerin.de">Heinrich</a>' ); ?></p>
	</section><!-- #site-info -->


<a href="#top" class="top-link"><span class="icon icon-arrow-up2"></span><span class="screen-reader-text">top</span></a>

</footer><!-- #site-footer -->


<?php wp_footer(); ?>

</body>
</html>