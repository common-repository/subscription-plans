<?php
/**
* Plugin Name: Subscription Plans
* Plugin URI: https://chargely.com/plugins/Wordpress
* Description: Start your Subscription Business in minutes with Chargely. Chargely provides PCI Certified Payment page for your card processing. So that you don't need a PCI Certification.
* Version: 1.0
* Author: Chargely
* Author URI: https://chargely.com/
* License: GPLv2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/

if (!defined("ABSPATH"))
    exit;
if (!defined("CHARGELY_SUBSCRIPTION_PLUGIN_DIR_PATH"))
    define("CHARGELY_SUBSCRIPTION_PLUGIN_DIR_PATH", plugin_dir_path(__FILE__));
if (!defined("CHARGELY_SUBSCRIPTION_PLUGIN_URL"))
    define("CHARGELY_SUBSCRIPTION_PLUGIN_URL", plugins_url() . "/subscription-plans");

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'chargely_plan_subscription_setting_action_links' );
function chargely_plan_subscription_setting_action_links( $links ) {
	$plugin_links = array(
		'<a href="' . admin_url( 'admin.php?page=chargely_wordpress_login' ) . '">' . __( 'Settings', 'chargely-subscription-plugin' ) . '</a>',
	);

	// Merge our new link with the default ones
	return array_merge( $plugin_links, $links );	
}

/*
 * Set permlinks on theme activate
 */
function set_chargelyplan_custom_permalinks() {
    $current_setting = get_option('permalink_structure');

    // Abort if already saved to something else
    if($current_setting) {
        return;
    }

    // Save permalinks to a custom setting, force create of rules file
    global $wp_rewrite;
    update_option("rewrite_rules", FALSE);
    $wp_rewrite->set_permalink_structure('/news/%postname%/');
    $wp_rewrite->flush_rules(true);
}
add_action('after_switch_theme', 'set_chargelyplan_custom_permalinks');

function chargely_plan_subscription_plugin_menus(){

	$chargely_plan_subscription_svg = file_get_contents(plugins_url('images/subscription_logo.svg', __FILE__));
	
	$icon_data_in_base64 = base64_encode($chargely_plan_subscription_svg);

    add_menu_page('Chargely Subscriptions', 'Chargely Subscriptions', 'manage_options', 'chargely_wordpress_login', 'chargely_plan_subscription_login', 'data:image/svg+xml;base64,'.$icon_data_in_base64, 10);
    add_submenu_page( 'chargely_wordpress_login', 'Subscribers', 'Subscribers', 'manage_options', 'chargely_wordpress_subscription', 'chargely_plan_subscription_subscription');
    add_submenu_page( 'chargely_wordpress_login', 'Transactions', 'Transactions', 'manage_options', 'chargely_wordpress_transactions', 'chargely_plan_subscription_transactions');
}
add_action('admin_menu', 'chargely_plan_subscription_plugin_menus');       
       
function chargely_plan_subscription_login() {
    include_once CHARGELY_SUBSCRIPTION_PLUGIN_DIR_PATH . "/views/admin/login.php";
}
    
function chargely_plan_subscription_subscription(){
    include_once CHARGELY_SUBSCRIPTION_PLUGIN_DIR_PATH."/views/admin/subscription.phtml";
}

function chargely_plan_subscription_transactions(){
    include_once CHARGELY_SUBSCRIPTION_PLUGIN_DIR_PATH."/views/admin/transtion.phtml";
}

function chargelyplan_plugin_assets(){
	// styles
    wp_enqueue_style("style", CHARGELY_SUBSCRIPTION_PLUGIN_DIR_PATH . "/css/mystyle.css", '', true);

	//scripts
    wp_enqueue_script('jquery');
    flush_rewrite_rules();
}
add_action("init", "chargelyplan_plugin_assets");

/**
 * Register the price table post type
 */
