<?php
/*
Plugin Name: ROST Search filter
Plugin URI: https://github.com/symedia/rost-search
Description: Plugin search support by meta values
Version: 1.3
Author: Gregory V Lominoga (Gromodar)
Author URI: http://symedia.ru
License: GPLv2
GitHub Plugin URI: https://github.com/symedia/ROST-search-filter
*/
/*  Copyright 2017 Gregory V Lominoga (email: lominogagv@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
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

        add_action( 'init', array( $this, 'init' ) );

    }

    public function init() {
        include_once( RS_ABSPATH . 'includes/class-rs-admin.php' );
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