<div class="wrapper">
    <?php
         $settings = get_option('aliffiliate_shortcodes_settings');
         if (!isset($settings['api_key']) || $settings['api_key'] == "" || !isset($settings['tracking_id']) || $settings['tracking_id'] == "")
            echo "<div class='infoWarning'>You must set AliExpress API key and Tracking ID in order to use this plugin properly!!!</div>";

            //$timestamp = wp_next_scheduled( 'aliffiliate_auto');
            //echo date('m/d/Y h:m',$timestamp);
            //echo date('m/d/Y h:m',current_time( 'timestamp' ));
    ?>
<div class="header"><?php echo $page_name; ?><div class="helpBox"><a href="http://aliffiliate.com" target="_blank">Help </a><img src="<?php echo plugins_url('../images/faq-icon.png', __FILE__) ?>" /></div></div>
<a href="http://aliffiliate.com" target="_blank" style="margin: 10px auto 0px; float: none; text-align: center; display: block; width: 96%;"><img src="<?php echo plugins_url('../images/728.png', __FILE__) ?>" /></a>
    