if ( !function_exists( 'siteorigin_chargely_plugin_register' ) ) :
	function siteorigin_chargely_plugin_register(){
		register_post_type('chargely-plugin',array(
			'labels' => array(
				'name' => __('Chargely Subscription Management', 'chargely-plugin'),
				'name' => __('Chargely Subscription Managements', 'chargely-plugin'),
				// view card
				'add_new' => __('Add New', 'chargely-plugin'),
				// add page
				'add_new_item' => __('Add New Price Tables', 'chargely-plugin'),
				// edit page
				'edit_item' => __('Edit Subcription Plan', 'chargely-plugin'),
				
				'new_item' => __('New Price Table', 'chargely-plugin'),
				
				// view page
				'all_items' => __('All Price Tables', 'chargely-plugin'),
				
				'view_item' => __('View Price Table', 'chargely-plugin'),
				
				// view
				'search_items' => __('Search Prices Tables', 'chargely-plugin'),
				// view
				'not_found' =>  __('No Price Tables found', 'chargely-plugin'),
			),
			'public'             	=> false,
			'publicly_queryable' 	=> true,
			'show_ui'            	=> true,
			'show_in_menu'       	=> false,
			'query_var'          	=> true,
			'has_archive' 			=> false,			
			'supports' 				=> array( 'title', '', 'revisions', '', '' ),
			'menu_position'      	=> 5,
			'menu_icon' 			=> plugins_url('images/subscription_logo.svg', __FILE__),
		));
		flush_rewrite_rules();
	}
		add_action( 'init', 'siteorigin_chargely_plugin_register');
endif;

/**
 * Add custom columns to chargely-plugin post list in the admin
 * @param $cols
 * @return array
 */

if ( !function_exists( 'chargely_plugin_register_custom_columns' ) ) :
	function chargely_plugin_register_custom_columns($cols){
		unset($cols['title']);
		unset($cols['date']);
		
		$cols['title'] = __('Titles', 'chargely-plugin');
		$cols['options'] = __('Options', 'chargely-plugin');
		$cols['features'] = __('Features', 'chargely-plugin');
		$cols['featured'] = __('Featured Option', 'chargely-plugin');
		$cols['date'] = __('Date', 'chargely-plugin');
		return $cols;
	}
	add_filter( 'manage_chargely_plugin_posts_columns', 'chargely_plugin_register_custom_columns');
endif; 

/**
 * Render the contents of the admin columns
 * @param $column_name
 */
 
function siteorigin_chargely_plugin_custom_column($column_name){
	global $post;
	switch($column_name){
	case 'options' :
		$table = get_post_meta($post->ID, 'chargely-plan-table', true);
		print count($table);
		break;
	case 'features' :
	case 'featured' :
		$table = get_post_meta($post->ID, 'chargely-plan-table', true);
		foreach($table as $col){
		if(!empty($col['featured']) && $col['featured'] == 'true'){
			if($column_name == 'featured') print $col['title'];
			else print count($col['features']);
			break;
		}
		}
		break;
	}
}
add_action( 'manage_chargely_plugin_posts_custom_column', 'siteorigin_chargely_plugin_custom_column');

/**
 * @return string The URL of the CSS file to use
 */

 function chargely_plugin_css_url() {
	// Find the best price table file to use
	if(file_exists(get_stylesheet_directory().'/chargely-subscription/css/chargely-plan-wp.css')) return get_stylesheet_directory_uri().'/chargely-subscription/css/chargely-plan-wp.css';
	elseif(file_exists(get_template_directory().'/chargely-subscription/css/chargely-plan-wp.css')) return get_template_directory_uri().'/chargely-subscription/css/chargely-plan-wp.css';
	else return plugins_url( 'css/chargely-plan-wp.css', __FILE__);

}

function chargely_plugin_boot_url(){
	// Find the best price table file to use
	if(file_exists(get_stylesheet_directory().'/chargely-subscription/css/bootstrap.min.css')) return get_stylesheet_directory_uri().'/chargely-subscription/css/bootstrap.min.css';
	elseif(file_exists(get_template_directory().'/chargely-subscription/css/bootstrap.min.css')) return get_template_directory_uri().'/chargely-subscription/css/bootstrap.min.css';
	else return plugins_url( 'css/bootstrap.min.css', __FILE__);

}

function chargely_plugin_js_url(){
	// Find the best price table file to use
	if(file_exists(get_stylesheet_directory().'/chargely-subscription/js/bootstrap.min.js')) return get_stylesheet_directory_uri().'/chargely-subscription/js/bootstrap.min.js';
	elseif(file_exists(get_template_directory().'/chargely-subscription/js/bootstrap.min.js')) return get_template_directory_uri().'/chargely-subscription/js/bootstrap.min.js';
	else return plugins_url( 'js/bootstrap.min.js', __FILE__);

}

