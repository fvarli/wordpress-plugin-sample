<?php

/**
 * Plugin Name: Glossary
 * Plugin URI: https://ferzendervarli.com
 * Description: This is a plugin for glossary page.
 * Version: 1.0.0
 * Author: Ferzender Varli
 * Author URI: https://ferzendervarli.com
 */

function custom_query_vars_filter($vars) {
    $vars[] .= 'startswith';
    return $vars;
}
add_filter( 'query_vars', 'custom_query_vars_filter' );

function get_all_glossary_list() {
    global $wpdb;

    if(get_query_var('search')){
        $query = $wpdb->get_results($wpdb->prepare("SELECT * FROM table WHERE title LIKE %s or description LIKE %s ORDER BY title ASC", array('%' . get_query_var('search'). '%', '%' . get_query_var('search'). '%')), ARRAY_A);
    }

    else if(get_query_var('startswith')){
        $query = $wpdb->get_results($wpdb->prepare("SELECT * FROM table WHERE title LIKE %s ORDER BY title ASC", get_query_var('startswith'). '%'), ARRAY_A);
    }

    else {
        $query = $wpdb->get_results($wpdb->prepare( "SELECT * FROM table"), ARRAY_A);
    }
    return $query;
}


add_action( 'wp_ajax_get_glossary_list_by_search', 'get_glossary_list_by_search' );
add_action( 'wp_ajax_nopriv_get_glossary_list_by_search', 'get_glossary_list_by_search' );
function get_glossary_list_by_search() {
    global $wpdb;

    // Fetch record by id
    $searchText = $_POST['searchText'];

    if(!empty($searchText)){
        $query = $wpdb->get_results($wpdb->prepare("SELECT * FROM table WHERE title LIKE %s ORDER BY title ASC", $searchText. '%'), ARRAY_A);
    }

    echo json_encode($query);
    wp_die();
}