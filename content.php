<?php
/**
 * The default template for displaying content
 * 
 * @author Vtrois <seaton@vtrois.com>
 * @license GPL-3.0
 */
?>
<div class="kratos-post-border clearfix">
    <div class="kratos-post-thumb">
        <?php kratos_thumbnail() ?>
    </div>
    <div class="kratos-post-inner">
        <div class="kratos-post-header">
            <a class="label" href="<?php $category = get_the_category();echo get_category_link($category[0] -> term_id) . '">' . $category[0] -> cat_name ; ?><i class="label-arrow"></i></a>
            <h2 class="kratos-post-title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
        </div>
        <div class="kratos-post-content">
            <p><?php echo wp_trim_words(get_the_excerpt(), kratos_option('post_trim', '110')); ?></p>
        </div>
    </div>
    <div class="kratos-post-meta">
        <span class="float-left">
            <a href="<?php the_permalink() ?>" class="d-none d-lg-inline-block d-xl-inline-block"><svg class="icon" aria-hidden="true"><use xlink:href="#i-date"></use></svg> <?php echo get_the_date(); ?></a>
            <a href="<?php the_permalink() ?>#respond" class="d-none d-lg-inline-block d-xl-inline-block"><svg class="icon" aria-hidden="true"><use xlink:href="#i-discuss"></use></svg> <?php comments_number('0', '1', '%'); ?><?php _e('条评论', 'kratos');?></a>
            <a href="<?php the_permalink() ?>"><svg class="icon" aria-hidden="true"><use xlink:href="#i-view"></use></svg> <?php echo kratos_get_post_views(); ?><?php _e('次阅读', 'kratos');?></a>
            <a href="<?php the_permalink() ?>"><svg class="icon" aria-hidden="true"><use xlink:href="#i-thumbs"></use></svg> <?php if( get_post_meta($post->ID,'love',true) ){ echo get_post_meta($post->ID,'love',true); } else { echo '0'; }?><?php _e('人点赞', 'kratos');?></a>
        </span>
        <span class="float-right">
            <a class="read-more" href="<?php the_permalink() ?>" title="<?php _e('阅读全文', 'kratos');?>"><?php _e('阅读全文', 'kratos');?> <svg class="icon" aria-hidden="true"><use xlink:href="#i-right"></use></svg></i></a>
        </span>
    </div>
</div>