function chargely_plugin_icon_url(){
	// Find the best price table file to use
	if(file_exists(get_stylesheet_directory().'/chargely-subscription/css/font-awesome/css/font-awesome.min.css')) return get_stylesheet_directory_uri().'/chargely-subscription/css/font-awesome/css/font-awesome.min.css';
	elseif(file_exists(get_template_directory().'/chargely-subscription/css/font-awesome/css/font-awesome.min.css')) return get_template_directory_uri().'/chargely-subscription/css/font-awesome/css/font-awesome.min.css';
	else return plugins_url( 'css/font-awesome/css/font-awesome.min.css', __FILE__);

}

/**
 * Enqueue the chargely-plugin scripts
 */
function chargely_plugin_scripts(){
	global $post, $chargely_plugin_queued, $chargely_plugin_displayed;
	if(is_singular() && (($post->post_type == 'chargely-plugin') || ($post->post_type != 'chargely-plugin' && preg_match( '#\[ *chargely-plan-table([^\]])*\]#i', $post->post_content ))) || !empty($chargely_plugin_displayed)){
        wp_enqueue_script('jquery');
		wp_enqueue_style('chargely-plugin',  chargely_plugin_css_url(), null);
		wp_enqueue_style('chargely-font-awesome',  plugins_url( 'css/font-awesome/css/font-awesome.min.css', __FILE__) , null);
		$chargely_plugin_queued = true;
		wp_enqueue_style('chargely-plugin',  chargely_plugin_boot_url(), null);
		$chargely_plugin_queued = true;
		wp_enqueue_script('chargely-plugin',  chargely_plugin_js_url(), null);
		$chargely_plugin_queued = true;
		wp_enqueue_script('chargely-plugin-icon',  chargely_plugin_icon_url(), null);
		$chargely_plugin_queued = true;
	}
}
add_action('wp_enqueue_scripts', 'chargely_plugin_scripts');

/**
 * Add administration scripts
 * @param $page
 */
function siteorigin_chargely_plugin_admin_scripts($page){
	if($page == 'post-new.php' || $page == 'post.php'){
		global $post;
		
		if(!empty($post) && $post->post_type == 'chargely-plugin'){
			// Scripts for building the chargely-plugin
			wp_enqueue_script('placeholder', 	plugins_url( 'js/placeholder.jquery.js', __FILE__), array('jquery'), '1.1.1', true);
			wp_enqueue_script('elastic', 		plugins_url( 'js/jquery.elastic.js', __FILE__), array('jquery'), '1.6.10', true);
			
			wp_enqueue_script( 'chargely-plugin-color-pic', plugins_url( 'js/color-picker.js', __FILE__), array('wp-color-picker'), false, true);

			wp_enqueue_script('jquery-ui');
			wp_enqueue_script('chargely-plugin-admin', plugins_url( 'js/chargely-plan-wp.build.js', __FILE__), array('jquery'), true);

			wp_enqueue_script( 'chargely-plugin-font-icon', plugins_url( 'js/fontawesome-iconpicker.js', __FILE__), array('jquery'));
			wp_enqueue_script( 'chargely-plugin-call-icon', plugins_url( 'js/call-icon-picker.js', __FILE__), array('jquery'), false, true);
			
			wp_localize_script('chargely-plugin-admin', 'pt_messages', array(
				'delete_column' => __('Are you sure you want to delete this column?', 'chargely-plugin'),
				'delete_feature' => __('Are you sure you want to delete this feature?', 'chargely-plugin'),
			));
			
			wp_enqueue_style('chargely-plugin-admin',  plugins_url( 'css/chargely-plan-wp.admin.css', __FILE__), array());
			
			wp_enqueue_style('jquery-ui',  plugins_url( 'css/jquery-ui.css', __FILE__), array());
			wp_enqueue_style('chargely-font-awesome-icon',  plugins_url( 'css/font-awesome/css/font-awesome.min.css', __FILE__), array());

			wp_enqueue_style('chargely-plugin-icon',  plugins_url( 'css/chargely-plan-wp.icon.css', __FILE__), array());
			wp_enqueue_style('chargely-plugin-fontawe-icon',  plugins_url( 'css/fontawesome-iconpicker.css', __FILE__), array());

			//color-picker css 
			wp_enqueue_style( 'wp-color-picker' );
		}
	}


	// The light weight CSS for changing the icon
	if(@$_GET['post_type'] == 'chargely-plugin'){
		wp_enqueue_style('chargely-plugin-icon',  plugins_url( 'css/chargely-plan-wp.icon.css', __FILE__), array());
	}
	
}
add_action('admin_enqueue_scripts', 'siteorigin_chargely_plugin_admin_scripts');

