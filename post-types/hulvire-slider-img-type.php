<?php
function huu_slider_register_init() { 
	
	$labels = array(
	'name'                => _x( 'hulvire_slider', 'post type general name' ),
	'singular_name'       => _x( 'Slider', 'post type singular name' ),
	'add_new'             => _x( 'Pridaj nový', 'slider' ),
	'add_new_item'        => __( 'Pridaj New Slide' ),
	'menu_name'           => __( 'Hulvire Slider' ),
	'all_items'           => __( 'Všetky slajdy' ),
	'view_item'           => __( 'View Slide' ),
	'edit_item'           => __( 'Uprav slide' ),
	'not_found_in_trash'  => __('No Slide found in Trash'),
	'slide_date_OD' 	  => __('Dátum OD'),
	'slide_date_DO' 	  => __('Dátum DO'),
	'parent_item_colon'   => '',
	    'menu_name' => __('Hulvire Slider') 
	);
		
	
    $args = array( 
		'labels' => $labels,
			'public' => true,
		    'publicly_queryable' => true,
		    'show_ui' => true, 
		    'show_in_menu' => true, 
		    'query_var' => true,
		    'rewrite' => true,
		    'capability_type' => 'post',
		    'has_archive' => true, 
		    'hierarchical' => false, 
			'menu_position' => 5,
		'menu_icon' => plugin_dir_url( __FILE__ ). '../images/slider-icon3.png',
        'supports' => array('title','thumbnail') 
       ); 
   
    register_post_type('hulvire_slider' , $args ); 
} 
add_action('init', 'huu_slider_register_init');
add_theme_support('post-thumbnails', array('hulvire_slider'));


/* Add Custom Columns */
function huu_slider_edit_columns($columns)
{
	$columns = array(
		  "cb" => '<input type="checkbox" >',
		  "title" => __( 'Slide Title' ),		  
		  "thumb" => __( 'Thumbnail' ),		  		 
		  "slide_sub_title" => __('Slide Sub Title' ),
		  "slide_date_OD" => __( 'Datum OD','framework' ),
		  "slide_date_DO" => __( 'Datum DO','framework' ),
		  "date" => __( 'Date' )
	);
	
	return $columns;
}
add_filter("manage_hulvire_slider_posts_columns", "huu_slider_edit_columns");


function huu_slider_custom_columns($column){
	global $post;
	switch ($column)
	{
		case 'thumb':
			if(has_post_thumbnail($post->ID))
			{
				the_post_thumbnail('slider-img-thumb',array( 'style' => 'width:180px;height:auto' ) );                   
			}
			else
			{
				_e('No Slider Image');
			}
			break;		
		case 'slide_sub_title':
			$slide_sub_title = get_post_meta($post->ID,'slide_sub_title',true);
			if(!empty($slide_sub_title))
			{
				echo $slide_sub_title;
			}
			else
			{
				_e('NA');
			}		
			break;
		case 'slide_date_OD':
			$slide_date_OD = get_post_meta($post->ID,'slide_date_OD',true);
			if(!empty($slide_date_OD))
			{
				echo $slide_date_OD;
			}
			else
			{
				_e('NA');
			}		
			break;
		case 'slide_date_DO':
			$slide_date_DO = get_post_meta($post->ID,'slide_date_DO',true);
			if(!empty($slide_date_DO))
			{
				echo $slide_date_DO;
			}
			else
			{
				_e('NA');
			}		
			break;
	}
}
add_action("manage_hulvire_slider_posts_custom_column", "huu_slider_custom_columns");



