<?php defined('ABSPATH') or die("No script kiddies please!"); ?>
<p><?php _e('There are 2 main settings tabs that will help you to setup the plugin to work properly.', 'accesspress-social-login-lite'); ?></p>
<dl><?php _e("Please note that for google login to work the user must have google+ account and may cause error during google login if they don't have google+ account.", 'accesspress-social-login-lite'); ?></dl>
<dl>
    <dt><strong><?php _e('Network Settings:', 'accesspress-social-login-lite'); ?></strong></dt>
    <dd><?php _e('In this tab you can enable and disable the available social medias as per your need. Also you can order the apperance of the social medias simply by drag and drop.', 'accesspress-social-login-lite'); ?>
    </dd>
    <p><?php _e('For each social media you can', 'accesspress-social-login-lite'); ?></p>
    <ul class='how-list'>
        <li><?php _e('Enable/Disable: You can enable and disable the social media.', 'accesspress-social-login-lite'); ?></li>
        <li><?php _e('App ID: App id of the social media.', 'accesspress-social-login-lite'); ?></li>
        <li><?php _e('App secret: App secret of the social media.', 'accesspress-social-login-lite'); ?></li>
    </ul>
    <?php _e('To get the App ID and App Secret please follow the instructions(notes section) for each social profile.', 'accesspress-social-login-lite'); ?>

    <dt><strong><?php _e('Other settings:', 'accesspress-social-login-lite'); ?></strong></dt>
    <dd>
        <p><?php _e('In this tab you can do various settings of a plugin.', 'accesspress-social-login-lite'); ?></p>
        <ul class="how-list">
            <li><?php _e('Enable or disable the social login.', 'accesspress-social-login-lite'); ?></li>
            <li><?php _e('Options to enable the social logins for login form, registration form and in comments.', 'accesspress-social-login-lite'); ?></li>
            <li><?php _e('Options to choose the pre available themes, You can choose any one theme from the pre available 4 themes.', 'accesspress-social-login-lite'); ?></li>
            <li><?php _e('Login text: Here you can setup the login text as per your needs.', 'accesspress-social-login-lite'); ?></li>
        </ul>
    </dd>

    <dt><strong><?php _e('Shortcode:', 'accesspress-social-login-lite'); ?></strong></dt>
    <dd><p><?php _e('You can use shortcode for the display of the social logins in the contents of a posts and pages.', 'accesspress-social-login-lite'); ?>
        <ul class="how-list">
            <li>Example 1: [apsl-login-lite login_text='Social Connection']</li>
            <li><?php _e('Shortcode attributes: <br />
                i. login_text: You can use the custom login text for the shortcode using this attribute.<br />', 'accesspress-social-login-lite'); ?>
            </li>
        </ul>
    </p></dd>

    <dt><strong><?php _e('Widget:', 'accesspress-social-login-lite'); ?></strong></dt>
    <dd>
        <p><?php _e('You can use widget for the display of the social logins in the widgets area. ', 'accesspress-social-login-lite'); ?><br/>
            <ul class="how-list">
                <li><?php _e('Widget attributes ', 'accesspress-social-login-lite'); ?><br />
                    <?php _e('i. Title: You can setup the widget title here.', 'accesspress-social-login-lite'); ?><br />
                    <?php _e('ii. Login text: You can setup the login text here.', 'accesspress-social-login-lite'); ?><br />
                </li>
            </ul>
        </dd>
        <dd>
           <p><?php _e('For the complete documentation please visit:', 'accesspress-social-login-lite'); ?><br /> <a href='//accesspressthemes.com/documentation/documentationplugin-instruction-accesspress-social-login-lite/' target="_blank">Here</a></p>
       </dd>

   </dl>