/**
 * Metaboxes because we're boss
 * 
 * @action add_meta_boxes
 */
function siteorigin_chargely_plugin_meta_boxes(){
	add_meta_box('chargely-plugin', __('Price Table Design Template', 'chargely-plugin'), 'siteorigin_chargely_plugin_render_metabox', 'chargely-plugin', 'normal', 'high');
	add_meta_box('chargely-plugin-shortcode', __('Price Table Shortcode', 'chargely-plugin'), 'siteorigin_chargely_plugin_render_metabox_shortcode', 'chargely-plugin', 'side', 'low');

	add_meta_box('chargely-plugin-sidebar', __('Price Table Sidebar Settings', 'chargely-plugin'), 'siteorigin_chargely_plugin_render_metabox_sidebar', 'chargely-plugin', 'side', 'low');
}
add_action( 'add_meta_boxes', 'siteorigin_chargely_plugin_meta_boxes' );

/**
 * Render the price table building interface
 * 
 * @param $post
 * @param $metabox
 */
function siteorigin_chargely_plugin_render_metabox($post, $metabox){
	wp_nonce_field( plugin_basename( __FILE__ ), 'siteorigin_chargely_plugin_nonce' );
	
	$table = get_post_meta($post->ID, 'chargely-plan-table', true);
	if(empty($table)) $table = array();
	
	include(dirname(__FILE__).'/views/admin/chargely-plugin.build.phtml');
}

/**
 * Render the shortcode metabox
 * @param $post
 * @param $metabox
 */
function siteorigin_chargely_plugin_render_metabox_shortcode($post, $metabox){
	?>
<h3 class="blink_me" style="color:red;font-size: 16px; font-weigth:bold;margin-top: 0;">Use this shortcode on any Page/Post to display Price table</h3>
<style>
.blink_me {
  animation: blinker 1s linear infinite;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
}
	</style>	
<code>[chargely-plan-table id=<?php print $post->ID ?>]</code>



	<?php
}

function siteorigin_chargely_plugin_render_metabox_sidebar($post, $metabox){
			
    wp_nonce_field( plugin_basename( __FILE__ ), 'siteorigin_chargely_plugin_sidebar_nonce' );
	
	$table = get_post_meta($post->ID, 'chargely-plan-table', true);
	if(empty($table)) $table = array();
	
	include(dirname(__FILE__).'/views/admin/chargely-plugin.settings.phtml');
	
}

/**
 * Save the price table
 * @param $post_id
 * @return
 * 
 * @action save_post
 */
if ( !function_exists( 'siteorigin_chargely_plugin_save' ) ) :
	function siteorigin_chargely_plugin_save($post_id) {
		// Authorization, verification this is my vocation 
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		if ( !wp_verify_nonce( @$_POST['siteorigin_chargely_plugin_nonce'], plugin_basename( __FILE__ ) ) ) return;
		if ( !current_user_can( 'edit_post', $post_id ) ) return;
		
		// Create the price table from the post variables
		$table = array();
		foreach($_POST as $name => $val){
			if(substr($name,0,6) == 'price_'){
				$parts = explode('_', $name);
				
				$i = intval($parts[1]);
				if(@$parts[2] == 'feature'){
					// Adding a feature
					$fi = intval($parts[3]);
					$fn = $parts[4];
					
					if(empty($table[$i]['features'])) $table[$i]['features'] = array();
					$table[$i]['features'][$fi][$fn] = $val;
				}
				elseif(isset($parts[2])){
					// Adding a field
					$table[$i][$parts[2]] = $val;
				}
			}
		}
		
		// Clean up the features
		foreach($table as $i => $col){
			if(empty($col['features'])) continue;
			
			foreach($col['features'] as $fi => $feature){
				if(empty($feature['title']) && empty($feature['sub']) && empty($feature['description'])){
					unset($table[$i]['features'][$fi]);
				}
			}
			$table[$i]['features'] = array_values($table[$i]['features']);
		}
		
		if(isset($_POST['price_recommend'])){
			$table[intval($_POST['price_recommend'])]['featured'] = 'true';
		}
		
		$table = array_values($table);
		
		update_post_meta($post_id,'chargely-plan-table', $table);
	}
	add_action( 'save_post', 'siteorigin_chargely_plugin_save' );
