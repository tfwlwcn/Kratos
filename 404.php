<?php
/**
 * The template for displaying 404 pages (not found)
 * 
 * @author Vtrois <seaton@vtrois.com>
 * @license GPL-3.0
 */
get_header(); ?>
<div class="kratos-404">
	<div class="kratos-overlay"></div>
	<div class="kratos-cover text-center" style="background-image: url('<?php echo kratos_option('error_image', get_template_directory_uri() . '/assets/img/404.jpg'); ?>')">
		<div class="notice">
			<h2><?php echo kratos_option('error_title', '这里已经是废墟 什么东西都没有'); ?></h2>
			<span><?php echo kratos_option('error_notice', 'That page can not be found'); ?></span>
			<span><a href="<?php echo home_url(); ?>"><div class="btn btn-star"><?php _e('返回首页','kratos'); ?></div></a></span>
		</div>
	</div>
</div>
<?php get_footer(); ?>