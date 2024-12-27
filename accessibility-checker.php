<?php
/*
Plugin Name: Accessibility Checker
Description: Checks for accessibility issues in the Gutenberg editor and admin dashboard.
Version: 1.1.0
Author: Karishma Shukla
*/

// Enqueue assets for the Gutenberg editor
function accessibility_checker_enqueue_assets() {
    wp_enqueue_script(
        'accessibility-checker',
        plugins_url('build/index.js', __FILE__),
        ['wp-edit-post', 'wp-element', 'wp-components', 'wp-plugins'],
        '1.0.0',
        true
    );
    wp_localize_script('accessibility-checker', 'accessibilityCheckerSettings', [
        'restApiUrl' => esc_url(rest_url()), 
        'nonce'   => wp_create_nonce('accessibility-checker-nonce'),
    ]);
}
add_action('enqueue_block_editor_assets', 'accessibility_checker_enqueue_assets');

// Add admin menu
function accessibility_checker_add_admin_menu() {
    add_menu_page(
        'Accessibility Checker Settings',
        'Accessibility Checker',
        'manage_options',
        'accessibility-checker',
        'accessibility_checker_settings_page',
        'dashicons-universal-access-alt',
        100
    );


}
add_action('admin_menu', 'accessibility_checker_add_admin_menu');

// Render the admin settings page
function accessibility_checker_settings_page() {
    ?>
    <div class="wrap">
        <h1>Accessibility Checker Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('accessibility_checker_options_group');
            do_settings_sections('accessibility-checker');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register settings
function accessibility_checker_register_settings() {
    register_setting('accessibility_checker_options_group', 'accessibility_checker_settings');
    add_settings_section(
        'accessibility_checker_main_section',
        'General Settings',
        null,
        'accessibility-checker'
    );
    add_settings_field(
        'axe_rules',
        'Enabled Axe Rules',
        'accessibility_checker_render_rules_field',
        'accessibility-checker',
        'accessibility_checker_main_section'
    );
}
add_action('admin_init', 'accessibility_checker_register_settings');

// Render settings fields
function accessibility_checker_render_rules_field() {
    $settings = get_option('accessibility_checker_settings', []);
    $rules = isset($settings['axe_rules']) ? $settings['axe_rules'] : '';
    ?>
    <textarea name="accessibility_checker_settings[axe_rules]" rows="5" cols="50"><?php echo esc_textarea($rules); ?></textarea>
    <p class="description">Enter a comma-separated list of Axe rules to enable.</p>
    <?php
}

// Create custom table for storing scan results
function accessibility_checker_create_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'accessibility_results';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        post_id mediumint(9) NOT NULL,
        results text NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'accessibility_checker_create_table');

// Handle database updates
function accessibility_checker_update_db_check() {
    $current_version = get_option('accessibility_checker_db_version', '1.0');
    if ($current_version < '1.1') {
        accessibility_checker_create_table();
        update_option('accessibility_checker_db_version', '1.1');
    }
}
add_action('plugins_loaded', 'accessibility_checker_update_db_check');

// AJAX handler for running accessibility scans
function accessibility_checker_run_scan() {
    check_ajax_referer('accessibility-checker-nonce', 'nonce');

    $post_id = absint($_POST['post_id']);
    $post = get_post($post_id);

    if (!$post) {
        wp_send_json_error(['message' => 'Invalid post ID']);
    }

    $results = [
        'violations' => [
            ['id' => 'color-contrast', 'description' => 'Ensure color contrast is sufficient.'],
            ['id' => 'aria-roles', 'description' => 'Ensure ARIA roles are valid.']
        ]
    ];

    global $wpdb;
    $wpdb->insert(
        $wpdb->prefix . 'accessibility_results',
        [
            'post_id' => $post_id,
            'results' => wp_json_encode($results),
        ]
    );

    wp_send_json_success($results);
}
add_action('wp_ajax_accessibility_checker_run_scan', 'accessibility_checker_run_scan');

// Render scan reports page
function accessibility_checker_reports_page() {
    ?>
    <div class="wrap">
        <h1>Accessibility Scan Reports</h1>
        <table class="widefat fixed">
            <thead>
                <tr>
                    <th>Post</th>
                    <th>Violations</th>
                </tr>
            </thead>
            <tbody>
                <?php
                global $wpdb;
                $table_name = $wpdb->prefix . 'accessibility_results';
                $results = $wpdb->get_results("SELECT * FROM $table_name");

                foreach ($results as $result) {
                    $post = get_post($result->post_id);
                    ?>
                    <tr>
                        <td><?php echo esc_html($post->post_title); ?></td>
                        <td><?php echo esc_html($result->results); ?></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
}

// Admin notices for errors
function accessibility_checker_admin_notices() {
    if (get_transient('accessibility_checker_activation_error')) {
        echo '<div class="notice notice-error"><p>Accessibility Checker: Database creation failed.</p></div>';
        delete_transient('accessibility_checker_activation_error');
    }
}
add_action('admin_notices', 'accessibility_checker_admin_notices');