endif;

if ( !function_exists( 'chargely_plugin_meta_options_setting_save' ) ) :
	function chargely_plugin_meta_options_setting_save( $post_id ) {		
		// Authorization, verification this is my vocation 	
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		if ( !wp_verify_nonce( @$_POST['siteorigin_chargely_plugin_sidebar_nonce'], plugin_basename( __FILE__ ) ) ) return;
		if ( !current_user_can( 'edit_post', $post_id ) ) return;
		$table = array();
		if ( !empty( $_POST['box_layout'] ) ) 	{ 	$table['box_layout'] 	= sanitize_text_field( $_POST['box_layout'] ) ; }
		if ( !empty( $_POST['title_font_family'] ) ) 	{ 	$table['title_font_family'] 	= sanitize_text_field( $_POST['title_font_family'] ) ; 	}
		if ( !empty( $_POST['title_clr'] ) ) 	{ 	$table['title_clr'] 	= sanitize_text_field( $_POST['title_clr'] ) ; 	}
		if ( !empty( $_POST['plandescription_clr'] ) ) 	{ 	$table['plandescription_clr'] 	= sanitize_text_field( $_POST['plandescription_clr'] ) ; 	}
		if ( !empty( $_POST['currency_clr'] ) ) 	{ 	$table['currency_clr'] 	= sanitize_text_field( $_POST['currency_clr'] ) ; 	}
		if ( !empty( $_POST['price_clr'] ) ) 	{ 	$table['price_clr'] 	= sanitize_text_field( $_POST['price_clr'] ) ; 	}
		if ( !empty( $_POST['slash_clr'] ) ) 	{ 	$table['slash_clr'] 	= sanitize_text_field( $_POST['slash_clr'] ) ; 	}
		if ( !empty( $_POST['billingperiodtype_clr'] ) ) 	{ 	$table['billingperiodtype_clr'] 	= sanitize_text_field( $_POST['billingperiodtype_clr'] ) ; 	}
		if ( !empty( $_POST['button_clr'] ) ) 	{ 	$table['button_clr'] 	= sanitize_text_field( $_POST['button_clr'] ) ; 	}
		if ( !empty( $_POST['buttonhover_clr'] ) ) 	{ 	$table['buttonhover_clr'] 	= sanitize_text_field( $_POST['buttonhover_clr'] ) ; 	}
		if ( !empty( $_POST['icon_clr'] ) ) 	{ 	$table['icon_clr'] 		= sanitize_text_field( $_POST['icon_clr'] ) ; 	}
		if ( !empty( $_POST['ftr_bgclr'] ) ) 	{ 	$table['ftr_bgclr'] 	= sanitize_text_field( $_POST['ftr_bgclr'] ) ; 	}
		if ( !empty( $_POST['card_width'] ) ) 	{ 	$table['card_width'] 	= sanitize_text_field( $_POST['card_width'] ) ; 	}
		if ( !empty( $_POST['rbtn_clr'] ) ) 	{ 	$table['rbtn_clr'] 		= sanitize_text_field( $_POST['rbtn_clr'] ) ; 	}
		if ( !empty( $_POST['rbtn_bgclr'] ) ) 	{ 	$table['rbtn_bgclr'] 	= sanitize_text_field( $_POST['rbtn_bgclr'] ) ; 	}
		if ( !empty( $_POST['title_size'] ) ) 	{ 	$table['title_size'] 	= sanitize_text_field( $_POST['title_size'] ) ; }
		if ( !empty( $_POST['title_font'] ) ) 	{ 	$table['title_font'] 	= sanitize_text_field( $_POST['title_font'] ) ; }
		if ( !empty( $_POST['plandescription_size'] ) ) 	{ 	$table['plandescription_size'] 	= sanitize_text_field( $_POST['plandescription_size'] ) ; }
		if ( !empty( $_POST['plandescription_font'] ) ) 	{ 	$table['plandescription_font'] 	= sanitize_text_field( $_POST['plandescription_font'] ) ; }
		if ( !empty( $_POST['currency_size'] ) ) 	{ 	$table['currency_size'] 	= sanitize_text_field( $_POST['currency_size'] ) ; }
		if ( !empty( $_POST['currency_font'] ) ) 	{ 	$table['currency_font'] 	= sanitize_text_field( $_POST['currency_font'] ) ; }
		if ( !empty( $_POST['price_size'] ) ) 	{ 	$table['price_size'] 	= sanitize_text_field( $_POST['price_size'] ) ; }
		if ( !empty( $_POST['price_font'] ) ) 	{ 	$table['price_font'] 	= sanitize_text_field( $_POST['price_font'] ) ; }
		if ( !empty( $_POST['slash_size'] ) ) 	{ 	$table['slash_size'] 	= sanitize_text_field( $_POST['slash_size'] ) ; }
		if ( !empty( $_POST['slash_font'] ) ) 	{ 	$table['slash_font'] 	= sanitize_text_field( $_POST['slash_font'] ) ; }
		if ( !empty( $_POST['billingperiodtype_size'] ) ) 	{ 	$table['billingperiodtype_size'] 	= sanitize_text_field( $_POST['billingperiodtype_size'] ) ; }
		if ( !empty( $_POST['billingperiodtype_font'] ) ) 	{ 	$table['billingperiodtype_font'] 	= sanitize_text_field( $_POST['billingperiodtype_font'] ) ; }
		if ( !empty( $_POST['icon_size'] ) ) 	{ 	$table['icon_size'] 	= sanitize_text_field( $_POST['icon_size'] ) ; 	}  
		if ( !empty( $_POST['icon_font'] ) ) 	{ 	$table['icon_font'] 	= sanitize_text_field( $_POST['icon_font'] ) ; }
		if ( !empty( $_POST['rbtn_text_size'] ) ) 	{ 	$table['rbtn_text_size'] 	= sanitize_text_field( $_POST['rbtn_text_size'] ) ; 	}  
		if ( !empty( $_POST['rbtn_text_font'] ) ) 	{ 	$table['rbtn_text_font'] 	= sanitize_text_field( $_POST['rbtn_text_font'] ) ; }
		if ( !empty($_POST[ 'ftr_size'] ) ) 	{ 	$table['ftr_size'] 		= sanitize_text_field( $_POST[ 'ftr_size'] ) ; 	}
		if ( !empty( $_POST['ftr_font'] ) ) 	{ 	$table['ftr_font'] 	= sanitize_text_field( $_POST['ftr_font'] ) ; }
		if ( !empty($_POST[ 'button_size'] ) ) 	{ 	$table['button_size'] 		= sanitize_text_field( $_POST[ 'button_size'] ) ; 	}
		if ( !empty( $_POST['button_font'] ) ) 	{ 	$table['button_font'] 	= sanitize_text_field( $_POST['button_font'] ) ; }
		update_post_meta($post_id,'chargely_plugin_setting', $table);		
	}
	add_action( 'save_post', 'chargely_plugin_meta_options_setting_save' );
