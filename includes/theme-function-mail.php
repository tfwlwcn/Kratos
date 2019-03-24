<?php

/**
 * 邮件配置
 */
if (kratos_option('mail_smtpconfig', false)) {
    function kratos_mail_smtp($phpmailer)
    {
        $phpmailer->IsSMTP();
        $phpmailer->FromName = kratos_option('mail_name');
        $phpmailer->Host = kratos_option('mail_host');
        $phpmailer->Port = kratos_option('mail_port');
        $phpmailer->Username = kratos_option('mail_username');
        $phpmailer->Password = kratos_option('mail_passwd');
        $phpmailer->From = kratos_option('mail_username');
        $phpmailer->SMTPAuth = true;
        if (kratos_option('mail_smtpsecure', false)){
            $phpmailer->SMTPSecure = 'ssl';
        }
    }

    add_action('phpmailer_init', 'kratos_mail_smtp');
}
/**
 * 审核、回复邮件模板
 */
function kratos_comment_approved($comment)
{
    if (is_email($comment->comment_author_email)) {
        $wp_email = kratos_option('mail_username');
        $to = trim($comment->comment_author_email);
        $post_link = get_permalink($comment->comment_post_ID);
        $subject = __('[通知]您的留言已经通过审核', 'kratos');
        $message = '
            <div style="background:#ececec;width: 100%;padding: 50px 0;text-align:center;">
            <div style="background:#fff;width:750px;text-align:left;position:relative;margin:0 auto;font-size:14px;line-height:1.5;">
                    <div style="zoom:1;padding:25px 40px;background:#518bcb; border-bottom:1px solid #467ec3;">
                        <h1 style="color:#fff; font-size:25px;line-height:30px; margin:0;"><a href="' . get_option('home') . '" style="text-decoration: none;color: #FFF;">' . htmlspecialchars_decode(get_option('blogname'), ENT_QUOTES) . '</a></h1>
                    </div>
                <div style="padding:35px 40px 30px;">
                    <h2 style="font-size:18px;margin:5px 0;">Hi ' . trim($comment->comment_author) . ':</h2>
                    <p style="color:#313131;line-height:20px;font-size:15px;margin:20px 0;">' . __("您有一条留言通过了管理员的审核，摘要信息如下表。", "kratos") . '</p>
                        <table cellspacing="0" style="font-size:14px;text-align:center;border:1px solid #ccc;table-layout:fixed;width:500px;">
                            <thead>
                                <tr>
                                    <th style="padding:5px 0;text-indent:8px;border:1px solid #eee;border-width:0 1px 1px 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-weight:normal;color:#a0a0a0;background:#eee;border-color:#dfdfdf;" width="280px;">' . __("文章", "kratos") . '</th>
                                    <th style="padding:5px 0;text-indent:8px;border:1px solid #eee;border-width:0 1px 1px 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-weight:normal;color:#a0a0a0;background:#eee;border-color:#dfdfdf;" width="270px;">' . __("内容", "kratos") . '</th>
                                    <th style="padding:5px 0;text-indent:8px;border:1px solid #eee;border-width:0 1px 1px 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-weight:normal;color:#a0a0a0;background:#eee;border-color:#dfdfdf;" width="110px;">' . __("操作", "kratos") . '</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="padding:5px 0;text-indent:8px;border:1px solid #eee;border-width:0 1px 1px 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">《' . get_the_title($comment->comment_post_ID) . '》</td>
                                    <td style="padding:5px 0;text-indent:8px;border:1px solid #eee;border-width:0 1px 1px 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' . trim($comment->comment_content) . '</td>
                                    <td style="padding:5px 0;text-indent:8px;border:1px solid #eee;border-width:0 1px 1px 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><a href="' . get_comment_link($comment->comment_ID) . '" style="color:#1E5494;text-decoration:none;vertical-align:middle;" target="_blank">' . __("查看回复", "kratos") . '</a></td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                    <div style="font-size:13px;color:#a0a0a0;padding-top:10px">' . __("该邮件由系统自动发出，如果不是您本人操作，请忽略此邮件。", "kratos") . '</div>
                    <div class="qmSysSign" style="padding-top:20px;font-size:12px;color:#a0a0a0;">
                        <p style="color:#a0a0a0;line-height:18px;font-size:12px;margin:5px 0;">' . htmlspecialchars_decode(get_option('blogname'), ENT_QUOTES) . '</p>
                        <p style="color:#a0a0a0;line-height:18px;font-size:12px;margin:5px 0;"><span style="border-bottom:1px dashed #ccc;" t="5" times="">' . date("Y-m-d", time()) . '</span></p>
                    </div>
                </div>
            </div>
        </div>';
        $from = "From: \"" . htmlspecialchars_decode(get_option('blogname'), ENT_QUOTES) . "\" <$wp_email>";
        $headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
        wp_mail($to, $subject, $message, $headers);
    }
}
add_action('comment_unapproved_to_approved', 'kratos_comment_approved');

