<?php

/**
 * The main class for Social Stream
 */
class SS_Stream {

    /**
     * Reference to SS_Settings
     * @var object
     */
    private $settings;

    /**
     * Reference to SS_Twitter
     * @var object
     */
    public $twitter;

    /**
     * Reference to SS_Facebook
     * @var object
     */
    public $facebook;

    /**
     * Reference to SS_Posts
     * @var object
     */
    public $posts;

    /**
     * Merged but unsorted posts
     * @var array
     */
    public $merged;

    /**
     * Initiate the social stream
     */
    public function __construct() {
        $this->settings = new SS_Settings();
        $this->settings->create_page();

        add_action('init', array($this, 'load_classes'));
        add_action('wp_ajax_load_ss_posts', array($this, 'load_ss_posts'));
        add_action('wp_ajax_nopriv_load_ss_posts', array($this, 'load_ss_posts'));
        add_action('wp_ajax_load_ss_template', array($this, 'load_ss_template'));
        add_action('wp_ajax_nopriv_load_ss_template', array($this, 'load_ss_template'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_shortcode('social_stream', array($this, 'process_shortcode'));
    }

    /**
     * Load the classes after wordpress has loaded
     * @return void
     */
    public function load_classes() {
        $this->facebook = new SS_Facebook();
        $this->twitter  = new SS_Twitter();
        $this->posts    = new SS_Posts();
    }

    /**
     * Fetch and mix all the posts
     * @return array The mixed and sorted posts
     */
    public function fetch() {
        $this->facebook->fetch();
        $this->twitter->fetch();
        $this->posts->fetch();

        return $this->merge()->sort()->merged;
    }

    /**
     * Merge posts together
     * @return self
     */
    private function merge() {
        $this->merged = array_merge($this->facebook->posts, $this->twitter->posts, $this->posts->posts);
        return $this;
    }

    /**
     * Sort posts
     * @return self
     */
    private function sort() {
        function stream_sorter($a, $b) {
            $timeA = (isset($a['created_at'])) ? strtotime($a['created_at']) : strtotime($a['created_time']);
    		$timeB = (isset($b['created_at'])) ? strtotime($b['created_at']) : strtotime($b['created_time']);
    		return ($timeA < $timeB);
        }

        usort($this->merged, 'stream_sorter');
        return $this;
    }

    /**
     * Load the Social Stream posts and return JSON
     * @return string JSON encoded posts
     */
    public function load_ss_posts() {
        echo json_encode($this->fetch());
        wp_die();
    }

    /**
     * Load the Social Stream template
     * @return string The default template
     */
    public function load_ss_template() {
        the_social_stream();
        wp_die();
    }

    /**
     * Load up the JS
     * @return void
     */
    public function enqueue_scripts() {
        wp_enqueue_script('social-stream', plugins_url('social-stream').'/js/social-stream.js', array('jquery'), '1.0.0', false);
    }

    /**
     * Load the social stream via a shortcode
     * @return void
     */
    public function process_shorcode() {
        ajax_social_stream();
    }

}
