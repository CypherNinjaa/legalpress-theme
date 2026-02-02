<?php
/**
 * GitHub Theme Updater
 *
 * Allows clients to update the theme directly from GitHub releases.
 * Checks for new versions and provides one-click updates through WordPress admin.
 *
 * @package LegalPress
 * @since 2.5.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * GitHub Theme Updater Class
 */
class LegalPress_GitHub_Updater {
    
    /**
     * GitHub repository owner
     * @var string
     */
    private $github_username;
    
    /**
     * GitHub repository name
     * @var string
     */
    private $github_repo;
    
    /**
     * GitHub access token (optional, for private repos or higher rate limits)
     * @var string
     */
    private $access_token;
    
    /**
     * Theme slug
     * @var string
     */
    private $theme_slug;
    
    /**
     * Current theme version
     * @var string
     */
    private $current_version;
    
    /**
     * GitHub API response cache
     * @var object
     */
    private $github_response;
    
    /**
     * Constructor
     */
    public function __construct() {
        // Get settings from customizer or use defaults
        $this->github_username = get_theme_mod('legalpress_github_username', 'CypherNinjaa');
        $this->github_repo = get_theme_mod('legalpress_github_repo', 'legalpress-theme');
        $this->access_token = get_theme_mod('legalpress_github_token', '');
        $this->theme_slug = 'legalpress';
        
        // Get current theme version
        $theme = wp_get_theme($this->theme_slug);
        $this->current_version = $theme->get('Version');
        
        // Only run in admin
        if (is_admin()) {
            add_filter('pre_set_site_transient_update_themes', array($this, 'check_for_update'));
            add_filter('themes_api', array($this, 'theme_info'), 20, 3);
            add_filter('upgrader_source_selection', array($this, 'fix_directory_name'), 10, 4);
            add_action('admin_notices', array($this, 'update_notice'));
            
            // Add settings page
            add_action('admin_menu', array($this, 'add_settings_page'));
            add_action('admin_init', array($this, 'register_settings'));
            
            // AJAX handlers
            add_action('wp_ajax_legalpress_check_github_update', array($this, 'ajax_check_update'));
            add_action('wp_ajax_legalpress_clear_update_cache', array($this, 'ajax_clear_cache'));
        }
    }
    
    /**
     * Get GitHub repository data
     *
     * @return object|false Repository data or false on failure
     */
    private function get_github_data() {
        if (!empty($this->github_response)) {
            return $this->github_response;
        }
        
        // Check cache first
        $cached = get_transient('legalpress_github_response');
        if ($cached !== false) {
            $this->github_response = $cached;
            return $cached;
        }
        
        // Build API URL for latest release
        $api_url = sprintf(
            'https://api.github.com/repos/%s/%s/releases/latest',
            $this->github_username,
            $this->github_repo
        );
        
        // Set up request args
        $args = array(
            'timeout' => 10,
            'headers' => array(
                'Accept' => 'application/vnd.github.v3+json',
                'User-Agent' => 'WordPress/' . get_bloginfo('version') . '; ' . home_url(),
            ),
        );
        
        // Add authorization if token exists
        if (!empty($this->access_token)) {
            $args['headers']['Authorization'] = 'token ' . $this->access_token;
        }
        
        // Make request
        $response = wp_remote_get($api_url, $args);
        
        if (is_wp_error($response)) {
            return false;
        }
        
        $response_code = wp_remote_retrieve_response_code($response);
        if ($response_code !== 200) {
            return false;
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body);
        
        if (empty($data) || !isset($data->tag_name)) {
            return false;
        }
        
        // Cache for 6 hours
        set_transient('legalpress_github_response', $data, 6 * HOUR_IN_SECONDS);
        
        $this->github_response = $data;
        return $data;
    }
    
    /**
     * Check for theme updates
     *
     * @param object $transient Update transient
     * @return object Modified transient
     */
    public function check_for_update($transient) {
        if (empty($transient->checked)) {
            return $transient;
        }
        
        $github_data = $this->get_github_data();
        
        if (!$github_data) {
            return $transient;
        }
        
        // Get version from tag (remove 'v' prefix if present)
        $github_version = ltrim($github_data->tag_name, 'v');
        
        // Compare versions
        if (version_compare($github_version, $this->current_version, '>')) {
            // Get download URL
            $download_url = $this->get_download_url($github_data);
            
            if ($download_url) {
                $transient->response[$this->theme_slug] = array(
                    'theme' => $this->theme_slug,
                    'new_version' => $github_version,
                    'url' => $github_data->html_url,
                    'package' => $download_url,
                    'requires' => '5.0',
                    'requires_php' => '7.4',
                );
            }
        }
        
        return $transient;
    }
    
