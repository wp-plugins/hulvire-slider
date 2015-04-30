<?php
if(!class_exists('WP_Hulvire_Slider_Settings'))
{
	class WP_Hulvire_Slider_Settings
	{
		/**
		 * Construct the plugin object
		 */
		public function __construct()
		{
			// register actions
            add_action('admin_init', array(&$this, 'admin_init'));
        	add_action('admin_menu', array(&$this, 'add_menu'));
		} // END public function __construct
		
        /**
         * hook into WP's admin_init action hook
         */
        public function admin_init()
        {
        	// register your plugin's settings
        	register_setting('wp_hulvire_slider-group', 'setting_checkbox_popUpSlider');
        	register_setting('wp_hulvire_slider-group', 'setting_popUpSlider_delay');
        	register_setting('wp_hulvire_slider-group', 'setting_animacia');
        	register_setting('wp_hulvire_slider-group', 'setting_current_color');

        	// add your settings section
        	add_settings_section(
        	    'wp_hulvire_slider-section', 
        	    'Hulvire Slider Settings', 
        	    array(&$this, 'settings_section_hulvire_slider'), 
        	    'wp_hulvire_slider'
        	);
        	
        	// add your setting's fields
            add_settings_field(
                'wp_hulvire_slider-setting_checkbox_popUpSlider', 
                'Zobraz Hulvire Slider ako popUp okno', 
                array(&$this, 'settings_field_input_checkbox'),'wp_hulvire_slider','wp_hulvire_slider-section',
                array(
                    'field' => 'setting_checkbox_popUpSlider'
                )
            );
            add_settings_field(
                'wp_hulvire_slider-setting_popUpSlider_delay', 
                'Dellay for show popUp window', 
                array(&$this, 'settings_field_input_text'),'wp_hulvire_slider','wp_hulvire_slider-section',
                array(
                    'field' => 'setting_popUpSlider_delay'
                )
            );
			add_settings_field(
			    'wp_hulvire_slider-setting_animacia',
			    'nastav animaciu',
			    array(&$this, 'settings_field_input_radio'),'wp_hulvire_slider','wp_hulvire_slider-section',
                array(
                    'field' => 'setting_animacia'
                )
			);
			add_settings_field(
                'wp_hulvire_slider-setting_current_color', 
                'current color', 
                array(&$this, 'settings_field_input_color_picker'),'wp_hulvire_slider','wp_hulvire_slider-section',
                array(
                    'field' => 'setting_current_color'
                )
            );
            // Possibly do additional admin_init tasks
        } // END public static function activate
        
        public function settings_section_hulvire_slider()
        {
            // Think of this as help text for the section.
            echo 'Settings for Hulvire Slider. ٩(●̮̮̃•)۶	';
        }
        
        /**
         * This function provides text inputs for settings fields
         */
        public function settings_field_input_text($args)
        {
            // Get the field name from the $args array
            $field = $args['field'];
            // Get the value of this setting
            $value = get_option($field);
            // echo a proper input type="text"
            echo sprintf('<input type="text" name="%s" id="%s" value="%s" />', $field, $field, $value);
        } // END public function settings_field_input_text($args)
        
		public function settings_field_input_radio($args) {
 
            $field = $args['field'];
			
            $value = get_option($field);
			$value = isset( $value ) ? esc_attr( $value ) : 'fade';

		    echo sprintf('<input type="radio" name="%s" id="%s" value="fade"' . checked( "fade", $value, false ) . ' />', $field, $field);
		    echo sprintf('<label for="radio">FADE</label><br />');
		    echo sprintf('<input type="radio" name="%s" id="%s" value="slide"' . checked( "slide", $value, false ) . ' />', $field, $field);
		    echo sprintf('<label for="radio">SLIDE</label>');

		} // end settings_field_input_radio
		
		public function settings_field_input_checkbox($args) {
 
            $field = $args['field'];
			
            $value = get_option($field);
			$value = isset( $value ) ? esc_attr( $value ) : 'ano';

		    echo sprintf('<input type="checkbox" name="%s" id="%s" value="ano"' . checked( "ano", $value, false ) . ' />', $field, $field);


		} // end settings_field_input_checkbox
		
        public function settings_field_input_color_picker($args)
        {
            // Get the field name from the $args array
            $field = $args['field'];
            // Get the value of this setting
            $value = get_option($field);
            // echo a proper input type="text"
            echo sprintf('<input type="text" name="%s" id="%s" value="%s" class="cpa-color-picker" />', $field, $field, $value);
        } // END public settings_field_input_color_picker($args)
		
		public function settings_field_checkbox_element_callback($args) {
 
            $field = $args['field'];

            $value = get_option($field);

		    $html = '<select id="time_options" name="sandbox_theme_input_examples[time_options]">';
		        $html .= '<option value="default">Select a time option...</option>';
		        $html .= '<option value="never"' . selected( $options['time_options'], 'never', false) . '>Never</option>';
		        $html .= '<option value="sometimes"' . selected( $options['time_options'], 'sometimes', false) . '>Sometimes</option>';
		        $html .= '<option value="always"' . selected( $options['time_options'], 'always', false) . '>Always</option>';
		    $html .= '</select>';
		    echo $html;
 
		} // end sandbox_checkbox_element_callback
		
		
        /**
         * add a menu
         */		
        public function add_menu()
        {
            // Add a page to manage this plugin's settings
        	add_options_page(
        	    'WP Hulvire Slider Settings', 
        	    'Hulvire Slider', 
        	    'manage_options', 
        	    'wp_hulvire_slider', 
        	    array(&$this, 'plugin_settings_page')
        	);
        } // END public function add_menu()
    
        /**
         * Menu Callback
         */		
        public function plugin_settings_page()
        {
        	if(!current_user_can('manage_options'))
        	{
        		wp_die(__('You do not have sufficient permissions to access this page.'));
        	}
	
        	// Render the settings template
        	include(sprintf("%s/templates/settings.php", dirname(__FILE__)));
        } // END public function plugin_settings_page()
    } // END class WP_Hulvire_Slider_Settings
} // END if(!class_exists('WP_Hulvire_Slider_Settings'))
