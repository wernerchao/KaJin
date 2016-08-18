<?php
/**
 * Header
 * @package Heinrich
 * since heinrich 1.0
 */
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>

<body id="top" <?php body_class(); ?>>

	<header id="masthead" class="site-header" role="banner">

		<section id="site-branding">
			<h1 class="site-title"><a href="<?php echo esc_url( home_url() ); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
			<h2 class="site-description"><?php bloginfo('description'); ?></h2>
		</section><!-- .site-branding -->

		<section class="nav-btn">
			<a href="<?php echo esc_url( home_url() ); ?>" class="home-btn"><span class="screen-reader-text"><?php _e( 'Home', 'heinrich' ); ?></span></a>
			<a class="menu-btn"><span class="screen-reader-text"><?php _e( 'Menu', 'heinrich' ); ?></span></a>
			<a class="search-btn"><span class="screen-reader-text"><?php _e( 'Search', 'heinrich' ); ?></span></a>
		</section><!-- .nav-btn -->

	</header><!-- #masthead .site-header-->
	

	<section id="fly-out-nav">
		<nav id="header-navigation" role="navigation">
			<?php wp_nav_menu( array(
				'theme_location' => 'primary',
				'container' => false,
				'depth' => -1,
				'items_wrap' => '<ul>%3$s<li><a href="#">
					<span class="menu-close-btn icon icon-cross"></span>
					</a></li></ul>'
			) ); ?>
		</nav><!-- #header-navigation -->
	</section><!-- #fly-out-nav -->

	<section id="fly-out-search">
		<?php get_search_form(); ?>
	</section><!-- #fly-out-search -->



	<div id="main" class="masonry" role="main">

	<div class="grid-sizer"></div>