function kratos_comment_mail_notify($comment_id)
{
    $comment = get_comment($comment_id);
    $parent_id = $comment->comment_parent ? $comment->comment_parent : '';
    $spam_confirmed = $comment->comment_approved;
    if (($parent_id != '') && ($spam_confirmed != 'spam')) {
        $wp_email = kratos_option('mail_username');
        $to = trim(get_comment($parent_id)->comment_author_email);
        $subject = __('[通知]您的留言有了新的回复', 'kratos');
        $message = '
            <div style="background:#ececec;width: 100%;padding: 50px 0;text-align:center;">
            <div style="background:#fff;width:750px;text-align:left;position:relative;margin:0 auto;font-size:14px;line-height:1.5;">
                    <div style="zoom:1;padding:25px 40px;background:#518bcb; border-bottom:1px solid #467ec3;">
                        <h1 style="color:#fff; font-size:25px;line-height:30px; margin:0;"><a href="' . get_option('home') . '" style="text-decoration: none;color: #FFF;">' . htmlspecialchars_decode(get_option('blogname'), ENT_QUOTES) . '</a></h1>
                    </div>
                <div style="padding:35px 40px 30px;">
                    <h2 style="font-size:18px;margin:5px 0;">Hi ' . trim(get_comment($parent_id)->comment_author) . ':</h2>
                    <p style="color:#313131;line-height:20px;font-size:15px;margin:20px 0;">' . __("您有一条留言有了新的回复，摘要信息如下表。", "kratos") . '</p>
                        <table cellspacing="0" style="font-size:14px;text-align:center;border:1px solid #ccc;table-layout:fixed;width:500px;">
                            <thead>
                                <tr>
                                    <th style="padding:5px 0;text-indent:8px;border:1px solid #eee;border-width:0 1px 1px 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-weight:normal;color:#a0a0a0;background:#eee;border-color:#dfdfdf;" width="235px;">' . __("原文", "kratos") . '</th>
                                    <th style="padding:5px 0;text-indent:8px;border:1px solid #eee;border-width:0 1px 1px 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-weight:normal;color:#a0a0a0;background:#eee;border-color:#dfdfdf;" width="235px;">' . __("回复", "kratos") . '</th>
                                    <th style="padding:5px 0;text-indent:8px;border:1px solid #eee;border-width:0 1px 1px 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-weight:normal;color:#a0a0a0;background:#eee;border-color:#dfdfdf;" width="100px;">' . __("作者", "kratos") . '</th>
                                    <th style="padding:5px 0;text-indent:8px;border:1px solid #eee;border-width:0 1px 1px 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-weight:normal;color:#a0a0a0;background:#eee;border-color:#dfdfdf;" width="90px;" >' . __("操作", "kratos") . '</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="padding:5px 0;text-indent:8px;border:1px solid #eee;border-width:0 1px 1px 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' . trim(get_comment($parent_id)->comment_content) . '</td>
                                    <td style="padding:5px 0;text-indent:8px;border:1px solid #eee;border-width:0 1px 1px 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' . trim($comment->comment_content) . '</td>
                                    <td style="padding:5px 0;text-indent:8px;border:1px solid #eee;border-width:0 1px 1px 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' . trim($comment->comment_author) . '</td>
                                    <td style="padding:5px 0;text-indent:8px;border:1px solid #eee;border-width:0 1px 1px 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><a href="' . get_comment_link($comment->comment_ID) . '" style="color:#1E5494;text-decoration:none;vertical-align:middle;" target="_blank">' . __("查看回复", "kratos") . '</a></td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                    <div style="font-size:13px;color:#a0a0a0;padding-top:10px">' . __("该邮件由系统自动发出，如果不是您本人操作，请忽略此邮件。", "kratos") . '</div>
                    <div class="qmSysSign" style="padding-top:20px;font-size:12px;color:#a0a0a0;">
                        <p style="color:#a0a0a0;line-height:18px;font-size:12px;margin:5px 0;">' . htmlspecialchars_decode(get_option('blogname'), ENT_QUOTES) . '</p>
                        <p style="color:#a0a0a0;line-height:18px;font-size:12px;margin:5px 0;"><span style="border-bottom:1px dashed #ccc;" t="5" times="">' . date("Y-m-d", time()) . '</span></p>
                    </div>
                </div>
            </div>
        </div>';
        $from = "From: \"" . htmlspecialchars_decode(get_option('blogname'), ENT_QUOTES) . "\" <$wp_email>";
        $headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
        wp_mail($to, $subject, $message, $headers);
    }
}

