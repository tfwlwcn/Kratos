<?php

/**
 * 主题设置
 */
if (is_admin() && !function_exists('optionsframework_init')):
    function optionsframework_init()
{

        if (!current_user_can('edit_theme_options')) {
            return;
        }

        require_once 'class-options-list.php';
        require_once 'class-options-framework.php';
        require_once 'class-options-framework-admin.php';
        require_once 'class-options-interface.php';
        require_once 'class-options-media-uploader.php';
        require_once 'class-options-sanitization.php';

        $options_framework = new Options_Framework;
        $options_framework->init();

        $options_framework_admin = new Options_Framework_Admin;
        $options_framework_admin->init();

        $options_framework_media_uploader = new Options_Framework_Media_Uploader;
        $options_framework_media_uploader->init();

    }
    add_action('init', 'optionsframework_init', 20);
endif;

if (!function_exists('kratos_option')):
    function kratos_option($name, $default = false)
{
        $config = get_option('optionsframework');

        if (!isset($config['id'])) {
            return $default;
        }

        $options = get_option($config['id']);

        if (isset($options[$name])) {
            return $options[$name];
        }

        return $default;
    }
endif;

add_action('admin_init', 'optionscheck_change_santiziation', 100);

function optionscheck_change_santiziation()
{
    remove_filter('of_sanitize_textarea', 'of_sanitize_textarea');
    add_filter('of_sanitize_textarea', 'custom_sanitize_textarea');
}

function custom_sanitize_textarea($input)
{
    global $allowedposttags;
    $custom_allowedtags["embed"] = array(
        "src" => array(),
        "type" => array(),
        "allowfullscreen" => array(),
        "allowscriptaccess" => array(),
        "height" => array(),
        "width" => array(),
    );
    $custom_allowedtags["script"] = array("type" => array(), "src" => array());
    $custom_allowedtags = array_merge($custom_allowedtags, $allowedposttags);
    $output = wp_kses($input, $custom_allowedtags);
    return $output;
}

/**
 * 主题激活自动跳转主题设置
 */
add_action('load-themes.php', 'kratos_init_theme');
function kratos_init_theme()
{
    global $pagenow;
    if ('themes.php' == $pagenow && isset($_GET['activated'])) {
        wp_redirect(admin_url('admin.php?page=ktatos_options'));
        exit;
    }
}

/**
 * 主题本地化（中文、英文）
 */
function kratos_theme_languages()
{
    load_theme_textdomain('kratos', get_template_directory() . '/languages');
}

add_action('after_setup_theme', 'kratos_theme_languages');

/**
 * 禁用前端页面 Admin Bar
 */
add_filter('show_admin_bar', '__return_false');

/**
 * 禁用转义
 */
function kratos_formatter($content)
{
    $new_content = '';
    $pattern_full = '{(\[raw\].*?\[/raw\])}is';
    $pattern_contents = '{\[raw\](.*?)\[/raw\]}is';
    $pieces = preg_split($pattern_full, $content, -1, PREG_SPLIT_DELIM_CAPTURE);
    foreach ($pieces as $piece) {
        if (preg_match($pattern_contents, $piece, $matches)) {
            $new_content .= $matches[1];
        } else {
            $new_content .= wptexturize(wpautop($piece));
        }
    }
    return $new_content;
}

add_filter('the_content', 'kratos_formatter', 99);

$work_tags = array('the_title', 'the_excerpt', 'single_post_title', 'comment_author', 'comment_text', 'link_description', 'bloginfo', 'wp_title', 'term_description', 'category_description', 'widget_title', 'widget_text');

foreach ($work_tags as $work_tag) {
    remove_filter($work_tag, 'wptexturize');
}

remove_filter('the_content', 'wpautop');
remove_filter('the_content', 'wptexturize');
remove_filter('the_excerpt', 'wpautop');
add_filter('the_content', 'wpautop', 12);

/**
 * ! 加载静态资源
 */
