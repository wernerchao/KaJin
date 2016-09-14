<?php
/**
 * The template for displaying Comments
 * The area of the page that contains comments and the comment form.
 *
 * @package Heinrich
 * @since Heinrich 1.0
 */

/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if ( post_password_required() )
	return;
?>

<div id="comments-area" class="box comments">

		<?php if ( have_comments() ) : ?>
		
		<h2 class="comments-title">
			<?php
				printf( _nx( 'One comment on %2$s', '%1$s comments on %2$s', get_comments_number(), 'comments title', 'heinrich' ),
					number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
			?>
		</h2>
		
		<ol class="commentlist">
			<?php
				wp_list_comments( array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 70,
				) );
			?>
		</ol><!-- .commentlist -->
		
		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<nav id="navigation-comment" class="navigation" role="navigation">
				<?php previous_comments_link(); ?>
				<?php next_comments_link(); ?>
			</nav> <!-- #navigation.comment -->
		<?php endif; // Check for comment navigation. ?>
		
		<?php if ( ! comments_open() ) : ?>
		<p class="no-comments"><?php _e( 'Comments are closed.', 'heinrich' ); ?></p>
		<?php endif; ?>
		
		<?php endif; // have_comments() ?>
		
		<?php comment_form(); ?>

</div><!-- #comments-area .box.comments -->