endif;


/**
 * The price table shortcode.
 * @param array $atts
 * @return string
 * 
 * 
 */
function siteorigin_chargely_plugin_shortcode($atts = array()) {
	global $post, $chargely_plugin_displayed;
	
	$chargely_plugin_displayed = true;
	
	extract( shortcode_atts( array(
		'id' => null,
		'width' => 100,
	), $atts ) );
	
	if($id == null) $id = $post->ID;
	
	$table = get_post_meta($id , 'chargely-plan-table', true);
	if(empty($table)) $table = array();
	
	// Set all the classes
	$featured_index = null;
	foreach($table as $i => $column) {
		$table[$i]['classes'] = array('chargely-plugin-column');
		$table[$i]['classes'][] = (@$table[$i]['featured'] === 'true') ? 'chargely-plugin-featured' : 'chargely-plugin-standard';
		
		if(@$table[$i]['featured'] == 'true') $featured_index = $i;
		if(@$table[$i+1]['featured'] == 'true') $table[$i]['classes'][] = 'chargely-plugin-before-featured';
		if(@$table[$i-1]['featured'] == 'true') $table[$i]['classes'][] = 'chargely-plugin-after-featured';
	}
	$table[0]['classes'][] = 'chargely-plugin-first';
	$table[count($table)-1]['classes'][] = 'chargely-plugin-last';
	
	// Calculate the widths
	$width_total = 0;
	foreach($table as $i => $column){
		if(@$column['featured'] === 'true') $width_total += CHARGELY_PLUGIN_FEATURED_WEIGHT;
		else $width_total++;
	}
	$width_sum = 0;
	foreach($table as $i => $column){
		if(@$column['featured'] === 'true'){
			// The featured column takes any width left over after assigning to the normal columns
			$table[$i]['width'] = 100 - (floor(100/$width_total) * ($width_total-CHARGELY_PLUGIN_FEATURED_WEIGHT));
		}
		else{
			$table[$i]['width'] = floor(100/$width_total);
		}
		$width_sum += $table[$i]['width'];
	}
	
	// Create fillers
	if(!empty($table[0]['features'])){
		for($i = 0; $i < count($table[0]['features']); $i++){
			$has_title = false;
			$has_sub = false;
			
			foreach($table as $column){
				$has_title = ($has_title || !empty($column['features'][$i]['title']));
				$has_sub = ($has_sub || !empty($column['features'][$i]['sub']));
			}
			
			foreach($table as $j => $column){
				if($has_title && empty($table[$j]['features'][$i]['title'])) $table[$j]['features'][$i]['title'] = '';
				if($has_sub && empty($table[$j]['features'][$i]['sub'])) $table[$j]['features'][$i]['sub'] = '';
			}
		}
	}
	
	// Find the best chargely-plugin file to use
	if(file_exists(get_stylesheet_directory().'/chargely-plugin.php')) $template = get_stylesheet_directory().'/chargely-plugin.php';
	elseif(file_exists(get_template_directory().'/chargely-plugin.php')) $template = get_template_directory().'/chargely-plugin.php'; 
	else $template = dirname(__FILE__).'/views/frontend/chargely-plugin.phtml';
	
	// Render the chargely-plugin
	ob_start();
	include($template);
	$chargely_plugin = ob_get_clean();
	
	if($width != 100) $chargely_plugin = '<div style="width:'.$width.'%; margin: 0 auto;">'.$chargely_plugin.'</div>';
	
	$post->chargely_plugin_inserted = true;
	
	return $chargely_plugin;
}
add_shortcode( 'chargely-plan-table', 'siteorigin_chargely_plugin_shortcode' );