function kratos_theme_scripts()
{
    $dir = get_template_directory_uri();

    if (!is_admin()) {
        wp_enqueue_style('animate', $dir . '/assets/css/animate.min.css', array(), KRATOS_VERSION);
        wp_enqueue_style('layer', $dir . '/assets/css/layer.min.css', array(), KRATOS_VERSION);
        wp_enqueue_style('kratos', $dir . '/assets/css/kratos.min.css', array(), KRATOS_VERSION);
        wp_enqueue_style('custom', $dir . '/custom/custom-style.css', array(), KRATOS_VERSION);

        wp_enqueue_script('jquery', $dir . '/assets/js/jquery.min.js', array(), KRATOS_VERSION);
        wp_enqueue_script('icon', $dir . '/assets/js/icon.min.js', array(), KRATOS_VERSION);
        wp_enqueue_script('qrcode', $dir . '/assets/js/jquery.qrcode.min.js', array(), KRATOS_VERSION);
        wp_enqueue_script('layer', $dir . '/assets/js/layer.min.js', array(), KRATOS_VERSION);
        wp_enqueue_script('bootstrap', $dir . '/assets/js/bootstrap.min.js', array(), '3.3.7');
        wp_enqueue_script('waypoints', $dir . '/assets/js/jquery.waypoints.min.js', array(), '4.0.0');
        wp_enqueue_script('superfish', $dir . '/assets/js/superfish.js', array(), '1.0.0');
        wp_enqueue_script('kratos', $dir . '/assets/js/kratos.js', array(), KRATOS_VERSION);
        wp_enqueue_script('custom', $dir . '/custom/custom-script.js', array(), KRATOS_VERSION);
    }

    $data = array(
        'site' => home_url(),
    );

    wp_localize_script( 'kratos', 'kratos', $data );
}

add_action('wp_enqueue_scripts', 'kratos_theme_scripts');

/**
 * 禁用默认 jQuery 版本
 */
function kratos_enqueue_scripts()
{
    wp_deregister_script('jquery');
}

add_action('wp_enqueue_scripts', 'kratos_enqueue_scripts', 1);

/**
 * 开启友情链接功能
 */
add_filter('pre_option_link_manager_enabled', '__return_true');

/**
 * 文章中图片自动添加文章名的alt
 */
function kratos_auto_post_link($content)
{
    global $post;
    $content = preg_replace('/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i', "<img src=\"$2\" alt=\"《" . $post->post_title . "》\" />", $content);
    return $content;
}
add_filter('the_content', 'kratos_auto_post_link', 0);

/**
 * 删除多余 CSS 内容
 */
add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1);
add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1);
add_filter('page_css_class', 'my_css_attributes_filter', 100, 1);
function my_css_attributes_filter($var)
{
    return is_array($var) ? array_intersect($var, array('current-menu-item', 'current-post-ancestor', 'current-menu-ancestor', 'current-menu-parent')) : '';
}

/**
 * 上传文件支持 svg
 */
add_filter('upload_mimes', 'upload_svg');
function upload_svg($existing_mimes = array())
{
    $existing_mimes['svg'] = 'image/svg+xml';
    return $existing_mimes;
}

/**
 * ! 分页
 */
function kratos_pages($range = 3){
    global $paged, $wp_query,$max_page;
    if ( !$max_page ) {$max_page = $wp_query->max_num_pages;}
    if($max_page > 1){if(!$paged){$paged = 1;}
    echo "<div><ul class='pagination'>";
        if($paged != 1){
            echo "<li><a href='" . get_pagenum_link(1) . "' class='extend' title='首页'>&laquo;</a></li>";
        }
        if($paged>1) echo '<li><a href="' . get_pagenum_link($paged-1) .'" class="prev" title="上一页">&lt;</a></li>';
        if($max_page > $range){
            if($paged < $range){
                for($i = 1; $i <= ($range + 1); $i++){
                    echo "<li"; if($i==$paged)echo " class='active'";echo "><a href='" . get_pagenum_link($i) ."'>$i</a></li>";
                }
            }
            elseif($paged >= ($max_page - ceil(($range/2)))){
                for($i = $max_page - $range; $i <= $max_page; $i++){
                    echo "<li";
                    if($i==$paged)
                        echo " class='active'";echo "><a href='" . get_pagenum_link($i) ."'>$i</a></li>";
                }
            }
            elseif($paged >= $range && $paged < ($max_page - ceil(($range/2)))){
                for($i = ($paged - ceil($range/2)); $i <= ($paged + ceil(($range/2))); $i++){
                    echo "<li";
                    if($i==$paged)echo " class='active'";
                    echo "><a href='" . get_pagenum_link($i) ."'>$i</a></li>";
                }
            }
        }
        else{
            for($i = 1; $i <= $max_page; $i++){
                echo "<li";
                if($i==$paged)echo " class='active'";
                echo "><a href='" . get_pagenum_link($i) ."'>$i</a></li>";
            }
        }
        if($paged<$max_page) echo '<li><a href="' . get_pagenum_link($paged+1) .'" class="next" title="下一页">&gt;</a></li>';
        if($paged != $max_page){
            echo "<li><a href='" . get_pagenum_link($max_page) . "' class='extend' title='尾页'>&raquo;</a></li>";
        }
        echo "</ul></div>";
    }
}

