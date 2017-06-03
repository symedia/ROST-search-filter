<?php

/*
 * Writen by Gregory V Lominoga (Gromodar)
 * E-Mail: lominogagv@gmail.com
 * Produced by Symedia studio
 * http://symedia.ru
 * E-Mail: info@symedia.ru
 */

get_header(); ?>
<form action="" method="get">
  <table align="center" class="rost_search_form_table">
<?php
$groupID = '3075';
$custom_field_keys = get_post_custom_keys($groupID);

$fields_keys_array = array(
    'field_59230a0162a4c', // Номер сертификата
    'field_59230a2262a4d', // Статус
    'field_59230a5562a4e', // Наименование организации
    'field_59230a6062a4f', // ФИО руководителя
    'field_59230ab762a53', // Область сертификации
    'field_59230ac262a54', // ОКВЭД(ы)
    'field_59230acd62a55', // Критерии сертификации
    //'field_59230ae962a56', // Дата выдачи
    'field_59255774baa4d', // ОГРН
    'field_59255787baa4e', // ИНН
);

$fields = array();

foreach ( $custom_field_keys as $key => $fieldkey ) :

    $field = get_field_object($fieldkey, $groupID);
    if ( ! in_array( $field['key'], $fields_keys_array ) ) { continue; }
    $fields[$fieldkey] = $field;
    switch ($field['type']) :

        case 'text' :
        case 'wysiwyg':
        case 'number':
            ?>
            <tr><td><label for="<?php echo $field['key']; ?>"><?php echo $field['label']; ?></label></td>
            <td><input id="<?php echo $field['key']; ?>" name="<?php echo $field['key']; ?>" type="text"></td></tr>
            <?php
            break;
        case 'radio':
            ?>
            <tr><td><label for="<?php echo $field['key']; ?>"><?php echo $field['label']; ?></label></td>
            <td>  <select id="<?php echo $field['key']; ?>" name="<?php echo $field['key']; ?>">
              <?php foreach ( $field['choices'] as $option ) : ?>
              <option value="<?php echo $option ?>"><?php echo $option ?></option>
              <?php endforeach; ?>
              </select></td></tr>
            <?php
            break;

    endswitch;

endforeach;
?>
    <tr>
      <td colspan="2">
        <input type="submit" name="rost_search_filter" value="Найти">
      </td>
    </tr>
  </table>
</form>

<?php
$submit = (bool) filter_input(INPUT_GET, 'rost_search_filter', FILTER_SANITIZE_STRING);
if ( $submit ):

    $args = array(
        'post_type' => 'stm_portfolio',
        'post_status' => 'publish',
        'numberposts' => -1
    );

$meta_query = array();

foreach ( $fields_keys_array as $field_key ) {
    $value = filter_input(INPUT_GET, $field_key, FILTER_SANITIZE_STRING);
    if ( trim ( $value ) ) {
        $field = $fields[$field_key];
        $meta_query[] = array(
            'key' => $field['name'],
            'compare'	=> 'LIKE',
            'value' => $value
        );
    }
}

$args['meta_query'] = $meta_query;
$posts = get_posts($args);
?>
<br>
<br>
<table width="100%" class="rost_serch_results_table">
  <thead>
    <tr>
<?php foreach ($fields as $field ) :  ?>
      <th><?php echo $field['label']; ?></th>
<?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
<?php foreach ( $posts as $post ) : ?>
  <tr>
<?php foreach ($fields as $field ) :  ?>
    <td><a href="<?php echo get_permalink($post); ?>"><?php echo get_post_meta( $post->ID, $field['name'], true ); ?></a></td>
<?php endforeach; ?>
  </tr>
<?php
endforeach;
endif;
?>
  </tbody>
</table>

<?php // print_r($fields); ?>
<?php get_footer();