add_action('comment_post', 'kratos_comment_mail_notify');

/**
 * 注册、找回密码邮件模板
 */
add_filter('password_change_email', '__return_false');
add_filter('wp_new_user_notification_email', '__return_false');

function kratos_retrieve_password_message($message, $key)
{
    add_filter('wp_mail_content_type', function () {return "text/html";});
    if (strpos($_POST['user_login'], '@')) {
        $user_data = get_user_by('email', trim($_POST['user_login']));
    } else {
        $login = trim($_POST['user_login']);
        $user_data = get_user_by('login', $login);
    }
    $message = '
    <div style="background:#ececec;width: 100%;padding: 50px 0;text-align:center;">
                <div style="background:#fff;width:750px;text-align:left;position:relative;margin:0 auto;font-size:14px;line-height:1.5;">
                        <div style="zoom:1;padding:25px 40px;background:#518bcb; border-bottom:1px solid #467ec3;">
                            <h1 style="color:#fff; font-size:25px;line-height:30px; margin:0;"><a href="' . get_option('home') . '" style="text-decoration: none;color: #FFF;">' . htmlspecialchars_decode(get_option('blogname'), ENT_QUOTES) . '</a></h1>
                        </div>
                    <div style="padding:35px 40px 30px;">
                        <h2 style="font-size:18px;margin:5px 0;">Hi ' . $user_data->display_name . ':</h2>
                        <p style="color:#313131;line-height:20px;font-size:15px;margin:20px 0;">' . __("您正在请求重置密码，摘要信息如下表。", "kratos") . '</p>
                            <table cellspacing="0" style="font-size:14px;text-align:center;border:1px solid #ccc;table-layout:fixed;width:500px;">
                                <thead>
                                    <tr>
                                        <th style="padding:5px 0;text-indent:8px;border:1px solid #eee;border-width:0 1px 1px 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-weight:normal;color:#a0a0a0;background:#eee;border-color:#dfdfdf;" width="260px;">' . __("账号", "kratos") . '</th>
                                        <th style="padding:5px 0;text-indent:8px;border:1px solid #eee;border-width:0 1px 1px 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-weight:normal;color:#a0a0a0;background:#eee;border-color:#dfdfdf;" width="260px;">' . __("邮箱", "kratos") . '</th>
                                        <th style="padding:5px 0;text-indent:8px;border:1px solid #eee;border-width:0 1px 1px 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-weight:normal;color:#a0a0a0;background:#eee;border-color:#dfdfdf;" width="140px;">' . __("操作", "kratos") . '</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="padding:5px 0;text-indent:8px;border:1px solid #eee;border-width:0 1px 1px 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' . trim($user_data->user_login) . '</td>
                                        <td style="padding:5px 0;text-indent:8px;border:1px solid #eee;border-width:0 1px 1px 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' . trim($user_data->user_email) . '</td>
                                        <td style="padding:5px 0;text-indent:8px;border:1px solid #eee;border-width:0 1px 1px 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><a href="' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_data->user_login), 'login') . '" style="color:#1E5494;text-decoration:none;vertical-align:middle;" target="_blank">' . __("立即重置", "kratos") . '</a></td>
                                    </tr>
                                </tbody>
                            </table>
                            <br>
                        <div style="font-size:13px;color:#a0a0a0;padding-top:10px">' . __("该邮件由系统自动发出，如果不是您本人操作，请忽略此邮件。", "kratos") . '</div>
                        <div class="qmSysSign" style="padding-top:20px;font-size:12px;color:#a0a0a0;">
                            <p style="color:#a0a0a0;line-height:18px;font-size:12px;margin:5px 0;">' . htmlspecialchars_decode(get_option('blogname'), ENT_QUOTES) . '</p>
                            <p style="color:#a0a0a0;line-height:18px;font-size:12px;margin:5px 0;"><span style="border-bottom:1px dashed #ccc;" t="5" times="">' . date("Y-m-d", time()) . '</span></p>
                        </div>
                    </div>
                </div>
            </div>
    ';
    return $message;
}

add_filter('retrieve_password_message', 'kratos_retrieve_password_message', null, 2);

