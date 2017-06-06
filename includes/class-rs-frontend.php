<?php

/*
 * Writen by Gregory V Lominoga (Gromodar)
 * E-Mail: lominogagv@gmail.com
 * Produced by Symedia studio
 * http://symedia.ru
 * E-Mail: info@symedia.ru
 */

RS_Frontend::init();

class RS_Frontend {

    public static function init() {

        add_filter( 'template_include', array(__CLASS__, 'template_include'), 99 );

        add_action( 'wp_enqueue_scripts', array(__CLASS__, 'head') );

    }

    public function template_include($page_template) {

        if ( is_singular( 'rs_service_page' ) ) {
            $page_template = RS_ABSPATH . '/templates/rs_search_form.php';
        }
        return $page_template;

    }

    public function head() {
        wp_enqueue_style( 'rost_search_form_style', plugin_dir_url( RS_ABSPATH . '/rost-search.php' ) . 'assets/css/styles.css' );
    }

}