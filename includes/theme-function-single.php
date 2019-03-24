<?php

/**
 * 文章超链接添加新窗口打开并添加 nofollow
 */
function kratos_nofollow($content)
{
    $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>";
    if (preg_match_all("/$regexp/siU", $content, $matches, PREG_SET_ORDER)) {
        if (!empty($matches)) {
            $srcUrl = get_option('siteurl');
            for ($i = 0; $i < count($matches); $i++) {
                $tag = $matches[$i][0];
                $tag2 = $matches[$i][0];
                $url = $matches[$i][0];
                $noFollow = '';
                $pattern = '/target\s*=\s*"\s*_blank\s*"/';
                preg_match($pattern, $tag2, $match, PREG_OFFSET_CAPTURE);
                if (count($match) < 1) {
                    $noFollow .= ' target="_blank" ';
                }

                $pattern = '/rel\s*=\s*"\s*[n|d]ofollow\s*"/';
                preg_match($pattern, $tag2, $match, PREG_OFFSET_CAPTURE);
                if (count($match) < 1) {
                    $noFollow .= ' rel="nofollow" ';
                }

                $pos = strpos($url, $srcUrl);
                if ($pos === false) {
                    $tag = rtrim($tag, '>');
                    $tag .= $noFollow . '>';
                    $content = str_replace($tag2, $tag, $content);
                }
            }
        }
    }
    $content = str_replace(']]>', ']]>', $content);
    return $content;
}
add_filter('the_content', 'kratos_nofollow');

/**
 * 特色图
 */
if ( function_exists( 'add_image_size' ) ){  
    add_image_size( 'kratos-thumb', 750);
}  
function kratos_thumbnail() {
    global $post;
    $img_id = get_post_thumbnail_id();
    $img_url = wp_get_attachment_image_src($img_id,'kratos-entry-thumb');
    $img_url = $img_url[0];
    if ( has_post_thumbnail() ) {
        echo '<a href="'.get_permalink().'"><img src="'.$img_url.'" /></a>';
    } else {
        $content = $post->post_content;
        $img_preg = "/<img (.*?)src=\"(.+?)\".*?>/";
        preg_match($img_preg,$content,$img_src);
        $img_count=count($img_src)-1;
        if (isset($img_src[$img_count]))
        $img_val = $img_src[$img_count];
        if(!empty($img_val)){
            echo '<a href="'.get_permalink().'"><img src="'.$img_val.'" /></a>';
        } else {
             echo '<a href="'.get_permalink().'"><img src="'. kratos_option('post_default_thumbnail', get_template_directory_uri() . '/assets/img/default.jpg') .'" /></a>';
        }
    }  
}

/**
 * 文章阅读数
 */
function kratos_set_post_views()
{
    if (is_singular())
    {
      global $post;
      $post_ID = $post->ID;
      if($post_ID)
      {
          $post_views = (int)get_post_meta($post_ID, 'views', true);
          if(!update_post_meta($post_ID, 'views', ($post_views+1)))
          {
            add_post_meta($post_ID, 'views', 1, true);
          }
      }
    }
}
add_action('wp_head', 'kratos_set_post_views');

function kratos_get_post_views($before = '', $after = '', $echo = 1)
{
  global $post;
  $post_ID = $post->ID;
  $views = (int)get_post_meta($post_ID, 'views', true);
  if ($echo) echo $before, number_format($views), $after;
  else return $views;
}

/**
 * 文章分享图
 */
function kratos_thumbnail_url(){
    global $post;
    if (has_post_thumbnail($post->ID)) {
        $post_thumbnail_id = get_post_thumbnail_id( $post );
        $img = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );
        $img = $img[0];
    }else{
        $content = $post->post_content;
        preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
        if (!empty($strResult[1])) {
            $img = $strResult[1][0];
        }else{
            $img = kratos_option('post_default_thumbnail', get_template_directory_uri() . '/assets/img/default.jpg');
        }
    };
    return $img;
}

function share_post_image(){
    global $post;
    if (has_post_thumbnail($post->ID)) {
        $post_thumbnail_id = get_post_thumbnail_id( $post->ID );
        $img = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );
        $img = $img[0];
    }else{
        $content = $post->post_content;
        preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
        if (!empty($strResult[1])) {
            $img = $strResult[1][0];
        }else{
            $img = '';
        }
    }
    return $img;
}

