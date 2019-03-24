<?php

/**
 * Title 配置
 */
function kratos_wp_title($title, $sep)
{
    global $paged, $page;
    if (is_feed()) {
        return $title;
    }

    $title .= get_bloginfo('name');
    $site_description = get_bloginfo('description', 'display');
    if ($site_description && (is_home() || is_front_page())) {
        $title = "$title $sep $site_description";
    }

    if ($paged >= 2 || $page >= 2) {
        $title = "$title $sep " . sprintf(__('Page %s', 'kratos'), max($paged, $page));
    }

    return $title;
}
add_filter('wp_title', 'kratos_wp_title', 10, 2);

/**
 * Keywords 配置
 */
function kratos_keywords()
{
    if (is_home() || is_front_page()) {echo kratos_option('seo_keywords');} elseif (is_category()) {single_cat_title();} elseif (is_single()) {
        echo trim(wp_title('', false)) . ',';
        if (has_tag()) {foreach ((get_the_tags()) as $tag) {echo $tag->name . ',';}}
        foreach ((get_the_category()) as $category) {echo $category->cat_name . ',';}
    } elseif (is_search()) {the_search_query();} else {echo trim(wp_title('', false));}
}

/**
 * Description 配置
 */
function kratos_description()
{
    if (is_home() || is_front_page()) {echo trim(kratos_option('seo_description'));} elseif (is_category()) {$description = strip_tags(category_description());
        echo trim($description);} elseif (is_single()) {
        if (get_the_excerpt()) {
            echo get_the_excerpt();
        } else {
            global $post;
            $description = trim(str_replace(array("\r\n", "\r", "\n", "　", " "), " ", str_replace("\"", "'", strip_tags($post->post_content))));
            echo mb_substr($description, 0, 220, 'utf-8');
        }
    } elseif (is_search()) {
        echo '"';
        the_search_query();
        echo '" 为您找到结果 ';
        global $wp_query;
        echo $wp_query->found_posts;
        echo ' 个';
    } elseif (is_tag()) {
        $description = strip_tags(tag_description());
        echo trim($description);
    } else { $description = strip_tags(term_description());
        echo trim($description);
    }
}

/**
 * 配置 robots.txt 内容（如果后台没有配置内容，调用默认配置）
 * https://developer.wordpress.org/reference/hooks/robots_txt/
 */
add_filter('robots_txt', function ($output, $public) {
    if ('0' == $public) {
        return "User-agent: *\nDisallow: /\n";
    } else {
        if (!empty(kratos_option('seo_robots'))) {
            $output = esc_attr(strip_tags(kratos_option('seo_robots')));
        }
        return $output;
    }
}, 10, 2);