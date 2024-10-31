<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://myfaveswp.com
 * @since      1.0.0
 *
 * @package    Myfaves
 * @subpackage Myfaves/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Myfaves
 * @subpackage Myfaves/public
 * @author     Sana Azmeh <hello@myfaveswp.com>
 */
class Myfaves_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Myfaves_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Myfaves_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/myfaves-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Myfaves_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Myfaves_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_script('myfaves-globalscript', plugin_dir_url( __FILE__ ).'js/gscript.js', array('jquery'),  $this->version, false);
			wp_localize_script(
			    'myfaves-globalscript',
			    'myfavesGlobalObj',
			    array(
			    'homeUrl' => esc_url(home_url()),
			    'templateDirUrl' => esc_url(get_template_directory_uri())
			    )
			);
		wp_enqueue_script('myfaves-globalscript');
		$name_nonce = wp_create_nonce( 'MyFaves' );
		
		

		wp_register_script('myfaves-ajax-gscript', plugin_dir_url( __FILE__ ).'js/gscript.js', array('jquery'),  $this->version, false);
   		 wp_localize_script('myfaves-ajax-gscript', 
   		 					'myfaves_ajax_obj', 
   		 					array(	'ajax_url' => admin_url( 'admin-ajax.php' ),
       		 						'nonce'    => $name_nonce,
    							) 
   		 					);
   		 wp_enqueue_script('myfaves-ajax-gscript');
   		 wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/myfaves-public.js', array( 'jquery' ), filemtime( plugin_dir_path( __FILE__ ) . 'js/myfaves-public.js' ), false );

	}

}
