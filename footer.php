<?php
/**
 * The template for displaying the footer
 * 
 * @author Vtrois <seaton@vtrois.com>
 * @license GPL-3.0
 */
?>
                <footer class="kratos-footer">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 text-center content">
                                <?php if (kratos_option('social_config', false)) {?>
                                <div class="social-icons">
                                    <?php if (kratos_option('social_weibo')) {?>
                                        <a href="<?php echo kratos_option('social_weibo'); ?>" target="_blank" rel="nofollow">
                                        <svg class="icon" aria-hidden="true">
                                            <use xlink:href="#i-weibo"></use>
                                        </svg>
                                    </a>
                                    <?php } if (kratos_option('social_qzone')) {?>
                                        <a href="<?php echo kratos_option('social_qzone'); ?>" target="_blank" rel="nofollow">
                                        <svg class="icon" aria-hidden="true">
                                            <use xlink:href="#i-qzone"></use>
                                        </svg>
                                    </a>
                                    <?php }if (kratos_option('social_twitter')) {?>
                                        <a href="<?php echo kratos_option('social_twitter'); ?>" target="_blank" rel="nofollow">
                                        <svg class="icon" aria-hidden="true">
                                            <use xlink:href="#i-twitter"></use>
                                        </svg>
                                    </a>
                                    <?php } if (kratos_option('social_facebook')) {?>
                                        <a href="<?php echo kratos_option('social_facebook'); ?>" target="_blank" rel="nofollow">
                                        <svg class="icon" aria-hidden="true">
                                            <use xlink:href="#i-facebook"></use>
                                        </svg>
                                    </a>
                                    <?php } if (kratos_option('social_steam')) {?>
                                        <a href="<?php echo kratos_option('social_steam'); ?>" target="_blank" rel="nofollow">
                                        <svg class="icon" aria-hidden="true">
                                            <use xlink:href="#i-steam"></use>
                                        </svg>
                                    </a>
                                    <?php } if (kratos_option('social_github')) {?>
                                        <a href="<?php echo kratos_option('social_github'); ?>" target="_blank" rel="nofollow">
                                        <svg class="icon" aria-hidden="true">
                                            <use xlink:href="#i-github"></use>
                                        </svg>
                                    </a>
                                    <?php } ?>
                                </div>
                                <?php } ?>
                                <p>Copyright <?php echo date('Y'); ?> <a href="<?php echo get_option('home'); ?>"><?php bloginfo('name'); ?></a>. All Rights Reserved.</p>
                                <p>Theme <a href="https://github.com/vtrois/kratos" target="_blank" rel="nofollow">Kratos</a> made by <a href="https://www.vtrois.com/" target="_blank" rel="nofollow">Vtrois</a></p>
                                <?php if ( kratos_option('footer_miitbeian') || kratos_option('footer_beian') ) { ?>
                                    <p>
                                        <?php if (kratos_option('footer_miitbeian')) {?>
                                        <a href="http://www.miitbeian.gov.cn/" rel="nofollow" target="_blank"><?php echo kratos_option('footer_miitbeian'); ?></a>
                                        <?php } if (kratos_option('footer_beian')) {?>
                                        <a href="<?php echo kratos_option('footer_beian_url'); ?>" rel="nofollow" target="_blank"><?php echo kratos_option('footer_beian'); ?></a>
                                        <?php } ?>
                                    </p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </footer>
                <div class="cd-tool text-center">
                    <?php if ( kratos_option( 'global_guestbook' ) && kratos_option( 'global_weichat' ) ) { ?>
                        <a rel="nofollow" class="cd-gb-a" href="<?php echo kratos_option('global_guestbook_url'); ?>"><svg class="icon" aria-hidden="true"><use xlink:href="#i-book"></use></svg></a>	
                    <?php } elseif( kratos_option( 'global_guestbook' ) && !kratos_option( 'global_weichat' ) ){ ?>
                        <a rel="nofollow" class="cd-gb-b" href="<?php echo kratos_option('global_guestbook_url'); ?>"><svg class="icon" aria-hidden="true"><use xlink:href="#i-book"></use></svg></a>	
                    <?php } ?>
                    <?php if ( kratos_option( 'global_weichat' ) ) : ?>
                        <a id="weixin-img" class="cd-weixin"><svg class="icon" aria-hidden="true"><use xlink:href="#i-weixin"></use></svg><div id="weixin-pic"><img src="<?php echo kratos_option('global_weichat_img') ?>"></div></a>
                    <?php endif; ?>
                    <a class="cd-top "><svg class="icon" aria-hidden="true"><use xlink:href="#i-up"></use></svg></a>
                </div>
            </div>
        </div>
        <?php wp_footer(); ?>
        <script type="text/javascript"><?php echo kratos_option('footer_statistics'); ?></script>
    </body>
</html>