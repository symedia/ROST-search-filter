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

        $labels = array(
			'name'               => 'ACF фильтры', // основное название для типа записи
			'singular_name'      => 'ACF фильтр', // название для одной записи этого типа
			'add_new'            => 'Добавить фильтр', // для добавления новой записи
			'add_new_item'       => 'Добавление фильтра', // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'          => 'Редактирование фильтра', // для редактирования типа записи
			'new_item'           => 'Новый фильтр', // текст новой записи
			'view_item'          => 'Смотреть фильтр', // для просмотра записи этого типа.
			'search_items'       => 'Искать фильтр', // для поиска по этим типам записи
			'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
			'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
			'parent_item_colon'  => '', // для родителей (у древовидных типов)
			'menu_name'          => 'ACF фильтр', // название меню
        );

        $args = array(
            'labels'              => $labels,
            'public'              => false,
            'publicly_queryable'  => true,
            'exclude_from_search' => true,
            'show_ui'             => true,
            'show_in_menu'        => null,
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => null,
            'show_in_rest'        => false,
            'map_meta_cap'        => true,
            'rewrite'             => array( 'slug' => 'rs_service_page', 'with_front' => false, 'feeds' => false, 'pages' => false ),
            'supports'            => array('title'),
        );

        register_post_type( 'rs_service_page', $args );

    }

}