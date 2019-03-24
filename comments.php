<?php
/**
 * The template for displaying comments
 * 
 * @author Vtrois <seaton@vtrois.com>
 * @license GPL-3.0
 */
if ( post_password_required() ) {
	return;
}
?>
<div id="comments" class="comments-area">
	<?php if ( have_comments() ) : ?>
		<ol class="comment-list">
			<?php
				wp_list_comments( array(
					'style' => 'ol',
					'short_ping' => true,
					'avatar_size'=> 50,
				) );
			?>
		</ol>
		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
	<div id="comments-nav">
<?php paginate_comments_links('prev_text=<&next_text=>');?>
</div>
		<?php endif; ?>
	<?php endif; ?>
	<?php
		if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
	<?php endif; ?>
	<?php require_once 'includes/theme-function-smiley.php'; ?>
	<?php 
		$fields =  array(
			'author' => '<div class="comment-form-author form-group has-feedback"> <div class="input-group mb-3"> <div class="input-group-prepend"> <span class="input-group-text"><svg class="icon" aria-hidden="true"> <use xlink:href="#i-yonghu"></use> </svg></span> </div> <input class="form-control" placeholder="'. __('昵称','kratos') .'" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" required /><span class="required">*</span> </div> </div>',
   			'email'  => '<div class="comment-form-author form-group has-feedback"> <div class="input-group mb-3"> <div class="input-group-prepend"> <span class="input-group-text"><svg class="icon" aria-hidden="true"> <use xlink:href="#i-youjian"></use> </svg></span> </div> <input class="form-control" placeholder="'. __('邮箱','kratos') .'" id="email" name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" required /><span class="required">*</span> </div> </div>',
			'url'    => '<div class="comment-form-author form-group has-feedback"> <div class="input-group mb-3"> <div class="input-group-prepend"> <span class="input-group-text"><svg class="icon" aria-hidden="true"> <use xlink:href="#i-url"></use> </svg></span> </div> <input class="form-control" placeholder="'. __('网址','kratos') .'" id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /> </div> </div>',
			'cookies' => '',
		);
		$args = array(
			'title_reply_before' => '<h4 id="reply-title" class="comment-reply-title">',
			'title_reply_after'  => '</h4>',
			'fields' =>  $fields,
			'class_submit' => 'btn btn-primary',
			'comment_field' =>  '<div class="comment form-group has-feedback"> <div class="form-group"> <p>'.$smilies.'</p> <textarea class="form-control" class="form-control" id="comment" placeholder=" " name="comment" rows="4" aria-required="true" required onkeydown="if(event.ctrlKey){if(event.keyCode==13){document.getElementById(\'submit\').click();return false}};"></textarea> </div> </div>',
		);
		comment_form($args);
	?>
</div>

