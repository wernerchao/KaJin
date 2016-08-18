<?php
/**
 * Heinrich functions and definitions
 * @package Heinrich
 * since heinrich 1.0
 */

/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
/* Set the content width based on the theme's design and stylesheet.
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */

if ( ! isset( $content_width ) ) {
	$content_width = 724; /* pixels noch anpassen*/
}


/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
/* Sets up theme defaults and registers support for various WordPress features.
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */

if ( ! function_exists( 'heinrich_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function heinrich_setup() {


	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 */
	load_theme_textdomain( 'heinrich', get_template_directory() . '/languages' );


	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );


	// Add theme support for a nice title tag *//
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails', array( 'post', 'page' ) );
	add_image_size( 'small-thumbnail', 150, 150 );
	add_image_size( 'big-thumbnail', 150, 150 );
	set_post_thumbnail_size( 50, 50);


	// This theme uses wp_nav_menu() in two locations.  
	register_nav_menus( array( 
		'primary' => __( 'Header Navigation', 'heinrich' ),
		'secondary' => __( 'Social Links Menu', 'heinrich' )
) );


	// Enable support for Post Formats.
	add_theme_support( 'post-formats', array( 'status', 'link', 'image' ) );


	// Setup the WordPress core custom background feature.
	$args = array(
	'default-color'          => 'EDEBE8',
	'default-image'          => get_template_directory_uri() . '/img/background-image.jpg',
	'default-repeat'         => 'repeat',
	'default-position-x'     => 'top left'
	);
	add_theme_support( 'custom-background', $args );


	// Add Style to the WYSIWYG Editor
	add_editor_style( array( 'css/editor-style.css', heinrich_font_url() ) );


	// Enable support for HTML5 markup.
	add_theme_support( 'html5', array(
		'comment-list',
		'comment-form',
		'gallery',
		'caption'
	) );
}
endif; // heinrich_setup
add_action( 'after_setup_theme', 'heinrich_setup' );


/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
/* Register widgetized area and update sidebar with default widgets.
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */

function heinrich_widgets_init() {

	register_sidebar( array(
		'name' => __( 'Sidebar', 'heinrich' ),
		'id' => 'sidebar',
		'before_widget' => '<aside id="%1$s" class="box widget %2$s" role="complementary">',
		'after_widget' => '</aside><!-- .box -->'
	) );

	register_sidebar( array(
		'name' => __( 'Footer', 'heinrich' ),
		'id' => 'footer',
		'before_widget' => '<aside id="%1$s" class="widget %2$s" role="complementary">',
		'after_widget' => '</aside>'
	) );

}
add_action( 'widgets_init', 'heinrich_widgets_init' );


/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
/* Include Google Webfonts
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */

function heinrich_font_url() {
	$font_url = '';
	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Open Sans, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Open Sans font: on or off', 'heinrich' ) ) {
		$query_args = array(
			'family' => urlencode( 'Lato:300,400,600,400italic,600italic' ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);
		$font_url = add_query_arg( $query_args, '//fonts.googleapis.com/css' );
	}

	return $font_url;
}


/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
/* Enqueue scripts and styles.
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */

function heinrich_scripts() {

	// Add Open Sans font, used in the main stylesheet.
	wp_enqueue_style( 'heinrich-open-sans', heinrich_font_url(), array(), null );


	// Add Icomoon icon font, used in the main stylesheet.
	wp_enqueue_style( 'heinrich-icomoon', get_template_directory_uri() . '/icomoon/style.css', array(), '1.0' );

	// Load our main stylesheet.
	wp_enqueue_style( 'heinrich-style', get_stylesheet_uri(), array( 'heinrich-icomoon' ) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	
	// Include Masonry Layout Script and imagesLoaded script
	wp_enqueue_script('masonry');
	wp_enqueue_script( 'heinrich-imagesLoaded', get_stylesheet_directory_uri() . '/js/imagesLoaded.js', array( 'jquery' ) );

	wp_enqueue_script( 'heinrich-scripts', get_stylesheet_directory_uri() . '/js/heinrichScripts.js', array( 'jquery' ) );

}
add_action( 'wp_enqueue_scripts', 'heinrich_scripts' );


/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
/* If more than one page exists, return TRUE.
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */

function heinrich_show_posts_nav() {
	global $wp_query;
	return ($wp_query->max_num_pages > 1);
}


/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
/* Add a CSS class to the next and previous posts/comments link
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */

/* Filter For Posts */
add_filter('next_posts_link_attributes', 'heinrich_next_posts_link_attributes');
add_filter('previous_posts_link_attributes', 'heinrich_prev_posts_link_attributes');

function heinrich_next_posts_link_attributes() {
    return 'rel="next"';
}

function heinrich_prev_posts_link_attributes() {
    return 'rel="prev"';
}

/* Filter For Comments */
add_filter('next_comments_link_attributes', 'heinrich_next_comments_link_attributes');
add_filter('previous_comments_link_attributes', 'heinrich_prev_comments_link_attributes');

function heinrich_next_comments_link_attributes() {
    return 'rel="next"';
}

function heinrich_prev_comments_link_attributes() {
    return 'rel="prev"';
}

/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
/* Post excerpts 
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */

// Sets the auto post excerpt length to 30 words.
// And status posts to 50 words.
function heinrich_excerpt_length($length) {
	if (has_post_format ( 'status' )) {
		return 50; // 50 words
	}
	else {
		return 30; // 30 words
	}
}
add_filter('excerpt_length', 'heinrich_excerpt_length');


// Nicer more-tag
function heinrich_new_excerpt_more( $more ) {
	return ' &hellip;';
}
add_filter('excerpt_more', 'heinrich_new_excerpt_more');


/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
/* Tagcloud Widget: Show all Tags in a list (ul)
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */

add_filter( 'widget_tag_cloud_args', 'heinrich_widget_tag_cloud_args' );
function heinrich_widget_tag_cloud_args( $args ) {
	$args['number'] = 0;
	$args['format'] = 'list';
	$args['order'] = 'RAND';
	return $args;
}


/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
/* Redirect To Post When Search Query Returns Single Result
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */

add_action( 'template_redirect', 'heinrich_single_result' );  
function heinrich_single_result() {
	if (is_search()) {
		global $wp_query;  
	if ($wp_query->post_count == 1) {  
	wp_redirect( get_permalink( $wp_query->posts['0']->ID ) );  
		}  
	}  
}