    /**
     * Get download URL for the release
     *
     * @param object $release_data GitHub release data
     * @return string|false Download URL or false
     */
    private function get_download_url($release_data) {
        // First, check for a zip asset in the release
        if (!empty($release_data->assets) && is_array($release_data->assets)) {
            foreach ($release_data->assets as $asset) {
                if (strpos($asset->name, '.zip') !== false) {
                    $url = $asset->browser_download_url;
                    
                    // Add token for private repos
                    if (!empty($this->access_token)) {
                        $url = add_query_arg('access_token', $this->access_token, $url);
                    }
                    
                    return $url;
                }
            }
        }
        
        // Fall back to zipball URL
        if (!empty($release_data->zipball_url)) {
            $url = $release_data->zipball_url;
            
            if (!empty($this->access_token)) {
                $url = add_query_arg('access_token', $this->access_token, $url);
            }
            
            return $url;
        }
        
        return false;
    }
    
    /**
     * Provide theme information for the update details popup
     *
     * @param false|object|array $result The result object or array
     * @param string $action The type of information being requested
     * @param object $args Query arguments
     * @return object|false Theme info or false
     */
    public function theme_info($result, $action, $args) {
        if ($action !== 'theme_information') {
            return $result;
        }
        
        if (!isset($args->slug) || $args->slug !== $this->theme_slug) {
            return $result;
        }
        
        $github_data = $this->get_github_data();
        
        if (!$github_data) {
            return $result;
        }
        
        $github_version = ltrim($github_data->tag_name, 'v');
        $theme = wp_get_theme($this->theme_slug);
        
        $result = (object) array(
            'name' => $theme->get('Name'),
            'slug' => $this->theme_slug,
            'version' => $github_version,
            'author' => $theme->get('Author'),
            'homepage' => $theme->get('ThemeURI'),
            'requires' => '5.0',
            'requires_php' => '7.4',
            'downloaded' => 0,
            'last_updated' => $github_data->published_at,
            'sections' => array(
                'description' => $theme->get('Description'),
                'changelog' => $this->parse_changelog($github_data->body),
            ),
            'download_link' => $this->get_download_url($github_data),
        );
        
        return $result;
    }
    
    /**
     * Parse changelog from release body
     *
     * @param string $body Release body/description
     * @return string HTML formatted changelog
     */
    private function parse_changelog($body) {
        if (empty($body)) {
            return '<p>No changelog available.</p>';
        }
        
        // Convert markdown to HTML (basic conversion)
        $changelog = esc_html($body);
        $changelog = nl2br($changelog);
        
        // Convert markdown headers
        $changelog = preg_replace('/^### (.+)$/m', '<h4>$1</h4>', $changelog);
        $changelog = preg_replace('/^## (.+)$/m', '<h3>$1</h3>', $changelog);
        $changelog = preg_replace('/^# (.+)$/m', '<h2>$1</h2>', $changelog);
        
        // Convert markdown lists
        $changelog = preg_replace('/^- (.+)$/m', '<li>$1</li>', $changelog);
        $changelog = preg_replace('/^\* (.+)$/m', '<li>$1</li>', $changelog);
        
        // Wrap consecutive li elements in ul
        $changelog = preg_replace('/(<li>.*<\/li>\s*)+/s', '<ul>$0</ul>', $changelog);
        
        // Convert bold and italic
        $changelog = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $changelog);
        $changelog = preg_replace('/\*(.+?)\*/', '<em>$1</em>', $changelog);
        
