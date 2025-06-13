<?php
/**
 * Twenty Twenty-Five functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Five
 * @since Twenty Twenty-Five 1.0
 */

// Adds theme support for post formats.
if ( ! function_exists( 'twentytwentyfive_post_format_setup' ) ) :
    /**
     * Adds theme support for post formats.
     *
     * @since Twenty Twenty-Five 1.0
     *
     * @return void
     */
    function twentytwentyfive_post_format_setup() {
        add_theme_support( 'post-formats', array( 'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video' ) );
    }
endif;
add_action( 'after_setup_theme', 'twentytwentyfive_post_format_setup' );
//require_once get_template_directory() . '/blocks/testimonial.php';

// Enqueues editor-style.css in the editors.
if ( ! function_exists( 'twentytwentyfive_editor_style' ) ) :
    /**
     * Enqueues editor-style.css in the editors.
     *
     * @since Twenty Twenty-Five 1.0
     *
     * @return void
     */
    function twentytwentyfive_editor_style() {
        add_editor_style( get_parent_theme_file_uri( 'assets/css/editor-style.css' ) );
    }
endif;
add_action( 'after_setup_theme', 'twentytwentyfive_editor_style' );

// Enqueues style.css on the front.
if ( ! function_exists( 'twentytwentyfive_enqueue_styles' ) ) :
    /**
     * Enqueues style.css on the front.
     *
     * @since Twenty Twenty-Five 1.0
     *
     * @return void
     */
    function twentytwentyfive_enqueue_styles() {
        wp_enqueue_style(
            'twentytwentyfive-style',
            get_parent_theme_file_uri( 'style.css' ),
            array(),
            wp_get_theme()->get( 'Version' )
        );
    }
endif;
add_action( 'wp_enqueue_scripts', 'twentytwentyfive_enqueue_styles' );

// Registers custom block styles.
if ( ! function_exists( 'twentytwentyfive_block_styles' ) ) :
    /**
     * Registers custom block styles.
     *
     * @since Twenty Twenty-Five 1.0
     *
     * @return void
     */
    function twentytwentyfive_block_styles() {
        register_block_style(
            'core/list',
            array(
                'name'         => 'checkmark-list',
                'label'        => __( 'Checkmark', 'twentytwentyfive' ),
                'inline_style' => '
                ul.is-style-checkmark-list {
                    list-style-type: "\2713";
                }

                ul.is-style-checkmark-list li {
                    padding-inline-start: 1ch;
                }',
            )
        );
    }
endif;
add_action( 'init', 'twentytwentyfive_block_styles' );

// Registers pattern categories.
if ( ! function_exists( 'twentytwentyfive_pattern_categories' ) ) :
    /**
     * Registers pattern categories.
     *
     * @since Twenty Twenty-Five 1.0
     *
     * @return void
     */
    function twentytwentyfive_pattern_categories() {

        register_block_pattern_category(
            'twentytwentyfive_page',
            array(
                'label'       => __( 'Pages', 'twentytwentyfive' ),
                'description' => __( 'A collection of full page layouts.', 'twentytwentyfive' ),
            )
        );

        register_block_pattern_category(
            'twentytwentyfive_post-format',
            array(
                'label'       => __( 'Post formats', 'twentytwentyfive' ),
                'description' => __( 'A collection of post format patterns.', 'twentytwentyfive' ),
            )
        );
    }
endif;
add_action( 'init', 'twentytwentyfive_pattern_categories' );

// Registers block binding sources.
if ( ! function_exists( 'twentytwentyfive_register_block_bindings' ) ) :
    /**
     * Registers the post format block binding source.
     *
     * @since Twenty Twenty-Five 1.0
     *
     * @return void
     */
    function twentytwentyfive_register_block_bindings() {
        register_block_bindings_source(
            'twentytwentyfive/format',
            array(
                'label'              => _x( 'Post format name', 'Label for the block binding placeholder in the editor', 'twentytwentyfive' ),
                'get_value_callback' => 'twentytwentyfive_format_binding',
            )
        );
    }
endif;
add_action( 'init', 'twentytwentyfive_register_block_bindings' );

// Registers block binding callback function for the post format name.
if ( ! function_exists( 'twentytwentyfive_format_binding' ) ) :
    /**
     * Callback function for the post format name block binding source.
     *
     * @since Twenty Twenty-Five 1.0
     *
     * @return string|void Post format name, or nothing if the format is 'standard'.
     */
    function twentytwentyfive_format_binding() {
        $post_format_slug = get_post_format();

        if ( $post_format_slug && 'standard' !== $post_format_slug ) {
            return get_post_format_string( $post_format_slug );
        }
    }
endif;
function register_custom_menus() {
    register_nav_menus([
        'header-menu' => __('Header Menu'),
        'footer-menu' => __('Footer Menu')
    ]);
}
add_action('init', 'register_custom_menus');
add_action('add_meta_boxes', function () {
    add_meta_box(
        'contact_entry_meta_box',
        'Contact Entry Details',
        function ($post) {
            $name = get_post_meta($post->ID, 'name', true);
            $email = get_post_meta($post->ID, 'email', true);
            $message = get_post_meta($post->ID, 'message', true);

            echo '<p><strong>Name:</strong> ' . esc_html($name) . '</p>';
            echo '<p><strong>Email:</strong> ' . esc_html($email) . '</p>';
            echo '<p><strong>Message:</strong><br>' . nl2br(esc_html($message)) . '</p>';
        },
        'contact_entry',
        'normal',
        'default'
    );
});
// Register custom admin settings page
add_action('admin_menu', function () {
    add_menu_page(
        'Theme Settings',          // Page title
        'Theme Settings',          // Menu title
        'manage_options',          // Capability
        'theme-settings',          // Menu slug
        'theme_settings_page_html',// Callback function
        'dashicons-admin-generic', // Icon
        100                        // Position
    );
});

// Render settings page HTML
function theme_settings_page_html() {
    ?>
    <div class="wrap">
        <h1>Theme Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('theme_settings_group');
            do_settings_sections('theme-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register setting and field
add_action('admin_init', function () {
    register_setting('theme_settings_group', 'enable_dark_mode');

    add_settings_section('theme_settings_section', '', null, 'theme-settings');

    add_settings_field(
        'enable_dark_mode',
        'Enable Dark Mode',
        function () {
            $value = get_option('enable_dark_mode', '0');
            ?>
            <input type="checkbox" name="enable_dark_mode" value="1" <?php checked(1, $value); ?>>
            <label>Check to enable dark mode site-wide</label>
            <?php
        },
        'theme-settings',
        'theme_settings_section'
    );
});
add_action('rest_api_init', function () {
    register_rest_route('gatsby/v1', '/theme-settings', [
        'methods'  => 'GET',
        'callback' => function () {
            return [
                'enable_dark_mode' => get_option('enable_dark_mode') === '1',
            ];
        },
        'permission_callback' => '__return_true',
    ]);
});
