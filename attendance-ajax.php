<?php

/**
 * Attendance Ajax
 *
 * @package     AttendanceAjax
 * @author      Henri Susanto
 * @copyright   2022 Henri Susanto
 * @license     GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: Attendance Ajax
 * Plugin URI:  https://github.com/susantohenri
 * Description: This plugin show attendance entries after submit
 * Version:     1.0.0
 * Author:      Henri Susanto
 * Author URI:  https://github.com/susantohenri
 * Text Domain: joss-code
 * License:     GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

add_shortcode('attendace-ajax', function ($attributes) {
    $passparam = shortcode_atts(['id' => ''], $attributes);
    $passparam['ajax_url'] = site_url('wp-json/attendace-ajax/v1/entries');

    wp_register_script('attendance-ajax', plugin_dir_url(__FILE__) . 'attendance-ajax.js', array('jquery'), '1.0', false);
    wp_enqueue_script('attendance-ajax');
    wp_localize_script(
        'attendance-ajax',
        'attendace_ajax_attributes',
        $passparam
    );

    wp_register_style('attendance-ajax', plugin_dir_url(__FILE__) . 'attendance-ajax.css');
    wp_enqueue_style('attendance-ajax');

    return '<div id="attendance-ajax"></div>';
});

add_action('rest_api_init', function () {
    register_rest_route('attendace-ajax/v1', '/entries', array(
        'methods' => 'GET',
        'callback' => function () {
            $columns = array('Name', 'Attendance', 'Guest', 'Greeting');
            $entries_args = ['form_id' => absint($_GET['id'])];
            $entries = json_decode(json_encode(wpforms()->entry->get_entries($entries_args)), true);
            $entries = array_map(function ($entry) {
                return json_decode($entry['fields']);
            }, $entries);
            return $entries;
        },
    ));
});