        return $changelog;
    }
    
    /**
     * Fix directory name after download
     * GitHub archives have different folder names, we need to rename it
     *
     * @param string $source Source directory
     * @param string $remote_source Remote source
     * @param object $upgrader Upgrader instance
     * @param array $hook_extra Extra hook data
     * @return string Fixed source path
     */
    public function fix_directory_name($source, $remote_source, $upgrader, $hook_extra) {
        global $wp_filesystem;
        
        // Check if this is our theme
        if (!isset($hook_extra['theme']) || $hook_extra['theme'] !== $this->theme_slug) {
            return $source;
        }
        
        // Get the correct directory name
        $corrected_source = trailingslashit($remote_source) . $this->theme_slug . '/';
        
        // If the source is already correct, return it
        if ($source === $corrected_source) {
            return $source;
        }
        
        // Move/rename the directory
        if ($wp_filesystem->move($source, $corrected_source, true)) {
            return $corrected_source;
        }
        
        return $source;
    }
    
    /**
     * Display update notice in admin
     */
    public function update_notice() {
        // Only show on theme pages
        $screen = get_current_screen();
        if (!$screen || !in_array($screen->id, array('themes', 'update-core', 'appearance_page_legalpress-github-updater'))) {
            return;
        }
        
        $github_data = $this->get_github_data();
        
        if (!$github_data) {
            return;
        }
        
        $github_version = ltrim($github_data->tag_name, 'v');
        
        if (version_compare($github_version, $this->current_version, '>')) {
            $update_url = admin_url('update-core.php');
            ?>
            <div class="notice notice-warning is-dismissible">
                <p>
                    <strong><?php esc_html_e('LegalPress Theme Update Available!', 'legalpress'); ?></strong>
                    <?php
                    printf(
                        /* translators: 1: Current version, 2: New version, 3: Update URL */
                        esc_html__('Version %1$s is available. You are running %2$s. %3$s', 'legalpress'),
                        '<code>' . esc_html($github_version) . '</code>',
                        '<code>' . esc_html($this->current_version) . '</code>',
                        '<a href="' . esc_url($update_url) . '">' . esc_html__('Update now', 'legalpress') . '</a>'
                    );
                    ?>
                </p>
            </div>
            <?php
        }
    }
    
    /**
     * Add settings page to admin menu
     */
    public function add_settings_page() {
        add_theme_page(
            __('GitHub Updater', 'legalpress'),
            __('GitHub Updater', 'legalpress'),
            'manage_options',
            'legalpress-github-updater',
            array($this, 'render_settings_page')
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('legalpress_github_updater', 'legalpress_github_settings', array(
            'sanitize_callback' => array($this, 'sanitize_settings'),
        ));
        
        add_settings_section(
            'legalpress_github_settings_section',
            __('GitHub Repository Settings', 'legalpress'),
            array($this, 'settings_section_callback'),
            'legalpress-github-updater'
        );
        
        add_settings_field(
            'github_username',
            __('GitHub Username/Organization', 'legalpress'),
            array($this, 'render_username_field'),
            'legalpress-github-updater',
            'legalpress_github_settings_section'
        );
        
        add_settings_field(
            'github_repo',
            __('Repository Name', 'legalpress'),
            array($this, 'render_repo_field'),
            'legalpress-github-updater',
            'legalpress_github_settings_section'
        );
        
        add_settings_field(
            'github_token',
            __('Access Token (Optional)', 'legalpress'),
            array($this, 'render_token_field'),
            'legalpress-github-updater',
            'legalpress_github_settings_section'
        );
    }
    
    /**
     * Sanitize settings
     *
     * @param array $input Input values
     * @return array Sanitized values
     */
    public function sanitize_settings($input) {
        $sanitized = array();
        
        if (isset($input['github_username'])) {
            $sanitized['github_username'] = sanitize_text_field($input['github_username']);
            set_theme_mod('legalpress_github_username', $sanitized['github_username']);
        }
        
        if (isset($input['github_repo'])) {
            $sanitized['github_repo'] = sanitize_text_field($input['github_repo']);
            set_theme_mod('legalpress_github_repo', $sanitized['github_repo']);
        }
        
        if (isset($input['github_token'])) {
            $sanitized['github_token'] = sanitize_text_field($input['github_token']);
            set_theme_mod('legalpress_github_token', $sanitized['github_token']);
        }
        
        // Clear cache when settings change
        delete_transient('legalpress_github_response');
        
        return $sanitized;
    }
    
    /**
     * Settings section callback
     */
    public function settings_section_callback() {
        echo '<p>' . esc_html__('Configure your GitHub repository settings for automatic theme updates.', 'legalpress') . '</p>';
    }
    
    /**
     * Render username field
     */
    public function render_username_field() {
        $value = get_theme_mod('legalpress_github_username', 'CypherNinjaa');
        ?>
        <input type="text" name="legalpress_github_settings[github_username]" value="<?php echo esc_attr($value); ?>" class="regular-text" />
        <p class="description"><?php esc_html_e('Your GitHub username or organization name.', 'legalpress'); ?></p>
        <?php
    }
    
    /**
     * Render repo field
     */
    public function render_repo_field() {
        $value = get_theme_mod('legalpress_github_repo', 'legalpress-theme');
        ?>
        <input type="text" name="legalpress_github_settings[github_repo]" value="<?php echo esc_attr($value); ?>" class="regular-text" />
        <p class="description"><?php esc_html_e('The name of your GitHub repository.', 'legalpress'); ?></p>
        <?php
    }
    
    /**
     * Render token field
     */
    public function render_token_field() {
        $value = get_theme_mod('legalpress_github_token', '');
        ?>
        <input type="password" name="legalpress_github_settings[github_token]" value="<?php echo esc_attr($value); ?>" class="regular-text" autocomplete="new-password" />
        <p class="description">
            <?php esc_html_e('Optional: Required for private repositories or to avoid rate limits.', 'legalpress'); ?>
            <a href="https://github.com/settings/tokens" target="_blank"><?php esc_html_e('Generate a token', 'legalpress'); ?></a>
        </p>
        <?php
    }
    
    /**
     * Render settings page
     */
    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        $github_data = $this->get_github_data();
        $github_version = $github_data ? ltrim($github_data->tag_name, 'v') : false;
        $has_update = $github_version && version_compare($github_version, $this->current_version, '>');
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('LegalPress GitHub Updater', 'legalpress'); ?></h1>
            
            <!-- Status Card -->
            <div class="legalpress-updater-card">
                <h2><?php esc_html_e('Update Status', 'legalpress'); ?></h2>
                
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><?php esc_html_e('Current Version', 'legalpress'); ?></th>
                        <td><code><?php echo esc_html($this->current_version); ?></code></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Latest Version (GitHub)', 'legalpress'); ?></th>
                        <td>
                            <?php if ($github_version) : ?>
                                <code><?php echo esc_html($github_version); ?></code>
                                <?php if ($has_update) : ?>
                                    <span class="dashicons dashicons-warning" style="color: #dba617;"></span>
                                    <span style="color: #dba617;"><?php esc_html_e('Update available!', 'legalpress'); ?></span>
                                <?php else : ?>
                                    <span class="dashicons dashicons-yes-alt" style="color: #00a32a;"></span>
                                    <span style="color: #00a32a;"><?php esc_html_e('You are up to date!', 'legalpress'); ?></span>
                                <?php endif; ?>
                            <?php else : ?>
                                <span class="dashicons dashicons-dismiss" style="color: #d63638;"></span>
                                <span style="color: #d63638;"><?php esc_html_e('Could not fetch version info', 'legalpress'); ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php if ($github_data && !empty($github_data->published_at)) : ?>
                    <tr>
                        <th scope="row"><?php esc_html_e('Last Release Date', 'legalpress'); ?></th>
                        <td><?php echo esc_html(date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($github_data->published_at))); ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <th scope="row"><?php esc_html_e('Repository', 'legalpress'); ?></th>
                        <td>
                            <a href="https://github.com/<?php echo esc_attr($this->github_username); ?>/<?php echo esc_attr($this->github_repo); ?>" target="_blank">
                                <?php echo esc_html($this->github_username . '/' . $this->github_repo); ?>
                                <span class="dashicons dashicons-external"></span>
                            </a>
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <?php if ($has_update) : ?>
                        <a href="<?php echo esc_url(admin_url('update-core.php')); ?>" class="button button-primary">
                            <?php esc_html_e('Update Theme Now', 'legalpress'); ?>
                        </a>
                    <?php endif; ?>
                    <button type="button" id="legalpress-check-update" class="button">
                        <?php esc_html_e('Check for Updates', 'legalpress'); ?>
                    </button>
                    <button type="button" id="legalpress-clear-cache" class="button">
                        <?php esc_html_e('Clear Cache', 'legalpress'); ?>
                    </button>
                    <span id="legalpress-update-status" style="margin-left: 10px;"></span>
                </p>
            </div>
            
            <!-- Settings Form -->
            <div class="legalpress-updater-card">
                <form method="post" action="options.php">
                    <?php
                    settings_fields('legalpress_github_updater');
                    do_settings_sections('legalpress-github-updater');
                    submit_button(__('Save Settings', 'legalpress'));
                    ?>
                </form>
            </div>
            
            <!-- Changelog -->
            <?php if ($github_data && !empty($github_data->body)) : ?>
            <div class="legalpress-updater-card">
                <h2><?php esc_html_e('Latest Release Notes', 'legalpress'); ?></h2>
                <div class="legalpress-changelog">
                    <?php echo wp_kses_post($this->parse_changelog($github_data->body)); ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- How to Create a Release -->
            <div class="legalpress-updater-card">
                <h2><?php esc_html_e('How to Create a New Release', 'legalpress'); ?></h2>
                <ol>
                    <li><?php esc_html_e('Update the version number in style.css', 'legalpress'); ?></li>
                    <li><?php esc_html_e('Commit and push all changes to GitHub', 'legalpress'); ?></li>
                    <li><?php esc_html_e('Go to your GitHub repository → Releases → Create a new release', 'legalpress'); ?></li>
                    <li><?php esc_html_e('Create a new tag (e.g., v2.6.0) matching your style.css version', 'legalpress'); ?></li>
                    <li><?php esc_html_e('Add release notes describing the changes', 'legalpress'); ?></li>
                    <li><?php esc_html_e('Publish the release', 'legalpress'); ?></li>
                    <li><?php esc_html_e('WordPress will automatically detect the new version!', 'legalpress'); ?></li>
                </ol>
            </div>
        </div>
        
        <style>
            .legalpress-updater-card {
                background: #fff;
                border: 1px solid #c3c4c7;
                border-radius: 4px;
                padding: 20px;
                margin: 20px 0;
                max-width: 800px;
            }
            .legalpress-updater-card h2 {
                margin-top: 0;
                padding-bottom: 10px;
                border-bottom: 1px solid #dcdcde;
            }
            .legalpress-changelog {
                background: #f6f7f7;
                padding: 15px;
                border-radius: 4px;
                max-height: 300px;
                overflow-y: auto;
            }
            .legalpress-changelog h3,
            .legalpress-changelog h4 {
                margin: 15px 0 10px 0;
            }
            .legalpress-changelog ul {
                margin: 10px 0 10px 20px;
            }
            .legalpress-changelog li {
                margin: 5px 0;
            }
            #legalpress-update-status {
                display: inline-block;
                vertical-align: middle;
            }
            .legalpress-updater-card ol {
                margin-left: 20px;
            }
            .legalpress-updater-card ol li {
                margin: 8px 0;
            }
        </style>
        
        <script>
        jQuery(document).ready(function($) {
            // Check for updates
            $('#legalpress-check-update').on('click', function() {
                var $btn = $(this);
                var $status = $('#legalpress-update-status');
                
                $btn.prop('disabled', true);
                $status.html('<span class="spinner is-active" style="float:none;"></span> <?php esc_html_e('Checking...', 'legalpress'); ?>');
                
                $.post(ajaxurl, {
                    action: 'legalpress_check_github_update',
                    nonce: '<?php echo wp_create_nonce('legalpress_github_update'); ?>'
                }, function(response) {
                    $btn.prop('disabled', false);
                    if (response.success) {
                        $status.html('<span style="color:#00a32a;">' + response.data.message + '</span>');
                        if (response.data.has_update) {
                            location.reload();
                        }
                    } else {
                        $status.html('<span style="color:#d63638;">' + response.data + '</span>');
                    }
                });
            });
            
            // Clear cache
            $('#legalpress-clear-cache').on('click', function() {
                var $btn = $(this);
                var $status = $('#legalpress-update-status');
                
                $btn.prop('disabled', true);
                $status.html('<span class="spinner is-active" style="float:none;"></span>');
                
                $.post(ajaxurl, {
                    action: 'legalpress_clear_update_cache',
                    nonce: '<?php echo wp_create_nonce('legalpress_github_update'); ?>'
                }, function(response) {
                    $btn.prop('disabled', false);
                    if (response.success) {
                        $status.html('<span style="color:#00a32a;"><?php esc_html_e('Cache cleared!', 'legalpress'); ?></span>');
                    }
                });
            });
        });
        </script>
        <?php
    }
    
    /**
     * AJAX: Check for update
     */
    public function ajax_check_update() {
        check_ajax_referer('legalpress_github_update', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Permission denied', 'legalpress'));
        }
        
        // Clear cache first
        delete_transient('legalpress_github_response');
        
        // Fetch fresh data
        $github_data = $this->get_github_data();
        
        if (!$github_data) {
            wp_send_json_error(__('Could not connect to GitHub', 'legalpress'));
        }
        
        $github_version = ltrim($github_data->tag_name, 'v');
        $has_update = version_compare($github_version, $this->current_version, '>');
        
        if ($has_update) {
            wp_send_json_success(array(
                'has_update' => true,
                'message' => sprintf(
                    /* translators: %s: New version number */
                    __('Update available: v%s', 'legalpress'),
                    $github_version
                ),
            ));
        } else {
            wp_send_json_success(array(
                'has_update' => false,
                'message' => __('You are running the latest version!', 'legalpress'),
            ));
        }
    }
    
    /**
     * AJAX: Clear cache
     */
    public function ajax_clear_cache() {
        check_ajax_referer('legalpress_github_update', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Permission denied', 'legalpress'));
        }
        
        delete_transient('legalpress_github_response');
        delete_site_transient('update_themes');
        
        wp_send_json_success();
    }
}

// Initialize the updater
function legalpress_github_updater_init() {
    new LegalPress_GitHub_Updater();
}
add_action('init', 'legalpress_github_updater_init');
