<?php

/*
 * Writen by Gregory V Lominoga (Gromodar)
 * E-Mail: lominogagv@gmail.com
 * Produced by Symedia studio
 * http://symedia.ru
 * E-Mail: info@symedia.ru
 */

RS_Admin::init();

class RS_Admin {

    public static function init () {
        add_action( 'admin_init', array( __CLASS__,  'add_meta_box' ) );
        add_action( 'save_post', array( __CLASS__,  'save_meta_box' ) );
        add_action( 'do_meta_boxes',  array( __CLASS__,  'remove_meta_boxes' ) );
    }

    public function remove_meta_boxes ( ) {
        if ( is_admin() ) {
            remove_meta_box('mymetabox_revslider_0', 'rs_service_page', 'normal');
        }
    }

    public function add_meta_box () {
        add_meta_box('rost_search_filter', 'Поля для вывода в фильтре',
                array(__CLASS__, 'meta_html'), 'rs_service_page', 'advanced', 'high');
    }

    public function meta_html ( $post ) {

        $groupID = '3075';

        $acf_keys_all = get_post_custom_keys($groupID);

        foreach ( $acf_keys_all as $fieldkey ) {
            $acf_field = get_field_object($fieldkey, $groupID);
            if ( ! $acf_field['label']
                    || isset( $acf_field['media_upload'] )
                    || $acf_field['type'] === 'date_picker' ) {
                continue;
            }
            $acf_field['rost_publish'] = get_post_meta( $post->ID, 'rs_' . $fieldkey, true );
            $acf_field['rost_title'] = get_post_meta( $post->ID, 'rs_' . $fieldkey . '_title', true );
            $acf_fields[$fieldkey] = $acf_field;
        }

//        exit(print_r($acf_fields));

        wp_nonce_field( plugin_basename(__FILE__), 'rs_search_filter_noncename' );

        foreach ( $acf_fields as $acf_field ) :
        ?>

        <fieldset>
          <legend><b><?php echo $acf_field['label']; ?></b></legend>
            <label for="rs_<?php echo $acf_field['key']; ?>">
            <input type="checkbox" id="rs_<?php echo $acf_field['key']; ?>" name="rs_<?php echo $acf_field['key']; ?>" value="1"<?php if ( $acf_field['rost_publish'] ) : ?> checked<?php endif; ?>>
            Вкл/Выкл</label><br>
            <label for="rs_<?php echo $acf_field['key']; ?>_title">Название поля:</label>
            <input id="rs_<?php echo $acf_field['key']; ?>_title" type="text" name="rs_<?php echo $acf_field['key']; ?>_title" style="width: 100%;" value="<?php echo $acf_field['rost_title'] ?>">
        </fieldset>
        <hr>

        <?php
        endforeach;

    }

    public function save_meta_box( $post_id ) {

        if ( ! wp_verify_nonce( $_POST['rs_search_filter_noncename'], plugin_basename(__FILE__) ) ) {
            return $post_id;
        }

        // проверяем, если это автосохранение ничего не делаем с данными нашей формы.
        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
            return $post_id;
        }

        // проверяем разрешено ли пользователю указывать эти данные
        if ( 'rs_service_page' == $_POST['post_type'] && ! current_user_can( 'edit_page', $post_id ) ) {
              return $post_id;
        } elseif( ! current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
        }

        $groupID = '3075';

        $acf_keys_all = get_post_custom_keys($groupID);

        foreach ( $acf_keys_all as $fieldkey ) {
            $acf_field = get_field_object($fieldkey, $groupID);
            if ( ! $acf_field['label']
                    || isset( $acf_field['media_upload'] )
                    || $acf_field['type'] === 'date_picker' ) {
                continue;
            }

            $publish = filter_input(INPUT_POST, 'rs_' . $acf_field['key'], FILTER_SANITIZE_NUMBER_INT);
            $title = sanitize_text_field( filter_input(INPUT_POST, 'rs_' . $acf_field['key'] . '_title', FILTER_SANITIZE_STRING) );

            update_post_meta( $post_id, 'rs_' . $acf_field['key'], $publish );
            update_post_meta( $post_id, 'rs_' . $acf_field['key'] . '_title', $title );
        }


    }

}