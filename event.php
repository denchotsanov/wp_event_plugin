<?php
/*
*   Plugin Name: DTS Events plugin
*   Author: Dencho Tsanov
*/

function create_post_type()
{
    register_post_type('events', [
        'labels' => [
            'name'                => __('Events'),
            'singular_name'       => __('Event'),
            'menu_name'           => __( 'Events'),
            'all_items'           => __( 'All Events'),
            'view_item'           => __( 'View Event'),
            'add_new_item'        => __( 'Add New Event'),
            'add_new'             => __( 'Add New'),
            'edit_item'           => __( 'Edit Event'),
            'update_item'         => __( 'Update Event'),
            'search_items'        => __( 'Search Event'),
            'not_found'           => __( 'Not Found'),
            'not_found_in_trash'  => __( 'Not found in Trash'),
        ],
        'supports'            => ['title'],
        'public' => true,
            'has_archive' => true,
        'rewrite' => ['slug' => 'events'],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'create_post_type');

function add_acf_columns ( $columns ) {
    unset( $columns['date'] );
    return array_merge ( $columns, [
        'event_date' => __ ( 'Event Date' ),
        'event_location' => __ ( 'Event location' ),
        'event_url' => __ ( 'Event Url' ),
    ]);
}
add_filter ( 'manage_events_posts_columns', 'add_acf_columns' );
function events_custom_column ( $column, $post_id ) {
    switch ( $column ) {
        case 'event_date':
            echo get_post_meta ( $post_id, 'event_date', true );
            break;
        case 'event_location':
            echo get_post_meta ( $post_id, 'event_location', true );
            break;
        case 'event_url':
            echo get_post_meta ( $post_id, 'event_url', true );
            break;

    }
}
add_action ( 'manage_events_posts_custom_column',    'events_custom_column', 10, 2 );


function events_add_meta_box() {
    add_meta_box(
        'event_date',
        'Event Date',
        'event_data_meta_box_callback',
        'events'
    );
    add_meta_box(
        'event_location',
        'Event location',
        'event_location_meta_box_callback',
        'events'
    );
    add_meta_box(
        'event_url',
        'Event URL',
        'event_url_meta_box_callback',
        'events'
    );
}
add_action( 'add_meta_boxes', 'events_add_meta_box' );
function event_data_meta_box_callback($post){
    $value = get_post_meta($post->ID,'event_date',true);
    echo '<input type="date" id="datePick" name=event_date value="'.$value.'"/>';
}

function event_location_meta_box_callback($post){
    $value = get_post_meta($post->ID,'event_location',true);
    echo '<input type="text" name="event_location" value="'.$value.'"/>';
}
function event_url_meta_box_callback($post){
    $value = get_post_meta($post->ID,'event_url',true);
    echo '<input type="text" name="event_url" value="'.$value.'"/>';
}

function event_save_postdata($post_id){
    if (isset($_POST['post_type']) && $_POST['post_type'] == 'events') {
        update_post_meta($post_id, 'event_date', sanitize_text_field( $_REQUEST['event_date'] ));
        update_post_meta($post_id, 'event_location', sanitize_text_field( $_REQUEST['event_location'] ));
        update_post_meta($post_id, 'event_url', sanitize_text_field( $_REQUEST['event_url'] ));
    }
}
add_action( 'save_post', 'event_save_postdata' );

function event_init_template_logic($original_template) {
    $file = trailingslashit(get_template_directory()) . 'archive-events.php';

    if(is_post_type_archive('events')) {
        if(file_exists($file)) {
            return trailingslashit(get_template_directory()).'archive-events.php';
        } else {
            return plugin_dir_path(__DIR__) . 'event/templates/archive-events.php';
        }
    } elseif(is_singular('events')) {
        if(file_exists(get_template_directory_uri() . '/single-events.php')) {
            return get_template_directory_uri() . '/single-events.php';
        } else {
            return plugin_dir_path(__DIR__) . 'event/templates/single-events.php';
        }
    }

    return $original_template;
}
add_action('template_include', 'event_init_template_logic');