/**
 * 注册导航
 */
function kratos_register_nav_menu() {
    register_nav_menus(array('header_menu' => __('顶部菜单','kratos')));
}
add_action('after_setup_theme', 'kratos_register_nav_menu');

/**
 * 高亮导航
 */
function kratos_active_menu_class($classes) {
if (in_array('current-menu-item', $classes) || in_array('current-menu-ancestor', $classes))
    $classes[] = 'active';
return $classes;
}

add_filter('nav_menu_css_class', 'kratos_active_menu_class');

/**
 * 仪表盘页面引导
 */
add_action('welcome_panel', 'kratos_admin_notice');
function kratos_admin_notice()
{
    ?>
  <style type="text/css">
    .about-description a{
      text-decoration:none;
    }
  </style>
  <div class="notice notice-info">
    <p class="about-description">
        <?php _e('嗨，欢迎使用 Kratos 主题开始文章创作，欢迎您加入主题 QQ 交流群：<a target="_blank" rel="nofollow" href="//shang.qq.com/wpa/qunwpa?idkey=6ee0cc94b247fe4a068be7442b38fce2850485422ec9d655f0a60563ae00bdd2">734508</a> ，如果发现任何 BUG 或者您有什么好的建议，请按照页面给出的提示提交<a target="_blank" rel="nofollow" href="https://github.com/Vtrois/Kratos/issues/new"> Issues </a>。', 'kratos');?>
    </p>
  </div>
  <?php
}

/**
 * 页面底部引导
 */
function kratos_admin_footer_text($text)
{
    $text = __('<span id="footer-thankyou">感谢使用 <a href=http://wordpress.org/ target="_blank">WordPress</a> 进行创作，<a target="_blank" rel="nofollow" href="//shang.qq.com/wpa/qunwpa?idkey=6ee0cc94b247fe4a068be7442b38fce2850485422ec9d655f0a60563ae00bdd2">点击</a> 加入主题交流群。</span>', 'kratos');
    return $text;
}

add_filter('admin_footer_text', 'kratos_admin_footer_text');

/**
 * 当搜到的文章只有一篇直接进入
 */
add_action('template_redirect', function () {
    if (is_search() && get_query_var('module') == '') {
        global $wp_query;
        $paged = get_query_var('paged');
        if ($wp_query->post_count == 1 && empty($paged)) {
            wp_redirect(get_permalink($wp_query->posts['0']->ID));
        }
    }
});

/**
 * 评论表情包
 */
add_filter('smilies_src','custom_smilies_src',1,10);
function custom_smilies_src ($img_src, $img, $siteurl){
    return get_bloginfo('template_directory').'/assets/img/smilies/'.$img;
}
function disable_emojis_tinymce( $plugins ) {
    return array_diff( $plugins, array( 'wpemoji' ) );
}
function smilies_reset() {
    global $wpsmiliestrans, $wp_smiliessearch, $wp_version;
    if ( !get_option( 'use_smilies' ) || $wp_version < 4.2)
        return;
    $wpsmiliestrans = array(
    ':mrgreen:' => 'mrgreen.png',
    ':exclaim:' => 'exclaim.png',
    ':neutral:' => 'neutral.png',
    ':twisted:' => 'twisted.png',
      ':arrow:' => 'arrow.png',
        ':eek:' => 'eek.png',
      ':smile:' => 'smile.png',
   ':confused:' => 'confused.png',
       ':cool:' => 'cool.png',
       ':evil:' => 'evil.png',
    ':biggrin:' => 'biggrin.png',
       ':idea:' => 'idea.png',
    ':redface:' => 'redface.png',
       ':razz:' => 'razz.png',
   ':rolleyes:' => 'rolleyes.png',
       ':wink:' => 'wink.png',
        ':cry:' => 'cry.png',
  ':surprised:' => 'surprised.png',
        ':lol:' => 'lol.png',
        ':mad:' => 'mad.png',
   ':drooling:' => 'drooling.png',
     ':cowboy:' => 'cowboy.png',
':persevering:' => 'persevering.png',
    ':symbols:' => 'symbols.png',
       ':shit:' => 'shit.png',
    );
}
smilies_reset();