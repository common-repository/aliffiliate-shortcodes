<?php
        if (!defined('ABSPATH')) exit;
    
        class Aliffiliate_Shortcode {
            private static $jquery_cdn_url;
            private static $jquery_local_url;
            private static $aliBaseUrl;
            private static $aliProductsListBaseUrl;
            private static $aliDetailsBaseUrl;
            private static $aliProductParseUrl;
            private static $initialized = false;
            private static $errorCodes = array();
    
            public static function init() {
                
                if(!self::$initialized) {
                    
                    self::$initialized = true;
                    self::$aliBaseUrl = "http://gw.api.alibaba.com/openapi/param2/1/portals.open/";
                    self::$aliProductsListBaseUrl = self::$aliBaseUrl . "api.listPromotionProduct/";                            
                    self::$errorCodes = array(
                        '20030000' => 'Required parameters',
                        '20030010' => 'Keyword input parameter error',
                        '20030020' => 'Category ID input parameter error or formatting errors',
                        '20,030,030' => 'commission rate input parameter error or formatting errors',
                        '20030040' => 'Unit input parameter error or formatting errors',
                        '20030050' => '30 days promotion amount input parameter error or formatting errors',
                        '20,030,060' => 'tracking ID input parameter error or limited length',
                        '20030070' => 'unauthorized transfer request',
                        '20020000' => 'System Error',
                        '20010000' => 'call succeeds',
                        '20130000' => 'input parameter Product ID is error',
                        '20130010' => 'Tracking ID is error or invalid',
                        '20130020' => 'Unauthorized Request',
                        '20120000' => 'System error',
                        '20110000' => 'Success' 
                    );
    
                    if (is_admin())
                    {
                        self::init_hooks();
                    }
                }
            } 
            public static function init_hooks(){
                add_action('admin_enqueue_scripts', array('Aliffiliate_Shortcode', 'load_resources_short') );
                add_action('admin_menu', array('Aliffiliate_Shortcode', 'load_menu'));              
            }    
            public static function load_resources_short($hook) {           
                if (strpos($hook, 'aliffiliate-shortcodes') === false) {                    
                    return;
                }    
                // css
                wp_enqueue_style('aliffiliate_shortcodes_wp_admin_css', plugins_url('css/admin.css', __FILE__));
                wp_enqueue_style('aliffiliate_shortcodes_wp_admin_jquery_ui_css', plugins_url('css/jquery-ui.min.css', __FILE__));
    
                // js
                wp_enqueue_script('jquery');
                wp_enqueue_script('jquery-ui');
                wp_enqueue_script('aliffiliate_wp_admin_js_validation_short', plugins_url('js/jquery.validate.min.js', __FILE__));
            }        
                
             public static function getTransientQuery( $args) {      
                 $result = '_alishort_' . $args['category'].'_'.$args['keyword'];     
                 return $result;            
             }

              public static function getproducts( $args) {      
                  $jsonurl = self::construct_ali_express_url("list");
                 
                  if (intval($args['category'])>0)
                  {
                        $jsonurl = $jsonurl . "categoryId=". $args['category']. "&"; 
                  }
                  if (trim($args['keyword'])!="")
                  {
                        $jsonurl = $jsonurl . "keywords=". $args['keyword'] . "&"; 
                  }

                  $jsonurl = $jsonurl . 'pageNo=1&pageSize=20';  
           
                  $json = wp_remote_get($jsonurl);       
                  if (!isset($json["body"]))     return $json;
                  $obj = json_decode($json["body"]);    
         
                if (!isset($obj->result)) 
                {
                    return array();
                }
        
                $posts = $obj->result->items; 

                return $posts;
                 
             }
            public static function add_ali_shortcode( $atts) {                    
                              
                  $cat = isset($atts['category']) ? esc_attr($atts['category']):0;
                  $cnt = isset($atts['count']) ? intval(esc_attr($atts['count'])):1;
                  $keyword = isset($atts['keyword']) ? esc_attr($atts['keyword']):"";

                  if ($cnt>10) $cnt = 10;
                    $arguments = array(
                        'category'=> $cat,                        
                        'keyword'=>$keyword
                    );

                    $resultquery = self::getTransientQuery($arguments);              
                    $transientduration = 5 * HOUR_IN_SECONDS;
                    $products = get_transient($resultquery);
                  
                    if ( $products == false)
                    {
                          $products = self::getproducts($arguments);
                          set_transient( $resultquery, $products, $transientduration );
                    }

                  ob_start();     
      
                    foreach($products as $key => $product) 
                    {
                        if ($cnt==0) break;                       
                        $url = $product->detailUrl;            
                        echo '<a class="aliffiliate_banner" href="'.esc_url($url).'" title="'.esc_attr($product->subject).'">';
                        echo '<img class="ali_banner_image" src="'.$product->imageUrl.'"/>';
                        echo '</a>';                              
                        $cnt--;    
                    }
                                        
                  $output = ob_get_contents();
                  ob_end_clean();
                  return $output;
            }
            public static function load_menu() {
                add_menu_page('Aliffiliate Shortcodes', 'Aliffiliate Shortcodes', 'manage_options', 'aliffiliate-shortcodes-settings', array('Aliffiliate_Shortcode', 'display_shortcodes_settings_page'));
                add_submenu_page('aliffiliate-shortcodes-settings', 'Aliffiliate Shortcodes - Settings', 'Settings', 'manage_options', 'aliffiliate-shortcodes-settings', array('Aliffiliate_Shortcode', 'display_shortcodes_settings_page'));
                add_submenu_page('aliffiliate-shortcodes-settings', 'Aliffiliate Shortcodes - Create', 'Create shortodes', 'manage_options', 'aliffiliate-shortcodes-create', array('Aliffiliate_Shortcode', 'display_shortcodes_create_page'));
                add_submenu_page('aliffiliate-shortcodes-settings', 'Aliffiliate Premium', 'Premium Plugin', 'manage_options', 'aliffiliate-shortcodes-premium', array('Aliffiliate_Shortcode', 'display_shortcodes_premium_page'));
             }
            public static function view($name, array $args = array()) {
                foreach ( $args AS $key => $val ) {
                    $$key = $val;
                }
                $file = ALIFFILIATE_SHORTCODES_PLUGIN_DIR . 'views/' . $name . '.php';
                include( $file );
            }
            public static function display_shortcodes_premium_page() {
               
                Aliffiliate_Shortcode::view('premium');
            }       
            public static function display_shortcodes_create_page() {
                $short = '';
                 if(isset($_POST['categoryId'])) {
                     $keyword = (isset($_POST['keywords'])?$_POST['keywords']:"");
                     $cat = (isset($_POST['categoryId'])?$_POST['categoryId']:"");
                     $num = (isset($_POST['numberToImport'])?$_POST['numberToImport']:"");
                     if (intval($num)>0)
                     { 
                        $short = '[aliffiliate_short keyword="'.$keyword.'" category="'.$cat.'" count="'.$num.'"]';
                     }                
                 }
                $args = array(
                     'short'=> $short
                     );
                Aliffiliate_Shortcode::view('create', $args);
            }           
            public static function display_shortcodes_settings_page() {
                if(isset($_POST['save'])) {
                    $settings = array(
                        'api_key' =>isset($_POST['afApiKey'])?$_POST['afApiKey']:"",
                        'digital_signature' => isset($_POST['afDigitalSignature'])?$_POST['afDigitalSignature']:"",
                        'tracking_id' => isset($_POST['afTrackingId'])?$_POST['afTrackingId']:""             
                    );
    
                    update_option('aliffiliate_shortcodes_settings', $settings);
                }
    
                $settings = get_option('aliffiliate_shortcodes_settings');
                $args = array(
                    'afApiKey' => isset($settings['api_key'])?$settings['api_key']:"",
                    'afDigitalSignature' => isset($settings['digital_signature'])?$settings['digital_signature']:"",
                    'afTrackingId' => isset($settings['tracking_id'])?$settings['tracking_id']:""           
                );
    
                Aliffiliate_Shortcode::view('settings', $args);
            }                  
            public static function construct_ali_express_url($type) {
                $settings = get_option('aliffiliate_shortcodes_settings');                
                return self::$aliProductsListBaseUrl . $settings['api_key'] . '?' . 'trackingId=' . $settings['tracking_id'] . '&';
            }              
    }
?>
