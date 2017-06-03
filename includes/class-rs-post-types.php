<?php

/*
 * Writen by Gregory V Lominoga (Gromodar)
 * E-Mail: lominogagv@gmail.com
 * Produced by Symedia studio
 * http://symedia.ru
 * E-Mail: info@symedia.ru
 */

RS_PostTypes::init();

class RS_PostTypes {

    public static function init() {

        add_action( 'init', array( __CLASS__, 'register_post_types' ), 5 );

    }

    public static function register_post_types() {

        $args = array(
            'public'              => false,
            'publicly_queryable'  => true,
            'exclude_from_search' => true,
            'show_ui'             => false,
            'show_in_menu'        => null,
            'show_in_admin_bar'   => null,
            'show_in_nav_menus'   => null,
            'show_in_rest'        => false,
            'map_meta_cap'        => true,
            'rewrite'             => array('slug' => 'rs_service_page', 'with_front' => false, 'feeds' => false, 'pages' => false),
        );

        register_post_type('rs_service_page', $args);

    }

}