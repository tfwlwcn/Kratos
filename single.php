<?php
/**
 * The template for displaying all single posts and attachments
 * 
 * @author Vtrois <seaton@vtrois.com>
 * @license GPL-3.0
 */
get_header(); ?>
<main class="kratos-main" style="background:<?php echo kratos_option('global_background_color', '#f5f5f5'); ?>">
	<div class="container">
		<div class="row">
            <div id="main" class="col-lg-8">
				<?php if (have_posts()){ the_post();update_post_caches($posts);?>
					<div>
						<div class="kratos-hentry kratos-post-inner clearfix">
							<div class="kratos-entry-header">
								<h1 class="kratos-entry-title text-center"><?php the_title();?></h1>
								<div class="kratos-post-meta text-center">
									<span>
									<svg class="icon" aria-hidden="true"><use xlink:href="#i-date"></use></svg> <?php echo get_the_date(); ?>
					                <svg class="icon" aria-hidden="true"><use xlink:href="#i-discuss"></use></svg> <?php comments_number('0', '1', '%');?><?php _e('条评论', 'kratos');?>
					                <svg class="icon" aria-hidden="true"><use xlink:href="#i-view"></use></svg> <?php echo kratos_get_post_views(); ?><?php _e('次阅读', 'kratos');?>
					                <svg class="icon" aria-hidden="true"><use xlink:href="#i-thumbs"></use></svg> <?php if (get_post_meta($post->ID, 'love', true)) {echo get_post_meta($post->ID, 'love', true);} else {echo '0';}?><?php _e('人点赞', 'kratos');?>
									</span>
								</div>
							</div>
							<div class="kratos-post-content">
							<?php if (kratos_option('ad_top', false)) {?>
								<a href="<?php echo kratos_option('ad_top_url'); ?>"><img src="<?php echo kratos_option('ad_top_img', get_template_directory_uri() . '/assets/img/ad.jpg') ?>"></a>
							<?php }?>
	                        <?php the_content();?>
							<?php if (kratos_option('ad_bottom', false)) {?>
								<a href="<?php echo kratos_option('ad_bottom_url'); ?>"><img src="<?php echo kratos_option('ad_bottom_img', get_template_directory_uri() . '/assets/img/ad.jpg') ?>"></a>
							<?php }?>
							</div>
							<div class="kratos-entry-footer clearfix">
								<div class="post-like-donate text-center clearfix" id="post-like-donate">
								<?php if (kratos_option('post_donate', false)) {?>
					   			<a href="<?php echo kratos_option('post_donate_links'); ?>" class="Donate"><svg class="icon" aria-hidden="true"> <use xlink:href="#i-dashang"></use> </svg><?php _e('打赏', 'kratos');?></a>
					   			<?php }?>
					   			<a href="javascript:;" id="btn" data-action="love" data-id="<?php the_ID();?>" class="Love <?php if (isset($_COOKIE['love_' . $post->ID])) { echo 'done'; } ?>" ><svg class="icon" aria-hidden="true"> <use xlink:href="#i-thumbs"></use> </svg><?php _e('点赞', 'kratos');?></a>
								<?php if (kratos_option('post_share', true)) {?>
								<a href="javascript:;"  class="Share"><svg class="icon" aria-hidden="true"> <use xlink:href="#i-fenxiang"></use></svg><?php _e('分享', 'kratos');?></a>
									<?php require_once get_template_directory() . '/includes/theme-function-share.php';?>
								<?php }?>
					    		</div>
								<div class="footer-tag clearfix">
									<div class="float-left">
									    <?php if (get_the_tags()) {the_tags('', ' ', '');} else {_e('<a>暂无</a>', 'kratos');}?>
									</div>
								</div>
							</div>
						</div>
						<?php if (kratos_option('post_cc', true)){ ?>
						<div class="kratos-hentry kratos-copyright text-center clearfix">
							<img src="<?php echo get_template_directory_uri(); ?>/assets/img/licenses.png">
							<p><?php _e('本作品采用 知识共享署名-相同方式共享 4.0 国际许可协议 进行许可', 'kratos'); ?></p>
						</div>
						<?php }?>
					<div class="navigation post-navigation clearfix" role="navigation">
						<?php $prev_post = get_previous_post(true); if (!empty($prev_post)){ ?>
						<div class="nav-previous clearfix">
							<a title="<?php echo $prev_post->post_title; ?>" href="<?php echo get_permalink($prev_post->ID); ?>"><?php _e('&lt; 上一篇', 'kratos'); ?></a>
						</div>
						<?php }?>
						<?php $next_post = get_next_post(true); if (!empty($next_post)){ ?>
						<div class="nav-next">
							<a title="<?php echo $next_post->post_title; ?>" href="<?php echo get_permalink($next_post->ID); ?>"><?php _e('下一篇 &gt;', 'kratos'); ?></a>
						</div>
						<?php }?>
					</div>
					<?php comments_template();?>
				</div>
				<?php }?>
			</div>
			<div id="kratos-widget-area" class="col-lg-4 d-none d-lg-block d-xl-block">
				<div id="sidebar">
					<?php dynamic_sidebar('sidebar_tool');?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php get_footer();?>