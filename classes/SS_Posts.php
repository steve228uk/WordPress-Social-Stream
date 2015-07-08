<?php

/**
 * Class to fetch news posts
 */
class SS_Posts {

    /**
     * WordPress Posts
     * @var array
     */
    public $posts;

    /**
     * Reference to SS_Settings
     * @var object
     */
    private $settings;

    /**
     * Let the magic begin
     */
    public function __construct() {
        $this->settings = new SS_Settings();
    }

    /**
     * Pull posts from the database
     * @return self
     */
    public function fetch() {
        if(!$this->valid()) {
            $this->posts = array();
            return $this;
        }

        $args = array(
            'posts_per_page' => 15,
        );

        $this->posts = array();

        $q = new WP_Query($args);
        foreach($q->posts as $post) {
            $this->posts[] = array(
                'ID'         => $post->ID,
                'title'      => get_the_title($post->ID),
                'content'    => apply_filters('the_content', $post->post_content),
                'created_at' => get_the_time('U', $post->ID),
            );
        }

        return $this;
    }

    /**
     * Checks if the required settings have been set
     * @return self
     */
    private function valid() {
        return ($this->settings->posts_per_page);
    }

}
