<?php
/**
 * The index template file
 * 
 * @author Vtrois <seaton@vtrois.com>
 * @license GPL-3.0
 */
get_header(); ?>
<main class="kratos-main" style="background:<?php echo kratos_option('global_background_color', '#f5f5f5'); ?>">
	<div class="container">
		<div class="row">
			<div id="main" class="col-lg-8">
			<?php if(is_search()){ ?>
				<div class="kratos-post-border clearfix">
					<h1 class="kratos-post-header-title"><?php _e('搜索结果：', 'kratos');?><?php the_search_query(); ?></h1>
				</div>				
			<?php } ?>
            <?php
				if ( have_posts() ) {
					while ( have_posts() ){
						the_post();
						get_template_part('content', get_post_format());
					}
				}else{
			?>
			<div class="kratos-post-border clearfix">
					<h1 class="kratos-post-header-title"><?php _e('很抱歉，没有找到任何内容。', 'kratos');?></h1>
			</div>
			<?php } ?>
				<?php kratos_pages(3);?>
				<?php wp_reset_query(); ?>
			</div>
			<div id="kratos-widget-area" class="col-lg-4 d-none d-lg-block d-xl-block">
                <div id="sidebar">
                    <?php dynamic_sidebar('sidebar_tool'); ?>
                </div>
            </div>
		</div>
	</div>
</main>
<?php get_footer(); ?>