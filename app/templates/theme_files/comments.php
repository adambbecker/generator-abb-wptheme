<?php
/**
 * Comments Template
 *
 * Acts as the template for showing the various comments on a blog post.
 * This is purely an example that has most of the comment functions wrapped in common containers.
 *
 * Use what you'd like, discard the rest.
 *
 * @package WordPress
 * @subpackage <%= themeTitle %> theme
 * @since 1.0.0
 */
 
// Stop script if loaded directly
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly.');
?>

<!-- Main Comment Container -->
<div id="comments">

<?php /* Check if password protected, if so display message to viewer, stop processing comments */
	if ( post_password_required() ) : ?>
		<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view comments.', '<%= _.slugify(textDomain) %>' ); ?></p>
	</div>
	<?php return; endif;

/*-----------------------------------------------------------------------------------*/
/*	Display Comments and/or Pings
/*-----------------------------------------------------------------------------------*/
	if ( have_comments() ) : // if there are comments ?>

		<?php if ( ! empty($comments_by_type['comment']) ) : // if there are normal comments ?>
			<ol class="commentlist">
			<?php wp_list_comments('type=comment&avatar_size=40&callback=<%= _.slugify(textDomain) %>_comment'); ?>
			</ol>
		<?php endif; // END if there are normal comments ?>

        <?php if ( ! empty($comments_by_type['pings']) ) : // if there are pings ?>
	        <ol class="pinglist">
	        <?php wp_list_comments('type=pings&callback=<%= _.slugify(textDomain) %>_list_pings'); ?>
	        </ol>        
        <?php endif; // END if there are pings ?>
        
        <div class="comment-navigation">
			<div class="next"><?php next_comments_link(); ?></div>
			<div class="prev"><?php previous_comments_link(); ?></div>
		</div>
		
	<?php endif; // END if there are comments	
	
/*-----------------------------------------------------------------------------------*/
/*	Comment Form
/*-----------------------------------------------------------------------------------*/
	if ( comments_open() ) : // if comments are open ?>
		<div class="respond">
			<?php 
			/* If you'd like to customize the default comment form, pass $args into comment_form()
			 * http://codex.wordpress.org/Function_Reference/comment_form
			 */
			comment_form(); ?>
		</div>
	<?php else : // if comments are NOT open ?>
		<p><?php _e('Comments are now closed.', '<%= _.slugify(textDomain) %>'); ?></p>
	<?php endif; // END if comments are open ?>
	
<!-- END Main Comment Container -->
</div>