/**
 * 文章简介截取
 */
function kratos_excerpt_length($length) {
    return 110;
}
add_filter('excerpt_length', 'kratos_excerpt_length');

function kratos_excerpt_more($more) {
    return '……';
}
add_filter('excerpt_more', 'kratos_excerpt_more');

/**
 * 文章点赞数
 */
function kratos_love(){
    global $wpdb,$post;
    $id = $_POST["um_id"];
    $action = $_POST["um_action"];
    if ( $action == 'love'){
        $raters = get_post_meta($id,'love',true);
        $expire = time() + 99999999;
        $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
        setcookie('love_'.$id,$id,$expire,'/',$domain,false);
        if (!$raters || !is_numeric($raters)) {
            update_post_meta($id, 'love', 1);
        } 
        else {
            update_post_meta($id, 'love', ($raters + 1));
        }
        echo get_post_meta($id,'love',true);
    } 
    die;
}
add_action('wp_ajax_nopriv_love', 'kratos_love');
add_action('wp_ajax_love', 'kratos_love');

add_filter( 'private_title_format', 'kratos_private_title_format' );
add_filter( 'protected_title_format', 'kratos_private_title_format' );
 
function kratos_private_title_format( $format ) {
    return '%s';
}

/**
 * 加密类型文章
 */
add_filter( 'the_password_form', 'custom_password_form' );
function custom_password_form() {
    $url = get_option('siteurl');
    global $post; $label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID ); $o = '
    <form class="protected-post-form" action="' . $url . '/wp-login.php?action=postpass" method="post">
        <div class="panel panel-pwd">
            <div class="panel-body text-center">
                <img class="post-pwd" src="' . get_template_directory_uri() . '/assets/img/fingerprint.png"><br />
                <h4 style="font-size: 1.2rem; margin-bottom: 20px;">这是一篇受保护的文章，请输入阅读密码！</h4>
                <div class="form-group">
                    <div class="input-group"><input class="form-control" placeholder="输入阅读密码" id="'.$label.'" name="post_password" type="password" size="20" required /></div>
                </div>
                <div class="comment-form" style="margin-top:15px;"><button id="generate" class="btn btn-primary btn-pwd" name="Submit" type="submit">确认</button></div>
            </div>
        </div>
    </form>';
return $o;
}

/**
 * 短代码
 */
function success($atts, $content=null, $code="") {
    $return = '<div class="alert alert-success">';
    $return .= $content;
    $return .= '</div>';
    return $return;
}
add_shortcode('success' , 'success' );

function info($atts, $content=null, $code="") {
    $return = '<div class="alert alert-info">';
    $return .= $content;
    $return .= '</div>';
    return $return;
}
add_shortcode('info' , 'info' );

function warning($atts, $content=null, $code="") {
    $return = '<div class="alert alert-warning">';
    $return .= $content;
    $return .= '</div>';
    return $return;
}
add_shortcode('warning' , 'warning' );

function danger($atts, $content=null, $code="") {
    $return = '<div class="alert alert-danger">';
    $return .= $content;
    $return .= '</div>';
    return $return;
}
add_shortcode('danger' , 'danger' );

function wymusic($atts, $content=null, $code="") {
    $return = '<iframe class="" style="width:100%" frameborder="no" border="0" marginwidth="0" marginheight="0" height=86 src="//music.163.com/outchain/player?type=2&id=';
    $return .= $content;
    $return .= '&auto='. kratos_option('global_music163') .'&height=66"></iframe>';
    return $return;
}
add_shortcode('music' , 'wymusic' );

function bdbtn($atts, $content=null, $code="") {
    $return = '<a class="downbtn" href="';
    $return .= $content;
    $return .= '" target="_blank"><svg class="icon" aria-hidden="true"><use xlink:href="#i-download"></use></svg> 本地下载</a>';
    return $return;
}
add_shortcode('bdbtn' , 'bdbtn' );

function ypbtn($atts, $content=null, $code="") {
    $return = '<a class="downbtn downcloud" href="';
    $return .= $content;
    $return .= '" target="_blank"><svg class="icon" aria-hidden="true"><use xlink:href="#i-xiazai"></use></svg> 云盘下载</a>';
    return $return;
}
add_shortcode('ypbtn' , 'ypbtn' );

