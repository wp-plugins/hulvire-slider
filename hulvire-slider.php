<?php
/*
Plugin Name: Hulvire slider
Plugin URI: http://www.amfajnor.sk/_hulvire_web/hulvire%20old/index.htm
Description: slider manager so setupom (typ animacie – FADE alebo SLIDE) pouziva jQuery FlexSlider v2.2.0 "http://flexslider.woothemes.com/", shortcut v postoch a strankach: <strong>[huu_slider]</strong> alebo v temach <strong>huu_slider();</strong>
Version: 1.4
Author: Fajnor
Author URI: http://amfajnor.sk
License: GPL2
Text Domain: mee
*/
// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly. Meee';
	exit;
}

if(!class_exists('WP_Hulvire_Slider'))
{
    class WP_Hulvire_Slider
    {
        /**
         * Construct the plugin object
         */
        public function __construct()
        {
			// Initialize Settings
			require_once(sprintf("%s/hulvire-slider-settings.php", dirname(__FILE__)));
			$WP_Hulvire_Slider_Settings = new WP_Hulvire_Slider_Settings();
			
			// Register custom post types
			//require_once(sprintf("%s/post-types/hulvire-slider-img_type.php", dirname(__FILE__)));
			//$Hulvire_Slider_Img_Type = new Hulvire_Slider_Img_Type();

			$plugin = plugin_basename(__FILE__);
			add_filter("plugin_action_links_$plugin", array( $this, 'plugin_settings_link' ));
			
			
			
			define( 'HUU_SLIDER_VERSION', '1.4' );
			define( 'HUU__SLIDER_URL', plugin_dir_url( __FILE__ ) );
			define( 'HUU__SLIDER_DIR', plugin_dir_path( __FILE__ ) );
			
			
			add_action( 'admin_enqueue_scripts', 'hulvire_slider_add_color_picker' );
			function hulvire_slider_add_color_picker( $hook ) {
 
			    if( is_admin() ) {
     
			        // Add the color picker css file      
			        wp_enqueue_style( 'wp-color-picker' );
         
			        // Include our custom jQuery file with WordPress Color Picker dependency
			        wp_enqueue_script( 'custom-script-handle', plugins_url( 'js/hulvire-slider-admin-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
			    }
			}
			

			function huu_slider_scripts_method() {
				wp_enqueue_script('flexslider', HUU__SLIDER_URL .'flexslider/jquery.flexslider-min.js', array('jquery'));
				wp_enqueue_style('flexslider_css', HUU__SLIDER_URL .'flexslider/flexslider.css');
				wp_enqueue_style('hulvire-slider-style_css', HUU__SLIDER_URL .'css/hulvire-slider-style.css');
				wp_enqueue_style('hulvire-slider-script', HUU__SLIDER_URL .'js/hulvire-slider-script.js', array('jquery'));
				
			}
			add_action('wp_enqueue_scripts', 'huu_slider_scripts_method');

			require_once('post-types/hulvire-slider-img-type.php');
			
			
			function huu_slider_script(){
				$settingAnimacia = get_option('setting_animacia') ?: "fade";
				$settingPopUp = get_option('setting_checkbox_popUpSlider') ?: "";
				$settingPopUpDelay = get_option('setting_popUpSlider_delay') ?: "1000";
				
				if($settingPopUp!=""){
					echo "<script type='text/javascript' charset='utf-8'>
					(function($) {
					    $(window).load(function() {
							$(\"body\").prepend('<div class=\"popup_wraper\"></div>');
							/*$.ajax({
							  method: \"POST\",
							  url: \"". HUU__SLIDER_URL ."PopUp.php\",
							  data: { postovaneudaje:'". huu_get_popUp_slider() ."',popupdelay:'". $settingPopUpDelay ."'}
							})
							  .done(function( msg ) {
							    $(\".popup_wraper\").html(msg);
		  				        $('.flexsliderHulvire').flexslider({
		  					            animation: '$settingAnimacia',
		  						    controlsContainer: '.flex-container'
		  					    });
							  });*/

					    });
					})(jQuery)
					jQuery(document).ready(function($){
						$('.popup_content').delay(".$settingPopUpDelay.").slideDown(500);
						// PopUp CLOSE'==========================================
						$('.popup_close').mouseover(function(){
								$(this).css({'color':'#fff'});
							}).mouseout(function(){
								$(this).css({'color':'#E1312D'});
							}).click(function(){
								$('.popup_content').delay(100).slideUp(600);
							});
				
					});
					</script>";
				}else{
				
				echo "<script type='text/javascript' charset='utf-8'>
				(function($) {
				    $(window).load(function() {
				        $('.flexsliderHulvire').flexslider({
					            animation: '$settingAnimacia',
						    controlsContainer: '.flex-container'
					    });
				    });
				})(jQuery)
				</script>";
			}
 
			}
			add_action('wp_head', 'huu_slider_script');

			function huu_get_popUp_slider(){
				$settingPopUp = get_option('setting_checkbox_popUpSlider');
				
				$slider = '<div class="popup_content">';
				$slider.= '<div class="popup_close">&#10006;</div>';
				$slider.= '<div class="flexsliderHulvire">';
				$slider.= '<ul class="slides">';
 
				    $huu_query = "post_type=hulvire_slider";
				    query_posts($huu_query);
     
     
				    if (have_posts()) : while (have_posts()) : the_post();
        						
						if ( has_post_thumbnail() ) 
						{	
					        $img = get_the_post_thumbnail( $post->ID, 'large' );
							$values = get_post_custom( $post->ID );
		
							$slide_sub_title = isset( $values['slide_sub_title'] ) ? esc_attr( $values['slide_sub_title'][0] ) : '';
							$slide_url = isset( $values['slide_url'] ) ? esc_attr( $values['slide_url'][0] ) : '';
							$slide_date_OD = isset( $values['slide_date_OD'] ) ? esc_attr( $values['slide_date_OD'][0] ) : '';
							$slide_date_DO = isset( $values['slide_date_DO'] ) ? esc_attr( $values['slide_date_DO'][0] ) : '';
						
							$today = date("d.m.Y"); 
						    $start_ts = strtotime(preg_replace('/\s+/', '', $slide_date_OD));
						    $end_ts = strtotime(preg_replace('/\s+/', '', $slide_date_DO));
						    $user_ts = strtotime($today);
							
							
							if ((($user_ts >= $start_ts) && ($user_ts <= $end_ts)) || (($start_ts == "") or ($end_ts == "")))	
							{	 
							
								$slider.='<li>';
								
								//if ($slide_sub_title!="")
								//$slider.='<p>'.$slide_sub_title.'</p>';
								
								if ($slide_url!="")
								$slider.='<a href="'.$slide_url.'" title="'.get_the_title().'"  class="img-hover" >';
								
								$slider.=$img;
								
								if ($slide_url!="")
								$slider.='</a>';
									
								$slider.='</li>';
							
							}
							
						}
						
             
				    endwhile; endif; 
		
					wp_reset_query();
 
				    $slider.= '</ul>';
					$slider.= '</div><!-- /end .flexsliderHulvire-->';
					$slider.= '</div><!-- /end .popup_content-->';
					
     
				    return $slider; 
			}
			
			
			function huu_get_slider(){

				$slider = '<div class="flexsliderHulvire">';
				$slider.= '<ul class="slides">';
 
				    $huu_query = "post_type=hulvire_slider";
				    query_posts($huu_query);
     
     
				    if (have_posts()) : while (have_posts()) : the_post();
        						
						if ( has_post_thumbnail() ) 
						{	
					        $img = get_the_post_thumbnail( $post->ID, 'large' );
							$values = get_post_custom( $post->ID );
		
							$slide_sub_title = isset( $values['slide_sub_title'] ) ? esc_attr( $values['slide_sub_title'][0] ) : '';
							$slide_url = isset( $values['slide_url'] ) ? esc_attr( $values['slide_url'][0] ) : '';
							$slide_date_OD = isset( $values['slide_date_OD'] ) ? esc_attr( $values['slide_date_OD'][0] ) : '';
							$slide_date_DO = isset( $values['slide_date_DO'] ) ? esc_attr( $values['slide_date_DO'][0] ) : '';
						
							$today = date("d.m.Y"); 
						    $start_ts = strtotime(preg_replace('/\s+/', '', $slide_date_OD));
						    $end_ts = strtotime(preg_replace('/\s+/', '', $slide_date_DO));
						    $user_ts = strtotime($today);
							
							
							if ((($user_ts >= $start_ts) && ($user_ts <= $end_ts)) || (($start_ts == "") or ($end_ts == "")))	
							{	 
							
								$slider.='<li>';
								
								//if ($slide_sub_title!="")
								//$slider.='<p>'.$slide_sub_title.'</p>';
								
								if ($slide_url!="")
								$slider.='<a href="'.$slide_url.'" title="'.get_the_title().'"  class="img-hover" >';
								
								$slider.=$img;
								
								if ($slide_url!="")
								$slider.='</a>';
									
								$slider.='</li>';
							
							}
							
						}
						
             
				    endwhile; endif; 
		
					wp_reset_query();
 
				    $slider.= '</ul>';
					$slider.= '</div><!-- /end .flexsliderHulvire-->';					
     
				    return $slider; 
			}
			
			
 
			/**add the shortcode for the slider- for use in editor**/
			function huu_insert_slider($atts, $content = null){
 
				$slider = huu_get_slider();
 
				return $slider;
			}
			add_shortcode('huu_slider', 'huu_insert_slider');
 
			/**add template tag- for use in themes**/
			function huu_slider(){
	
			    echo huu_get_slider();
			}

			
        } // END public function __construct

        /**
         * Activate the plugin
         */
        public static function activate()
        {
            // Do nothing
        } // END public static function activate

        /**
         * Deactivate the plugin
         */     
        public static function deactivate()
        {
            // Do nothing
        } // END public static function deactivate
		
		// Add the settings link to the plugins page
		function plugin_settings_link($links)
		{
			$settings_link = '<a href="options-general.php?page=wp_hulvire_slider">Settings</a>';
			array_unshift($links, $settings_link);
			return $links;
		}
		
		
    } // END class WP_Hulvire_Slider
} // END if(!class_exists('WP_Hulvire_Slider'))

if(class_exists('WP_Hulvire_Slider'))
{
    // Installation and uninstallation hooks
    register_activation_hook(__FILE__, array('WP_Hulvire_Slider', 'activate'));
    register_deactivation_hook(__FILE__, array('WP_Hulvire_Slider', 'deactivate'));

    // instantiate the plugin class
    $wp_hulvire_slider = new WP_Hulvire_Slider();
}