/*-----------------------------------------------------------------------------------*/
/*	Add Metabox to Slide
/*-----------------------------------------------------------------------------------*/	


	function huu_slider_add_meta_boxes() {
	    add_meta_box('huu_meta_id', 'Slide Custom Post', 'slide_meta_box', 'hulvire_slider', 'normal');
	}
	add_action('add_meta_boxes', 'huu_slider_add_meta_boxes');
	
	function slide_meta_box( $post )
	{
		$values = get_post_custom( $post->ID );
		
		$slide_sub_title = isset( $values['slide_sub_title'] ) ? esc_attr( $values['slide_sub_title'][0] ) : '';
		$slide_url = isset( $values['slide_url'] ) ? esc_attr( $values['slide_url'][0] ) : '';
		$slide_date_OD = isset( $values['slide_date_OD'] ) ? esc_attr( $values['slide_date_OD'][0] ) : 'NA';
		$slide_date_DO = isset( $values['slide_date_DO'] ) ? esc_attr( $values['slide_date_DO'][0] ) : 'NA';
		
		wp_nonce_field( 'slide_meta_box_nonce', 'meta_box_nonce_slide' );
		?>
		<table style="width:100%;">			
        	<tr>
				<td style="width:25%;">
					<label for="slide_sub_title"><strong><?php _e('Sub Title','HuuS');?></strong></label>					
				</td>
				<td style="width:75%;">
					<input type="text" name="slide_sub_title" id="slide_sub_title" value="<?php echo $slide_sub_title; ?>" style="width:60%; margin-right:4%;" />
                    <span style="color:#999; display:block;"><?php _e('This text will appear below Title in Slider Navigation. Hoppefully :)','HuuS'); ?></span>
				</td>
			</tr>
			<tr>
				<td style="width:25%; vertical-align:top;">
					<label for="slide_url"><strong><?php _e('Target URL','HuuS');?></strong></label>					
				</td>
				<td style="width:75%; ">
                    <input type="text" name="slide_url" id="slide_url" value="<?php echo $slide_url; ?>" style="width:60%; margin-right:4%;" />
                    <span style="color:#999; display:block;  margin-bottom:10px;"><?php _e('This URL will be applied on Slide Image.','HuuS'); ?></span>
				</td>
			</tr>
			<tr>
				<td style="width:25%; vertical-align:top;">
					<label for="slide_date_OD"><strong><?php _e('Dátum OD','HuuS');?></strong></label>					
				</td>
				<td style="width:75%; ">
                    <input type="text" name="slide_date_OD" id="slide_date_OD" value="<?php echo $slide_date_OD; ?>" style="width:60%; margin-right:4%;" />
                    <span style="color:#999; display:block;  margin-bottom:10px;"><?php _e('Dátum od kedy sa bude zobrazovať slider.','framework'); ?></span>
				</td>
			</tr>
			<tr>
				<td style="width:25%; vertical-align:top;">
					<label for="slide_date_DO"><strong><?php _e('Dátum DO','HuuS');?></strong></label>					
				</td>
				<td style="width:75%; ">
                    <input type="text" name="slide_date_DO" id="slide_date_DO" value="<?php echo $slide_date_DO; ?>" style="width:60%; margin-right:4%;" />
                    <span style="color:#999; display:block;  margin-bottom:10px;"><?php _e('Dátum do kedy sa bude zobrazovať slider.','framework'); ?></span>
				</td>
			</tr>			
		</table>		        		
		<?php
	}
	
	
	add_action( 'save_post', 'slide_meta_box_save' );
	
	function slide_meta_box_save( $post_id )
	{
		
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		
		if( !isset( $_POST['meta_box_nonce_slide'] ) || !wp_verify_nonce( $_POST['meta_box_nonce_slide'], 'slide_meta_box_nonce' ) ) return;
		
		if( !current_user_can( 'edit_post' ) ) return;				
		
		if( isset( $_POST['slide_sub_title'] ) )
			update_post_meta( $post_id, 'slide_sub_title', $_POST['slide_sub_title']  );
		
		if( isset( $_POST['slide_url'] ) )
			update_post_meta( $post_id, 'slide_url', $_POST['slide_url'] );
		
		if( isset( $_POST['slide_date_OD'] ) )
			update_post_meta( $post_id, 'slide_date_OD', $_POST['slide_date_OD'] );
		if( isset( $_POST['slide_date_DO'] ) )
			update_post_meta( $post_id, 'slide_date_DO', $_POST['slide_date_DO'] );

	}