function nrtitle($atts, $content=null, $code="") {
    $return = '<h2>';
    $return .= $content;
    $return .= '</h2>';
    return $return;
}
add_shortcode('title' , 'nrtitle' );

function kbd($atts, $content=null, $code="") {
    $return = '<kbd>';
    $return .= $content;
    $return .= '</kbd>';
    return $return;
}
add_shortcode('kbd' , 'kbd' );

function nrmark($atts, $content=null, $code="") {
    $return = '<mark>';
    $return .= $content;
    $return .= '</mark>';
    return $return;
}
add_shortcode('mark' , 'nrmark' );

function striped($atts, $content=null, $code="") {
    $return = '<div class="progress progress-striped active"><div class="progress-bar" style="width: ';
    $return .= $content;
    $return .= '%;"></div></div>';
    return $return;
}
add_shortcode('striped' , 'striped' );

function successbox($atts, $content=null, $code="") {
    extract(shortcode_atts(array("title"=>'标题内容'),$atts));
    $return = '<div class="panel panel-success"><div class="panel-heading"><h3 class="panel-title">';
    $return .= $title;
    $return .= '</h3></div><div class="panel-body">';
    $return .= $content;
    $return .= '</div></div>';
    return $return;
}
add_shortcode('successbox' , 'successbox' );

function infobox($atts, $content=null, $code="") {
    extract(shortcode_atts(array("title"=>'标题内容'),$atts));
    $return = '<div class="panel panel-info"><div class="panel-heading"><h3 class="panel-title">';
    $return .= $title;
    $return .= '</h3></div><div class="panel-body">';
    $return .= $content;
    $return .= '</div></div>';
    return $return;
}
add_shortcode('infobox' , 'infobox' );

function warningbox($atts, $content=null, $code="") {
    extract(shortcode_atts(array("title"=>'标题内容'),$atts));
    $return = '<div class="panel panel-warning"><div class="panel-heading"><h3 class="panel-title">';
    $return .= $title;
    $return .= '</h3></div><div class="panel-body">';
    $return .= $content;
    $return .= '</div></div>';
    return $return;
}
add_shortcode('warningbox' , 'warningbox' );

function dangerbox($atts, $content=null, $code="") {
    extract(shortcode_atts(array("title"=>'标题内容'),$atts));
    $return = '<div class="panel panel-danger"><div class="panel-heading"><h3 class="panel-title">';
    $return .= $title;
    $return .= '</h3></div><div class="panel-body">';
    $return .= $content;
    $return .= '</div></div>';
    return $return;
}
add_shortcode('dangerbox' , 'dangerbox' );

function youku($atts, $content=null, $code="") {
    $return = '<div class="video-container"><iframe height="498" width="750" src="//player.youku.com/embed/';
    $return .= $content;
    $return .= '" frameborder="0" allowfullscreen="allowfullscreen"></iframe></div>';
    return $return;
}
add_shortcode('youku' , 'youku' );

function vqq($atts, $content=null, $code="") {
    extract(shortcode_atts(array("auto"=>'0'),$atts));
    $return = '<div class="video-container"><iframe frameborder="0" width="640" height="498" src="//v.qq.com/txp/iframe/player.html?vid=';
    $return .= $content;
    $return .= '&tiny=0&auto=';
    $return .= $auto;
    $return .= '" allowfullscreen></iframe></div>';
    return $return;
}
add_shortcode('vqq' , 'vqq' );

function youtube($atts, $content=null, $code="") {
    $return = '<div class="video-container"><iframe height="498" width="750" src="https://www.youtube.com/embed/';
    $return .= $content;
    $return .= '" frameborder="0" allowfullscreen="allowfullscreen"></iframe></div>';
    return $return;
}
add_shortcode('youtube' , 'youtube' );

function bilibili($atts, $content=null, $code="") {
    $return = '<div class="video-container"><iframe src="//player.bilibili.com/player.html?aid=';
    $return .= $content;
    $return .= '" scrolling="no" border="0" frameborder="no" framespacing="0" allowfullscreen="true"> </iframe></div>';
    return $return;
}
add_shortcode('bilibili' , 'bilibili' );

