<?php
/**
 * The template for displaying pages
 * 
 * @author Vtrois <seaton@vtrois.com>
 * @license GPL-3.0
 */
get_header(); ?>
<main class="kratos-main" style="background:<?php echo kratos_option('global_background_color', '#f5f5f5'); ?>">
	<div class="container">
		<div class="row">
            <div id="main" class="col-lg-8">
			<?php while ( have_posts() ) : the_post(); ?>
				<div>
					<div class="kratos-hentry kratos-post-inner clearfix">
						<div class="kratos-entry-header">
							<h1 class="kratos-entry-title text-center"><?php the_title(); ?></h1>
						</div>
						<div class="kratos-post-content"><?php the_content(); ?></div>
					</div>
						<?php comments_template(); ?>
				</div>
			<?php endwhile;?>
            </div>
			<div id="kratos-widget-area" class="col-lg-4 d-none d-lg-block d-xl-block">
                <div id="sidebar">
                    <?php dynamic_sidebar('sidebar_tool'); ?>
                </div>
            </div>
		</div>
	</div>
</div>
<?php get_footer(); ?>