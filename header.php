<?php
/**
 * The template for displaying the header
 * 
 * @author Vtrois <seaton@vtrois.com>
 * @license GPL-3.0
 */
?>
<!DOCTYPE html>
<html lang="zh-cn">
    <head>
        <meta charset="UTF-8">
        <title><?php wp_title( '-', true, 'right' ); ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="format-detection" content="telphone=no, email=no">
		<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
		<meta itemprop="image" content="<?php echo kratos_thumbnail_url(); ?>"/>
        <meta name="keywords" content="<?php kratos_keywords();?>" />
        <meta name="description" itemprop="description" content="<?php kratos_description(); ?>" />
		<?php wp_head(); ?>
		<?php if ( kratos_option('global_mourning', false)) { ?>
			<style type="text/css">html{filter: grayscale(100%);-webkit-filter: grayscale(100%);-moz-filter: grayscale(100%);-ms-filter: grayscale(100%);-o-filter: grayscale(100%);filter: progid:DXImageTransform.Microsoft.BasicImage(grayscale=1);filter: gray;-webkit-filter: grayscale(1); }
			</style>
		<?php } ?>
		<?php wp_print_scripts('jquery'); ?>
    </head>
	<body>
	<div class="kratos-wrapper">
		<div class="kratos-page">
				<div class="kratos-header">
					<header class="kratos-header-section">
						<div class="container">
							<div class="nav-header">
								<a class="js-kratos-nav-toggle kratos-nav-toggle"><i></i></a>
								<?php if ( kratos_option('global_logo') ) {?>
									<a href="<?php echo get_option('home'); ?>">
										<h1 class="kratos-logo-img"><img src="<?php echo kratos_option('global_logo'); ?>"></h1>
									</a>
								<?php }else{?>
									<h1 class="kratos-logo"><a href="<?php echo get_option('home'); ?>"><?php bloginfo('name'); ?></a></h1>
								<?php }?>
								<?php $defaults = array('theme_location' => 'header_menu', 'container' => 'nav', 'container_id' => 'kratos-menu-wrap', 'menu_class' => 'sf-menu', 'menu_id' => 'kratos-primary-menu', ); ?>
								<?php wp_nav_menu($defaults); ?>
							</div>
						</div>
					</header>
				</div>
				<?php if(!is_404()){ ?>
					<div class="kratos-start">
						<div class="kratos-overlay"></div>
						<div class="kratos-banner text-center" style="background-image: url(<?php echo kratos_option('global_background_img', get_template_directory_uri() . '/assets/img/background.jpg'); ?>);">
							<div class="desc animate-box">
								<?php if(is_category()){
								echo '<h2>' . single_cat_title('', false) . '</h2><span>' . category_description() . '</span>';
								}else{
								echo '<h2>' . kratos_option('global_background_title', 'Kratos') . '</h2><span>' . kratos_option('global_background_abstract', 'A responsible theme for WordPress') . '</span>';
								} ?>
							</div>
						</div>
					</div>
				<?php } ?>