add_action('init', 'more_button_a');
function more_button_a() {
   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
     return;
   }
   if ( get_user_option('rich_editing') == 'true' ) {
     add_filter( 'mce_external_plugins', 'add_plugin' );
     add_filter( 'mce_buttons', 'register_button' );
   }
}

add_action('init', 'more_button_b');
function more_button_b() {
   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
     return;
   }
   if ( get_user_option('rich_editing') == 'true' ) {
     add_filter( 'mce_external_plugins', 'add_plugin_b' );
     add_filter( 'mce_buttons_3', 'register_button_b' );
   }
}

function register_button( $buttons ) {
    array_push( $buttons, " ", "title" );
    array_push( $buttons, " ", "kbd" );
    array_push( $buttons, " ", "mark" );
    array_push( $buttons, " ", "striped" );
    array_push( $buttons, " ", "bdbtn" );
    array_push( $buttons, " ", "ypbtn" );
    array_push( $buttons, " ", "music" );
    array_push( $buttons, " ", "youku" );
    array_push( $buttons, " ", "vqq" );
    array_push( $buttons, " ", "youtube" );
    array_push( $buttons, " ", "bilibili" );
    return $buttons;
}

function register_button_b( $buttons ) {
    array_push( $buttons, " ", "success" );
    array_push( $buttons, " ", "info" );
    array_push( $buttons, " ", "warning" );
    array_push( $buttons, " ", "danger" );
    array_push( $buttons, " ", "successbox" );
    array_push( $buttons, " ", "infoboxs" );
    array_push( $buttons, " ", "warningbox" );
    array_push( $buttons, " ", "dangerbox" );
    return $buttons;
}

function add_plugin( $plugin_array ) {
    $plugin_array['title'] = get_bloginfo( 'template_url' ) . '/assets/js/buttons/more.js';
    $plugin_array['kbd'] = get_bloginfo( 'template_url' ) . '/assets/js/buttons/more.js';
    $plugin_array['mark'] = get_bloginfo( 'template_url' ) . '/assets/js/buttons/more.js';
    $plugin_array['striped'] = get_bloginfo( 'template_url' ) . '/assets/js/buttons/more.js';
    $plugin_array['bdbtn'] = get_bloginfo( 'template_url' ) . '/assets/js/buttons/more.js';
    $plugin_array['ypbtn'] = get_bloginfo( 'template_url' ) . '/assets/js/buttons/more.js';
    $plugin_array['music'] = get_bloginfo( 'template_url' ) . '/assets/js/buttons/more.js';
    $plugin_array['youku'] = get_bloginfo( 'template_url' ) . '/assets/js/buttons/more.js';
    $plugin_array['vqq'] = get_bloginfo( 'template_url' ) . '/assets/js/buttons/more.js';
    $plugin_array['youtube'] = get_bloginfo( 'template_url' ) . '/assets/js/buttons/more.js';
    $plugin_array['bilibili'] = get_bloginfo( 'template_url' ) . '/assets/js/buttons/more.js';
    return $plugin_array;
}

function add_plugin_b( $plugin_array ) {
    $plugin_array['success'] = get_bloginfo( 'template_url' ) . '/assets/js/buttons/more.js';
    $plugin_array['info'] = get_bloginfo( 'template_url' ) . '/assets/js/buttons/more.js';
    $plugin_array['warning'] = get_bloginfo( 'template_url' ) . '/assets/js/buttons/more.js';
    $plugin_array['danger'] = get_bloginfo( 'template_url' ) . '/assets/js/buttons/more.js';
    $plugin_array['successbox'] = get_bloginfo( 'template_url' ) . '/assets/js/buttons/more.js';
    $plugin_array['infoboxs'] = get_bloginfo( 'template_url' ) . '/assets/js/buttons/more.js';
    $plugin_array['warningbox'] = get_bloginfo( 'template_url' ) . '/assets/js/buttons/more.js';
    $plugin_array['dangerbox'] = get_bloginfo( 'template_url' ) . '/assets/js/buttons/more.js';
    return $plugin_array;
}

function add_more_buttons($buttons) {
        $buttons[] = 'hr';
        $buttons[] = 'fontselect';
        $buttons[] = 'fontsizeselect';
        $buttons[] = 'styleselect';
    return $buttons;
}
add_filter("mce_buttons_2", "add_more_buttons");