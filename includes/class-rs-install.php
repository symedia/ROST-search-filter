<?php

/*
 * Writen by Gregory V Lominoga (Gromodar)
 * E-Mail: lominogagv@gmail.com
 * Produced by Symedia studio
 * http://symedia.ru
 * E-Mail: info@symedia.ru
 */

//RS_Install::init();

class RS_Install {

    public function install() {

        RS_PostTypes::register_post_types();

        $postattr = array(
            'post_title' => 'Поиск в реестре сертифицированных организаций',
            'post_status' => 'publish',
            'post_type' => 'rs_service_page',
            'comment_status' => 'closed',
            'ping_status' => 'closed',
            'post_name' => 'rs_search_form',

        );

        wp_insert_post( $postattr );

    }

    public function uninstall() {

        $posts = get_posts( array( 'post_type' => 'rs_service_page' ) );
        foreach ( $posts as $post ) {
            wp_delete_post( $post->ID, true );
        }

        unregister_post_type( 'rs_service_page' );
    }

}