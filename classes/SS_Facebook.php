<?php

/**
 * Class to fetch posts from Facebook
 */
class SS_Facebook {

    /**
     * Link to the cache file
     */
    const CACHE_FILE = '/../cache/facebook';

    /**
     * The posts fetched from Facebook
     * @var array
     */
    public $posts;

    /**
     * Access token from Facebook
     * @var string
     */
    private $token;

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
     * Fetch Facebook posts
     * @return self
     */
    public function fetch() {
        if(!$this->valid()) {
            $this->posts = array();
            return $this;
        }

        if(!$this->check_cache()) {
            $this->get_token()->get_posts();
        }

        return $this;
    }

    /**
     * Check all fields are set in the settings class
     * @return bool
     */
    private function valid() {
        return ($this->settings->facebook && $this->settings->client_id && $this->settings->client_secret);
    }

    /**
     * Get the token from Facebook
     * @return self
     */
    private function get_token() {
        $this->token = file_get_contents('https://graph.facebook.com/oauth/access_token?client_id='.$this->settings->client_id.'&client_secret='.$this->settings->client_secret.'&grant_type=client_credentials&limit=1');
        return $this;
    }

    /**
     * Get the posts from Facebook
     * @return self
     */
    private function get_posts() {
        $feed = json_decode(file_get_contents('https://graph.facebook.com/'.$this->settings->facebook.'/posts?'.$this->token), true);
		$this->posts = $feed['data'];
        $this->write_cache();
        return $this;
    }

    /**
     * Check if the cache date is less than now and if so load the posts from there
     * @return bool
     */
    private function check_cache() {
        $cache = json_decode(file_get_contents(__DIR__.static::CACHE_FILE), true);
        if(isset($cache['expires']) && $cache['expires'] > time()) {
            $this->posts = $cache['posts'];
            return true;
        }
        return false;
    }

    /**
     * Write to the cache
     * @param  array $posts Posts to cache
     * @return void
     */
    private function write_cache() {
        $data = array(
            'expires' => strtotime('+1 hours'),
            'posts'   => $this->posts,
        );

        file_put_contents(__DIR__.static::CACHE_FILE, json_encode($data));
    }

    /**
     * Parse the Facebook text with Regex to load links
     * @param  string $content The original post content
     * @return string          The parsed post content
     */
    public static function parse($content) {
        $content = nl2br($content);
		$content = preg_replace('/(http:\/\/[a-z0-9\.\/]+)/i', '<a href="$1" target="_blank">$1</a>', $content);
		$content = preg_replace('/(https:\/\/[a-z0-9\.\/]+)/i', '<a href="$1" target="_blank">$1</a>', $content);

        return $content;
    }

}