/**
 * Add the chargely-plugin to the content.
 * 
 * @param $the_content
 * @return string
 * 
 * @filter the_content
 */
function siteorigin_chargely_plugin_the_content_filter($the_content){
	global $post;
	
	if(is_single() && $post->post_type == 'chargely-plugin' && empty($post->chargely_plugin_inserted)){
		$the_content = siteorigin_chargely_plugin_shortcode().$the_content;
	}
	return $the_content;
}
// Filter the content after WordPress has had a chance to do shortcodes (priority 10)
add_filter('the_content', 'siteorigin_chargely_plugin_the_content_filter',11);

function cps_activate_plan_pricing_activate_create_page(){
	$page_plan = array();
	$page_plan['post_title'] = "Plan & Pricing";
	$page_plan['post_status'] = "Publish";
	$page_plan['post_type'] = "page";
	$page_plan['post_date'] = date('Y-m-d H:i:s');
	$page_plan['post_name'] = "Plan & Pricing";

	$pageplanvalue = wp_insert_post( $page_plan, false );
    update_option( 'cps_plan_pricing', $pageplanvalue );
}
register_activation_hook(__FILE__, "cps_activate_plan_pricing_activate_create_page");


function cps_activate_plan_pricing_deactivate_create_page() {

    $page_plan_id = get_option('cps_plan_pricing');
    wp_delete_post($page_plan_id);

}
register_deactivation_hook( __FILE__, 'cps_activate_plan_pricing_deactivate_create_page' );