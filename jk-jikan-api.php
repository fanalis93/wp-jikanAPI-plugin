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

        add_action( 'admin_menu', array( $this, 'jikan_create_settings' ) );

    }

    function load_textdomain()
    {
        load_plugin_textdomain('jikan', false, plugin_dir_url(__FILE__) . "/languages");
    }
    function jikan_create_settings()
    {
        $main = add_menu_page(
            __('Jikan Fetch Anime', 'jikan'),
            __('Jikan Fetch Anime', 'jikan'),
            'manage_options',
            'jikan-animes',
            array($this,'jikan_settings_callback'),
            'dashicons-store',
            110);
        $genre = add_submenu_page(
            'jikan-animes',
            __('All Genres', 'jikan'),
            __('All Genres', 'jikan'),
            'manage_options',
            'jikan-genres',
            array($this,'jikan_genres_callback')
        );
        $user = add_submenu_page(
            'jikan-animes',
            __('Fetch User', 'jikan'),
            __('Fetch User', 'jikan'),
            'manage_options',
            'jikan-user',
            array($this,'jikan_user_callback')
        );
        foreach(array($main,$genre,$user) as $slug) {
            // make sure the style callback is used on our page only
            add_action(
                "admin_print_styles-$slug",
                array($this,'enqueue_style')
            );

            // make sure the script callback is used on our page only
            add_action(
                "admin_print_scripts-$slug",
                array ( $this, 'enqueue_script' )
            );
        }

    }
    public static function enqueue_style()
    {
        wp_register_style(
            'jikan-style',
            plugins_url( 'assets/css/style.css', __FILE__ )
        );
        wp_enqueue_style( 'jikan-style' );
    }
    public static function enqueue_script()
    {
        wp_register_script(
            'jikan-script',
            plugins_url( 'assets/js/main.js', __FILE__ ),
            array(),
            FALSE,
            TRUE
        );
        wp_enqueue_script( 'jikan-script' );
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
