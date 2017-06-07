<?php

/*
 * Writen by Gregory V Lominoga (Gromodar)
 * E-Mail: lominogagv@gmail.com
 * Produced by Symedia studio
 * http://symedia.ru
 * E-Mail: info@symedia.ru
 */

$groupID = '3075';

$acf_keys_filter_all = get_post_custom_keys( $groupID );
$acf_fields = array();
foreach ( $acf_keys_filter_all as $field_key ) {
    if ( ! get_post_meta( get_the_ID(), 'rs_' . $field_key, true ) ) {
        continue;
    }
    $acf_field = get_field_object( $field_key, $groupID );
    $acf_field['value'] = filter_input( INPUT_GET, $field_key, FILTER_SANITIZE_STRING );
    $acf_fields[$field_key] = $acf_field;
}

$submit = (bool) filter_input( INPUT_GET, 'rost_search_filter', FILTER_SANITIZE_STRING );

$is_values = false;

if ( $submit ) {

    $paged = ( get_query_var('page') ) ? get_query_var('page') : 1 ;

    $page_records_count = get_post_meta( $post->ID, 'rs_page_records_count', true );

    $post_per_page = $page_records_count ? $page_records_count : 10;

    $args = array(
        'post_type' => 'stm_portfolio',
        'post_status' => 'publish',
        'paged' => $paged,
        'posts_per_page' => $post_per_page,
    );

    $args_meta_query = array();

    foreach ( $acf_fields as $acf_key => $acf_field ) {
        if ( trim ( $acf_field['value'] ) ) {
            $is_values = true;
            $args_meta_query[] = array(
                'key' => $acf_field['name'],
                'compare' => 'LIKE',
                'value' => $acf_field['value']
            );
        }
    }

    $args['meta_query'] = $args_meta_query;

}

get_header(); ?>

<form action="/rs_service_page/rs_search_form" method="get">
  <table align="center" class="rost_search_form_table">

<?php

foreach ( $acf_fields as $acf_field ) :

    switch ($acf_field['type']) :

        case 'text' :
        case 'wysiwyg':
        case 'number':
            ?>
            <tr><td><label for="<?php echo $acf_field['key']; ?>"><?php echo $acf_field['rs_title'] ? $acf_field['rs_title'] : $acf_field['label'] ; ?></label></td>
            <td><input id="<?php echo $acf_field['key']; ?>" name="<?php echo $acf_field['key']; ?>" type="text" value="<?php echo $acf_field['value']; ?>"></td></tr>
            <?php
            break;
        case 'radio':
            ?>
            <tr><td><label for="<?php echo $acf_field['key']; ?>"><?php echo $acf_field['rs_title'] ? $acf_field['rs_title'] : $acf_field['label'] ; ?></label></td>
            <td>  <select id="<?php echo $acf_field['key']; ?>" name="<?php echo $acf_field['key']; ?>">
              <option value=""<?php if ( ! $acf_field['value'] ) : ?> selected<?php endif; ?>></option>
              <?php foreach ( $acf_field['choices'] as $option ) : ?>
              <option value="<?php echo $option ?>"<?php if ( $acf_field['value'] == $option ) : ?> selected<?php endif; ?>><?php echo $option ?></option>
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
if ( $submit && $is_values ) :

$posts = new WP_Query( $args );
$temp_query = $wp_query;
$wp_query   = NULL;
$wp_query   = $posts;

if ( $posts->have_posts() ) :

$acf_keys_column_all = get_post_custom_keys($groupID);
$acf_fields = array();
foreach ( $acf_keys_column_all as $field_key ) {
    if ( ! get_post_meta( get_the_ID(), 'rs_' . $field_key . '_column', true ) ) {
        continue;
    }
    $acf_field = get_field_object($field_key, $groupID);
    $acf_field['column_title'] = get_post_meta( get_the_ID(), 'rs_' . $field_key . '_column_title', true );
    $acf_fields[$field_key] = $acf_field;
}
?>
<br>
<br>
<table width="100%" class="rost_serch_results_table">
  <thead>
    <tr>
<?php foreach ($acf_fields as $acf_field ) :  ?>
      <th><?php echo $acf_field['label'] ? $acf_field['label'] : $acf_field['column_title']; ?></th>
<?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
<?php  while ( $posts->have_posts() ) : $posts->the_post(); ?>
  <tr>
<?php foreach ($acf_fields as $acf_field ) :  ?>
    <td><a href="<?php echo get_permalink($post); ?>"><?php echo get_post_meta( $post->ID, $acf_field['name'], true ); ?></a></td>
<?php endforeach; ?>
  </tr>
<?php endwhile; ?>
  </tbody>
</table>
<br>
<?php

else :
?>
<p class="rost_search_result_message">Не найдены данные, удовлетворяющие Вашему запросу.</p>
<?php
endif;

$result = paginate_links( array(
    'format'    => '?page=%#%',
    'current'   => $paged,
	'type'      => 'list',
	'prev_text' => '<i class="fa fa-chevron-left"></i>',
	'next_text' => '<i class="fa fa-chevron-right"></i>',
) );
$result = str_replace( '/' . $paged . '/', '', $result );
echo $result;
wp_reset_postdata();
$wp_query = NULL;
$wp_query = $temp_query;
elseif ( $submit && ! $is_values ) :
?>
<p class="rost_search_result_message">Для поиска нужно заполнить хотя бы одно поле</p>
<?php
endif;
get_footer();