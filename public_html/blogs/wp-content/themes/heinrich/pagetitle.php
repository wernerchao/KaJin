<?php
/**
 * Pagetitle
 * @package Heinrich
 * since heinrich 1.0
 */
?>

<?php if ( is_category() ) : ?>
	<section class="box pagetitle">
		<h2><?php single_cat_title(); ?></h2>
	</section><!-- .box.pagetitle -->


<?php elseif ( is_tag() ) : ?>
	<section class="box pagetitle">
		<h2><?php single_tag_title(); ?></h2>
	</section><!-- .box.pagetitle -->


<?php elseif ( is_day() ) : ?>
	<section class="box pagetitle">
		<h2><?php the_time( get_option( 'date_format' ) ); ?></h2>
	</section><!-- .box.pagetitle -->


<?php elseif ( is_month() ) : ?>
	<section class="box pagetitle">
		<h2><?php the_time( 'F Y' ); ?></h2>
	</section><!-- .box.pagetitle -->


<?php elseif ( is_year() ) : ?>
	<section class="box pagetitle">
		<h2><?php the_time( 'Y' ); ?></h2>
	</section><!-- .box.pagetitle -->


<?php elseif ( is_search() ) : ?>
	<section class="box pagetitle">
		<h2><?php echo $wp_query->found_posts; ?> <?php printf( __( 'Search Results for %s', 'heinrich' ), '<span class="searchterms">' . get_search_query() . '</span>' ); ?></h2>
	</section><!-- .box.pagetitle -->
<?php endif; ?>