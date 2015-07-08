<?php

/**
 * Load the settings and generate a page for them
 */
class SS_Settings {

    /**
     * Options pulled from the WordPress database
     * @var array
     */
    private $options;

    /**
     * Let the magic begin
     */
    public function __construct() {
        $this->options = get_option('ss_options');
    }

    /**
     * Create the settings page
     * @return void
     */
    public function create_page() {
        add_action('admin_menu', array($this, 'create_settings_page'));
		add_action('admin_init', array($this, 'register_settings'));
    }

    /**
     * Register a new options page with WordPress
     * @return void
     */
    public function create_settings_page() {
        add_options_page('Social Stream', 'Social Stream', 'delete_others_posts', 'social_stream', array($this, 'load_settings_page'));
    }

    /**
     * Callback for the create_settings_page method
     * @return void
     */
    public function load_settings_page() {
        require_once __DIR__.'/../views/settings.php';
    }

    /**
     * Register the settings fields with WordPress
     * @return void
     */
    public function register_settings() {
        // Register the groups
        register_setting('ss_group', 'ss_options', array($this, 'sanitize'));
        add_settings_section('ss_facebook', 'Facebook', array($this, 'section_callback'), 'social_stream');
        add_settings_section('ss_twitter', 'Twitter', array($this, 'section_callback'), 'social_stream');
        add_settings_section('ss_posts', 'WordPress Posts', array($this, 'section_callback'), 'social_stream');

        // Add Facebook fields
        add_settings_field('client_id', 'Client ID', array($this, 'field_callback'), 'social_stream', 'ss_facebook', array('id' => 'client_id'));
        add_settings_field('client_secret', 'Client Secret', array($this, 'field_callback'), 'social_stream', 'ss_facebook', array('id' => 'client_secret'));
        add_settings_field('facebook', 'Facebook Page ID', array($this, 'field_callback'), 'social_stream', 'ss_facebook', array('id' => 'facebook'));

        // Add Twitter fields
        add_settings_field('consumer_key', 'Consumer Key', array($this, 'field_callback'), 'social_stream', 'ss_twitter', array('id' => 'consumer_key'));
        add_settings_field('consumer_secret', 'Consumer Secret', array($this, 'field_callback'), 'social_stream', 'ss_twitter', array('id' => 'consumer_secret'));
        add_settings_field('access_token', 'Access Token', array($this, 'field_callback'), 'social_stream', 'ss_twitter', array('id' => 'access_token'));
        add_settings_field('access_secret', 'Access Token Secret', array($this, 'field_callback'), 'social_stream', 'ss_twitter', array('id' => 'access_secret'));
        add_settings_field('username', 'Username', array($this, 'field_callback'), 'social_stream', 'ss_twitter', array('id' => 'username'));

        // Add Posts fields
        add_settings_field('posts_per_page', 'Posts Per Page', array($this, 'field_callback'), 'social_stream', 'ss_posts', array('id' => 'posts_per_page'));
    }

    /**
     * Callback for the Section to add a description
     * @return void
     */
    public function section_callback($args) {
        echo "Settings for your {$args['title']} page. Leaving these fields blank will exclude {$args['title']} from the social stream.";
    }
    /**
     * Render the field
     * @return void
     */
    public function field_callback($args) {
        printf('<input type="text" id="%s" name="ss_options[%s]" value="%s">', $args['id'], $args['id'], (isset($this->options[$args['id']])) ? esc_attr($this->options[$args['id']]) : '');
    }

    /**
	 * Sanitize the content input in the plugin
	 * @param  array $input Unsanitized content
	 * @return array Sanitized content
	 */
	public function sanitize($input) {
		return $input;
	}

    /**
     * Getter for the options array
     * @param  string $name What option to fetch
     * @return string
     */
    public function __get($name) {
        if(isset($this->options[$name])) {
            return $this->options[$name];
        }

        return null;
    }

}
