<?php

function getrobots()
{
    $site_url = parse_url(site_url());
    $path = (!empty($site_url['path'])) ? $site_url['path'] : '';

    $robots = "User-agent: *\n\n";
    $robots .= "Disallow: $path/wp-admin/\n";
    $robots .= "Disallow: $path/wp-includes/\n";
    $robots .= "Disallow: $path/wp-content/plugins/\n";
    $robots .= "Disallow: $path/wp-content/themes/\n";

    return $robots;
}

function kratos_options()
{

    $seorobots = '<a href="' . home_url() . '/robots.txt" target="_blank">robots.txt</a>';
    $seoreading = '<a href="' . admin_url('options-reading.php') . '" target="_blank">' . __('设置-阅读-对搜索引擎的可见性', 'kratos') . '</a>';

    $options = array();

    // ! 全局配置
    $options[] = array(
        'name' => __('全局配置', 'kratos'),
        'type' => 'heading',
    );

    $options[] = array(
        'name' => __('悼念模式', 'kratos'),
        'desc' => __('在国家公祭日或悼念某位名人时可以开启，全站黑白显示效果', 'kratos'),
        'id' => 'global_mourning',
        'std' => '0',
        'type' => 'checkbox',
    );

	$options[] = array(
		'name' => __('站点 Logo', 'kratos'),
		'desc' => __('留空则显示文字标题，推荐图片尺寸 180 x 50 像素', 'kratos'),
		'id' => 'global_logo',
		'type' => 'upload'
	);

	$options[] = array(
		'name' => __('背景颜色', 'kratos'),
		'desc' => __('针对整个站点背景颜色控制', 'kratos'),
		'id' => 'global_background_color',
		'std' => '#f5f5f5',
        'type' => 'color'
    );

    $options[] = array(
		'name' => __('头部图片', 'kratos'),
        'id' => 'global_background_img',
        'std' => get_template_directory_uri() . '/assets/img/background.jpg',
        'type' => 'upload'
    );

    $options[] = array(
		'name' => __('首页标题', 'kratos'),
		'id' => 'global_background_title',
		'std' => 'Kratos',
        'type' => 'text'
    );

	$options[] = array(
		'name' => __('首页简介', 'kratos'),
		'id' => 'global_background_abstract',
		'std' => 'A responsible theme for WordPress',
        'type' => 'text'
    );

	$options[] = array(
		'name' => __('网易云音乐', 'kratos'),
		'desc' => __('启用网易云音乐自动播放功能', 'kratos'),
		'id' => 'global_music163',
		'std' => '0',
        'type' => 'checkbox'
    );
    
	$options[] = array(
		'name' => __('访客留言', 'kratos'),
		'desc' => __('在网页右下角显示访客留言按钮', 'kratos'),
		'id' => 'global_guestbook',
		'std' => '0',
        'type' => 'checkbox'
    );

	$options[] = array(
		'name' => __('访客留言地址', 'kratos'),
		'desc' => __('输入您的访客留言页面的 URL 链接', 'kratos'),
        'id' => 'global_guestbook_url',
        'class' => 'hidden',
        'type' => 'text'
    );

	$options[] = array(
		'name' => __('个人微信', 'kratos'),
		'desc' => __('在网页右下角显示个人微信二维码', 'kratos'),
		'id' => 'global_weichat',
		'std' => '0',
        'type' => 'checkbox'
    );

	$options[] = array(
		'name' => __('微信二维码', 'kratos'),
		'desc' => __('微信二维码图片，图片要大于 150 x 150 像素', 'kratos'),
        'id' => 'global_weichat_img',
        'class' => 'hidden',
        'type' => 'upload'
    );

    // ! 文章配置
    $options[] = array(
        'name' => __('文章配置', 'kratos'),
        'type' => 'heading',
    );

    $options[] = array(
		'name' => __('文章特色图片', 'kratos'),
		'desc' => __('在文章没有特色图片的时候显示的默认图片', 'kratos'),
		'id' => 'post_default_thumbnail',
		'std' => get_template_directory_uri() . '/assets/img/default.jpg',
        'type' => 'upload'
    );

    $options[] = array(
		'name' => __('文章简介字数', 'kratos'),
		'id' => 'post_trim',
		'std' => '110',
        'type' => 'text'
    );

	$options[] = array(
		'name' => __('版权声明', 'kratos'),
		'desc' => __('开启后会在文章底部显示 CC BY-SA 4.0 声明', 'kratos'),
		'id' => 'post_cc',
		'std' => '1',
        'type' => 'checkbox'
    );

	$options[] = array(
		'name' => __('分享按钮', 'kratos'),
		'desc' => __('文章底部显示分享按钮', 'kratos'),
		'id' => 'post_share',
		'std' => '1',
        'type' => 'checkbox'
    );

	$options[] = array(
		'name' => __('打赏按钮', 'kratos'),
		'desc' => __('文章底部显示打赏按钮', 'kratos'),
		'id' => 'post_donate',
		'std' => '0',
        'type' => 'checkbox'
    );

	$options[] = array(
		'name' => __('打赏连接', 'kratos'),
		'desc' => __('打赏介绍页面的 URL 连接', 'kratos'),
		'id' => 'post_donate_links',
		'class' => 'hidden',
        'type' => 'text'
    );

    // ! 优化配置
    $options[] = array(
        'name' => __('优化配置', 'kratos'),
        'type' => 'heading',
    );

    $options[] = array(
        'name' => __('优化 Head 信息', 'kratos'),
        'desc' => __('优化 wp_head() 中调用的 WordPress 版本、Windows Live Writer、Feed link、Shortlink 等信息', 'kratos'),
        'id' => 'optimize_wphead',
        'std' => '1',
        'type' => 'checkbox',
    );

    $options[] = array(
        'name' => __('优化 Gravatar 服务器', 'kratos'),
        'desc' => __('优化所有的 Gravatar 地址，强制替换为 secure.gravatar.com 并开启 HTTPS', 'kratos'),
        'id' => 'optimize_gravatar',
        'std' => '1',
        'type' => 'checkbox',
    );

    $options[] = array(
        'name' => __('优化页面伪静态', 'kratos'),
        'desc' => __('为页面添加 .html 伪静态后缀，该功能需配置固定链接为自定义结构才可生效', 'kratos'),
        'id' => 'optimize_pagepermalink',
        'std' => '1',
        'type' => 'checkbox',
    );

    $options[] = array(
        'name' => __('禁用 WordPress 拼写修正', 'kratos'),
        'desc' => __('禁用 WordPress 执行的 capital_P_dangit() 方法，该方法用来更正在正文、标题、评论中错误的 WordPress 拼写', 'kratos'),
        'id' => 'optimize_capitalpdangit',
        'std' => '1',
        'type' => 'checkbox',
    );

    $options[] = array(
        'name' => __('禁用 WordPress 文章修订功能', 'kratos'),
        'desc' => __('禁用 WordPress 所有文章类型（Post、Page、Attachment）的修订版本', 'kratos'),
        'id' => 'optimize_revisions',
        'std' => '1',
        'type' => 'checkbox',
    );

    $options[] = array(
        'name' => __('禁用 Google 字体', 'kratos'),
        'desc' => __('禁用后台的谷歌字体，由于谷歌服务器在国外，所以会影响后台访问速度', 'kratos'),
        'id' => 'optimize_googlefonts',
        'std' => '1',
        'type' => 'checkbox',
    );

    $options[] = array(
        'name' => __('禁用 Emoji 表情', 'kratos'),
        'desc' => __('禁用在 s.w.org 获取图片，由于服务器在国外，所以会影响访问速度，但数据库使用 utf8mb4 字符集时，emoji 将存储到数据库中', 'kratos'),
        'id' => 'optimize_emoji',
        'std' => '1',
        'type' => 'checkbox',
    );

    $options[] = array(
        'name' => __('禁用 Trackbacks 功能', 'kratos'),
        'desc' => __('由于 Trackbacks 被滥用，所以会导致网站会产生大量的垃圾信息', 'kratos'),
        'id' => 'optimize_trackbacks',
        'std' => '1',
        'type' => 'checkbox',
    );

    $options[] = array(
        'name' => __('禁用 Auto Embeds 功能', 'kratos'),
        'desc' => __('Auto Embeds 支持的大多是国外视频或图片网站，所以建议禁用 Auto Embeds 功能', 'kratos'),
        'id' => 'optimize_autoembeds',
        'std' => '1',
        'type' => 'checkbox',
    );

    $options[] = array(
        'name' => __('禁用 Post Embeds 功能', 'kratos'),
        'desc' => __('Post Embeds 功能可以在任意 WordPress 站点用嵌入的方式插入 WordPress 文章，如果不需要建议禁用', 'kratos'),
        'id' => 'optimize_postembeds',
        'std' => '1',
        'type' => 'checkbox',
    );

    $options[] = array(
        'name' => __('禁用 Gutenberg 编辑器', 'kratos'),
        'desc' => __('为方便还不能适应 Gutenberg 编辑器的用户，这个功能可以在 2021 年之前，可以将编辑器恢复为经典编辑器', 'kratos'),
        'id' => 'optimize_gutenberg',
        'std' => '0',
        'type' => 'checkbox',
    );

    // ! 邮件配置
    $options[] = array(
        'name' => __('邮件配置', 'kratos'),
        'type' => 'heading',
    );

    $options[] = array(
        'name' => __('SMTP 服务', 'kratos'),
        'desc' => __('是否启用 SMTP 服务，开启后根据服务商提供的信息填写配置', 'kratos'),
        'id' => 'mail_smtpconfig',
        'std' => '0',
        'type' => 'checkbox'
    );

    $options[] = array(
        'name' => __('SMTPSecure 配置', 'kratos'),
        'desc' => __('该配置与 SMTP 服务器端口对应，如果需要验证 SSL ，则开启这个选项', 'kratos'),
        'id' => 'mail_smtpsecure',
        'std' => '0',
        'class' => 'hidden',
        'type' => 'checkbox',
    );

    $options[] = array(
        'name' => __('SMTP 服务器地址', 'kratos'),
        'id' => 'mail_host',
        'class' => 'hidden',
        'type' => 'text'
    );

    $options[] = array(
        'name' => __('SMTP 服务器端口', 'kratos'),
        'id' => 'mail_port',
        'class' => 'hidden',
        'type' => 'text'
    );

    $options[] = array(
        'name' => __('发信人名称', 'kratos'),
        'id' => 'mail_name',
        'class' => 'hidden',
        'type' => 'text'
    );

    $options[] = array(
        'name' => __('邮箱帐号', 'kratos'),
        'id' => 'mail_username',
        'class' => 'hidden',
        'type' => 'text'
    );

    $options[] = array(
        'name' => __('邮箱密码', 'kratos'),
        'id' => 'mail_passwd',
        'class' => 'hidden',
        'type' => 'text'
    );

    // ! SEO 配置
    $options[] = array(
        'name' => __('SEO 配置', 'kratos'),
        'type' => 'heading',
    );

    $options[] = array(
        'name' => __('Keywords 配置', 'kratos'),
        'id' => 'seo_keywords',
        'desc' => __('每个关键词之间需要用英文逗号隔开', 'kratos'),
        'type' => 'text',
    );

    $options[] = array(
        'name' => __('Description 配置', 'kratos'),
        'id' => 'seo_description',
        'type' => 'textarea',
    );

    $options[] = array(
        'name' => __('robots.txt 配置', 'kratos'),
        'desc' => __('- 需要 ', 'kratos') . $seoreading . __(' 是开启的状态，下面的配置才会生效', 'kratos'),
        'type' => 'info',
    );

    $options[] = array(
        'desc' => __('- 如果网站根目录下已经有 robots.txt 文件，下面的配置不会生效', 'kratos'),
        'type' => 'info',
    );

    $options[] = array(
        'desc' => __('- 点击 ', 'kratos') . $seorobots . __(' 查看配置是否生效，如果网站开启了 CDN，可能需要刷新缓存才会生效', 'kratos'),
        'type' => 'info',
    );

    $options[] = array(
        'id' => 'seo_robots',
        'std' => getrobots(),
        'type' => 'textarea',
    );

    // ! 社交配置
    $options[] = array(
        'name' => __('社交配置', 'kratos'),
        'type' => 'heading',
    );

    $options[] = array(
        'name' => __('页脚社交图标', 'kratos'),
        'desc' => __('在页脚显示社交图标，在相应的选项中填写 URL 地址即可开启，留空则不开启', 'kratos'),
        'id' => 'social_config',
        'std' => '0',
        'type' => 'checkbox'
    );

    $options[] = array(
        'name' => __('新浪微博', 'kratos'),
        'id' => 'social_weibo',
        'class' => 'hidden',
        'type' => 'text',
    );

    $options[] = array(
        'name' => __('QQ 空间', 'kratos'),
        'id' => 'social_qzone',
        'class' => 'hidden',
        'type' => 'text',
    );

    $options[] = array(
        'name' => __('Twitter', 'kratos'),
        'id' => 'social_twitter',
        'class' => 'hidden',
        'type' => 'text',
    );

    $options[] = array(
        'name' => __('Facebook', 'kratos'),
        'id' => 'social_facebook',
        'class' => 'hidden',
        'type' => 'text',
    );

    $options[] = array(
        'name' => __('Steam', 'kratos'),
        'id' => 'social_steam',
        'class' => 'hidden',
        'type' => 'text',
    );

    $options[] = array(
        'name' => __('Github', 'kratos'),
        'id' => 'social_github',
        'class' => 'hidden',
        'type' => 'text',
    );

    // ! 页脚配置
    $options[] = array(
        'name' => __('页脚配置', 'kratos'),
        'type' => 'heading',
    );

	$options[] = array(
		'name' => __('工信部备案信息', 'kratos'),
		'desc' => __('输入您的工信部备案号', 'kratos'),
		'id' => 'footer_miitbeian',
        'type' => 'text'
    );

	$options[] = array(
		'name' => __('公安网备案信息', 'kratos'),
		'desc' => __('输入您的公安网备案号', 'kratos'),
		'id' => 'footer_beian',
        'type' => 'text'
    );

	$options[] = array(
		'name' => __('公安网备案连接', 'kratos'),
		'desc' => __('输入您的公安网备案的链接地址', 'kratos'),
		'id' => 'footer_beian_url',
        'type' => 'text'
    );
    
    $options[] = array(
        'name' => __('站点统计', 'kratos'),
        'desc' => __('这里内容直接包裹在 &lt;script&gt; 标签中，直接写 js 代码即可', 'kratos'),
        'id' => 'footer_statistics',
        'type' => 'textarea',
    );

    // ! 广告配置
    $options[] = array(
        'name' => __('广告配置', 'kratos'),
        'type' => 'heading',
    );

    $options[] = array(
        'name' => __('广告位置', 'kratos'),
        'desc' => __('文章顶部广告栏', 'kratos'),
        'id' => 'ad_top',
        'std' => '0',
        'type' => 'checkbox'
    );

    $options[] = array(
        'desc' => __('文章底部广告栏', 'kratos'),
        'id' => 'ad_bottom',
        'std' => '0',
        'type' => 'checkbox'
    );

    $options[] = array(
		'name' => __('顶部广告连接', 'kratos'),
		'desc' => __('输入您的广告链接地址', 'kratos'),
        'id' => 'ad_top_url',
        'class' => 'hidden',
        'type' => 'text'
    );

    $options[] = array(
		'name' => __('顶部广告图片', 'kratos'),
        'id' => 'ad_top_img',
        'std' => get_template_directory_uri() . '/assets/img/ad.png',
        'class' => 'hidden',
        'type' => 'upload'
    );

    $options[] = array(
		'name' => __('底部广告连接', 'kratos'),
		'desc' => __('输入您的广告链接地址', 'kratos'),
        'id' => 'ad_bottom_url',
        'class' => 'hidden',
        'type' => 'text'
    );

    $options[] = array(
		'name' => __('底部广告图片', 'kratos'),
        'id' => 'ad_bottom_img',
        'std' => get_template_directory_uri() . '/assets/img/ad.png',
        'class' => 'hidden',
        'type' => 'upload'
    );

    // ! 404 配置
    $options[] = array(
        'name' => __('404 配置', 'kratos'),
        'type' => 'heading',
    );

    $options[] = array(
		'name' => __('背景图片', 'kratos'),
        'id' => 'error_image',
        'std' => get_template_directory_uri() . '/assets/img/404.jpg',
        'type' => 'upload'
    );

    $options[] = array(
		'name' => __('页面标题', 'kratos'),
        'id' => 'error_title',
        'std' => '这里已经是废墟 什么东西都没有',
        'type' => 'text'
    );

    $options[] = array(
		'name' => __('页面简介', 'kratos'),
        'id' => 'error_notice',
        'std' => 'That page can not be found',
        'type' => 'text'
    );

    return $options;
}
