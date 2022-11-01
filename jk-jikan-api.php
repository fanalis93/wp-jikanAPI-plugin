<?php

/**
 * Plugin Name:         JK Jikan API
 * Plugin URI:          https://www.github.com/fayekalvi/
 * Description:         JK Jikan API
 * Version:             1.0.0
 * Author:              Fayek Alvi Rahman Jaki
 * Author URI:          https://www.github.com/fayekalvi/
 * Text Domain:         jikanapi
 * Domain Path:         /languages/
 * License:             GNU General Public License v2.0 or later
 * License URI:         http://www.gnu.org/licenses/gpl-2.0.txt
 * Requires at least:   5.2
 * Tested up to:        6.0
 *
 *
 */


define("JKA_ASSETS_DIR", plugin_dir_url(__FILE__) . "assets/");
require_once "class.jikan-anime.php";
require_once "class.jikan-genre.php";
require_once "class.jikan-user.php";
class JikanAPI
{
    function __construct()
    {

        add_action('plugins_loaded', array($this, 'load_textdomain'));
        add_action('admin_enqueue_scripts', function ($hook) {
            if ("toplevel_page_jikan-animes" == $hook) {
                wp_enqueue_style('jikan-style', plugin_dir_url(__FILE__) . 'assets/css/style.css');
                wp_enqueue_script('jikan-js', plugin_dir_url(__FILE__) . 'assets/js/main.js', array('jquery'), '1.1.1', true);
            }
        });
        add_action( 'wp_enqueue_scripts', 'wpdocs_theme_name_scripts' );

        add_action( 'admin_menu', array( $this, 'jikan_create_settings' ) );
        add_action('admin_menu', array($this, 'wpdocs_register_my_custom_submenu_page'),99);

    }

    function load_textdomain()
    {
        load_plugin_textdomain('jikan', false, plugin_dir_url(__FILE__) . "/languages");
    }
    function jikan_create_settings()
    {
        add_menu_page(
            __('Jikan Fetch Anime', 'jikan'),
            __('Jikan Fetch Anime', 'jikan'),
            'manage_options',
            'jikan-animes',
            array($this,'jikan_settings_callback'),
            'dashicons-store',
            110);

    }

    function wpdocs_register_my_custom_submenu_page() {
        add_submenu_page(
            'jikan-animes',
            __('All Genres', 'jikan'),
            __('All Genres', 'jikan'),
            'manage_options',
            'jikan-genres',
            array($this,'jikan_genres_callback')
        );
        add_submenu_page(
            'jikan-animes',
            __('Fetch User', 'jikan'),
            __('Fetch User', 'jikan'),
            'manage_options',
            'jikan-user',
            array($this,'jikan_user_callback')
        );
    }

    function jikan_settings_callback() {
        $test = new JikanAnime();
        $test->greeting();
        $test->jikan_settings_content();
    }
    function jikan_genres_callback() {
        $genre = new JikanGenre();
        $genre->jikan_genres_content();
    }
    function jikan_user_callback() {
        $user = new JikanUser();
        $user->jikan_user_content();
    }




}

new JikanAPI();
