<?php

/**
 * 禁用所有文章类型的修订版本
 */
if (kratos_option('optimize_revisions', true)) {
    add_filter('wp_revisions_to_keep', 'kratos_wp_revisions_to_keep', 10, 2);
    function kratos_wp_revisions_to_keep($num, $post)
    {
        return 0;
    }
}

/**
 * 禁用 Emoji
 */
if (kratos_option('optimize_emoji', true)) {
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('embed_head', 'print_emoji_detection_script');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    add_filter('emoji_svg_url', '__return_false');

    function kratos_disable_emoji_tiny_mce_plugin($plugins)
    {
        return array_diff($plugins, array('wpemoji'));
    }

    add_filter('tiny_mce_plugins', 'kratos_disable_emoji_tiny_mce_plugin');
}

/**
 * 禁用 Trackbacks
 */
if (kratos_option('optimize_trackbacks', true)) {
    add_filter('xmlrpc_methods', function ($methods) {

        $methods['pingback.ping'] = '__return_false';
        $methods['pingback.extensions.getPingbacks'] = '__return_false';

        return $methods;
    });

    remove_action('do_pings', 'do_all_pings', 10);
    remove_action('publish_post', '_publish_post_hook', 5);
}

/**
 * 优化 wp_head() 内容
 */
if (kratos_option('optimize_wphead', true)) {
    foreach (array('rss2_head', 'commentsrss2_head', 'rss_head', 'rdf_header', 'atom_head', 'comments_atom_head', 'opml_head', 'app_head') as $action) {
        remove_action($action, 'the_generator');
    }

    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'index_rel_link');
    remove_action('wp_head', 'parent_post_rel_link', 10);
    remove_action('wp_head', 'start_post_rel_link', 10);
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
    remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
    remove_action('wp_head', 'rest_output_link_wp_head', 10);
    remove_action('template_redirect', 'wp_shortlink_header', 11);
    remove_action('template_redirect', 'rest_output_link_header', 11);
}

/**
 * 禁用 WordPress 拼写修正
 * https://developer.wordpress.org/reference/functions/capital_p_dangit/
 */
if (kratos_option('optimize_capitalpdangit', true)) {
    remove_filter('the_title', 'capital_P_dangit', 11);
    remove_filter('the_content', 'capital_P_dangit', 11);
    remove_filter('comment_text', 'capital_P_dangit', 31);
}

/**
 * 禁用 Gutenberg 编辑器
 */
if (kratos_option('optimize_gutenberg', false)) {
    add_filter('use_block_editor_for_post', '__return_false');
    remove_action('wp_enqueue_scripts', 'wp_common_block_scripts_and_styles');
}

/**
 * 禁用后台 Google Fonts
 */
if (kratos_option('optimize_googlefonts', true)) {
    add_filter('style_loader_src', function ($href) {

        if (strpos($href, "fonts.googleapis.com") === false) {
            return $href;
        }

        return false;
    });
}

/**
 * 优化 Gravatar 链接
 */
if (kratos_option('optimize_gravatar', true)) {
    function kratos_get_https_avatar($avatar)
    {
        $avatar = str_replace(array("www.gravatar.com", "0.gravatar.com", "1.gravatar.com", "2.gravatar.com", "3.gravatar.com"), "secure.gravatar.com", $avatar);
        $avatar = str_replace("http://", "https://", $avatar);

        return $avatar;
    }

    add_filter('get_avatar', 'kratos_get_https_avatar');
}

/**
 * 禁用 Auto Embeds
 */
if (kratos_option('optimize_autoembeds', true)) {
    remove_filter('the_content', array($GLOBALS['wp_embed'], 'autoembed'), 8);
}

/**
 * 禁用 Post Embeds
 */
if (kratos_option('optimize_postembeds', true)) {
    remove_action('rest_api_init', 'wp_oembed_register_route');
    remove_filter('rest_pre_serve_request', '_oembed_rest_pre_serve_request', 10, 4);

    add_filter('embed_oembed_discover', '__return_false');

    remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
    remove_filter('oembed_response_data', 'get_oembed_response_data_rich', 10, 4);

    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');

    add_filter('tiny_mce_plugins', 'kratos_disable_post_embed_tiny_mce_plugin');
    function kratos_disable_post_embed_tiny_mce_plugin($plugins)
    {
        return array_diff($plugins, array('wpembed'));
    }

    add_filter('query_vars', 'kratos_disable_post_embed_query_var');
    function kratos_disable_post_embed_query_var($public_query_vars)
    {
        return array_diff($public_query_vars, array('embed'));
    }
}

/**
 * 为 page 添加 .html 伪静态后缀
 */
// if (kratos_option('optimize_pagepermalink', true)) {
//     add_action('init', 'html_page_permalink', -1);
//     function html_page_permalink()
//     {
//         global $wp_rewrite;
//         if (!strpos($wp_rewrite->get_page_permastruct(), '.html')) {
//             $wp_rewrite->page_structure = $wp_rewrite->page_structure . '.html';
//         }
//     }
// }

// ! 删除前端的block library的css资源，开启禁用古登堡也有这个效果，暂时没测试是否影响前端显示
add_action('wp_enqueue_scripts', 'kratos_remove_block_library_css', 100);
function kratos_remove_block_library_css()
{
    wp_dequeue_style('wp-block-library');
}