function filter_retrieve_password_title( $title, $user_login, $user_data ) { 
    $title = __('[通知]重置账户密码', 'kratos');
    return $title; 
};

add_filter( 'retrieve_password_title', 'filter_retrieve_password_title', 10, 3 );

function kratos_pwd_register_mail($user_id)
{
    $user = get_user_by('id', $user_id);
    $blogname = get_option('blogname');
    $pwd = wp_generate_password(10, false);
    $user->user_pass = $pwd;
    $new_user_id = wp_update_user($user);
    $message = '
    <div style="background:#ececec;width: 100%;padding: 50px 0;text-align:center;">
                <div style="background:#fff;width:750px;text-align:left;position:relative;margin:0 auto;font-size:14px;line-height:1.5;">
                        <div style="zoom:1;padding:25px 40px;background:#518bcb; border-bottom:1px solid #467ec3;">
                            <h1 style="color:#fff; font-size:25px;line-height:30px; margin:0;"><a href="' . get_option('home') . '" style="text-decoration: none;color: #FFF;">' . htmlspecialchars_decode(get_option('blogname'), ENT_QUOTES) . '</a></h1>
                        </div>
                    <div style="padding:35px 40px 30px;">
                        <h2 style="font-size:18px;margin:5px 0;">Hi ' . $user->nickname . ':</h2>
                        <p style="color:#313131;line-height:20px;font-size:15px;margin:20px 0;">' . __("恭喜您注册成功，请使用下面的信息登录并修改密码。", "kratos") . '</p>
                            <table cellspacing="0" style="font-size:14px;text-align:center;border:1px solid #ccc;table-layout:fixed;width:500px;">
                                <thead>
                                    <tr>
                                        <th style="padding:5px 0;text-indent:8px;border:1px solid #eee;border-width:0 1px 1px 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-weight:normal;color:#a0a0a0;background:#eee;border-color:#dfdfdf;" width="180px;">' . __("账号", "kratos") . '</th>
                                        <th style="padding:5px 0;text-indent:8px;border:1px solid #eee;border-width:0 1px 1px 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-weight:normal;color:#a0a0a0;background:#eee;border-color:#dfdfdf;" width="180px;">' . __("邮箱", "kratos") . '</th>
                                        <th style="padding:5px 0;text-indent:8px;border:1px solid #eee;border-width:0 1px 1px 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-weight:normal;color:#a0a0a0;background:#eee;border-color:#dfdfdf;" width="180px;">' . __("密码", "kratos") . '</th>
                                        <th style="padding:5px 0;text-indent:8px;border:1px solid #eee;border-width:0 1px 1px 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-weight:normal;color:#a0a0a0;background:#eee;border-color:#dfdfdf;" width="120px;">' . __("操作", "kratos") . '</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="padding:5px 0;text-indent:8px;border:1px solid #eee;border-width:0 1px 1px 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' . trim($user->user_login) . '</td>
                                        <td style="padding:5px 0;text-indent:8px;border:1px solid #eee;border-width:0 1px 1px 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' . trim($user->user_email) . '</td>
                                        <td style="padding:5px 0;text-indent:8px;border:1px solid #eee;border-width:0 1px 1px 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' . trim($pwd) . '</td>
                                        <td style="padding:5px 0;text-indent:8px;border:1px solid #eee;border-width:0 1px 1px 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><a href="' . wp_login_url() . '" style="color:#1E5494;text-decoration:none;vertical-align:middle;" target="_blank">' . __("立即登录", "kratos") . '</a></td>
                                    </tr>
                                </tbody>
                            </table>
                            <br>
                        <div style="font-size:13px;color:#a0a0a0;padding-top:10px">' . __("该邮件由系统自动发出，如果不是您本人操作，请忽略此邮件。", "kratos") . '</div>
                        <div class="qmSysSign" style="padding-top:20px;font-size:12px;color:#a0a0a0;">
                            <p style="color:#a0a0a0;line-height:18px;font-size:12px;margin:5px 0;">' . htmlspecialchars_decode(get_option('blogname'), ENT_QUOTES) . '</p>
                            <p style="color:#a0a0a0;line-height:18px;font-size:12px;margin:5px 0;"><span style="border-bottom:1px dashed #ccc;" t="5" times="">' . date("Y-m-d", time()) . '</span></p>
                        </div>
                    </div>
                </div>
            </div>
    ';
    $headers = "Content-Type:text/html;charset=UTF-8\n";
    $mtitle = __('[通知]账户注册成功', 'kratos');
    wp_mail($user->user_email, $mtitle, $message, $headers);
}

add_action('user_register', 'kratos_pwd_register_mail', 101);