<?php
/*
Plugin Name: ROST Search filter
Plugin URI: https://github.com/symedia/rost-search
Description: Plugin search support by meta values
Version: 1.0.0
Author: Gregory V Lominoga (Gromodar)
Author URI: http://symedia.ru
License: GPLv2
*/

class ROSTSearch {

	/**
	 * The single instance of the class.
	 *
	 * @var ROSTSearch
	 */
	protected static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

    public function __construct() {

        $this->define( 'RS_ABSPATH', dirname( __FILE__ ) . '/' );
        include_once( RS_ABSPATH . 'includes/class-rs-autoloader.php' );
        include_once( RS_ABSPATH . 'includes/class-rs-post-types.php' );
        include_once( RS_ABSPATH . 'includes/class-rs-install.php' );

        $this->init_hooks();

    }

    public function init_hooks() {

        register_activation_hook( __FILE__, array( 'RS_Install', 'install' ) );
        register_deactivation_hook( __FILE__, array( 'RS_Install', 'uninstall' ) );

        //add_action('acf/render_field_settings', array( $this, 'search_filter_render_field_settings' ) );

        add_action( 'init', array( $this, 'init' ) );

    }

    public function init() {
        include_once( RS_ABSPATH . 'includes/class-rs-frontend.php' );
    }

	/**
	 * Define constant if not already set.
	 *
	 * @param  string $name
	 * @param  string|bool $value
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}


//    public function search_filter_render_field_settings( $field ) {
//
//        acf_render_field_setting( $field, array(
//            'label'			=> __('Фильтр ?'),
//            'instructions'	=> __('Отображать поле в фильтре'),
//            'name'			=> 'is_rost_filter',
//            'type'			=> 'true_false',
//            'ui'			=> 0,
//        ), true);
//
//    }

}

$rost_search = ROSTSearch::instance();