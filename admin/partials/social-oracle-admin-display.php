<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Social_Oracle
 * @subpackage Social_Oracle/admin/partials
 */

if (!current_user_can('manage_options')) {
    return;
}

$API_KEY = get_option('socialoracle_api_key');
$PUBLIC_KEY = get_option('socialoracle_public_api_key');

?>


<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap" id="socialoracle-settings">
    <!-- <h1><?=esc_html(get_admin_page_title()); ?></h1> -->
    <a class="logo-container" href="https://socialoracle.app">
        <img class="top-logo" src="<?php echo $logo_path; ?>">
        <span>Social Oracle</span>
    </a>
    <form action="options.php" method="post">
        <?php
            settings_fields('socialoracle_options');
            do_settings_sections('socialoracle_options'); 
        ?>

        <div class="socialoracle-settings-container">
            <?php if ($API_KEY != null) { ?>
                <div class="notice notice-success is-dismissible"><p>Social Oracle is configured.</p></div>
                
            <?php } else { ?>
                <div class="notice notice-warning is-dismissible"><p>Add your API Key below</p></div>
            <?php } ?>
            
            <div class="label">Your SECRET API Key:</div>
            <input type="text" placeholder="required" name="<?php echo 'socialoracle_api_key'; ?>" value="<?php echo esc_attr($API_KEY); ?>" />
            <div>NB! - Do not share this API key. This is for direct communication between Social Oracle and this website and not the public tracking code.</div>
            <div class="m-t"><a href="https://socialoracle.app/account" target="_blank">Where is my API Key?</a></div>
        </div>

        <?php submit_button('Save'); ?>
    
    </form>
</div>