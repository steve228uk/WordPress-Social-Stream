<?php

/**
 * Class to fetch posts from Twitter
 */
class SS_Twitter {

    /**
     * Link to the cache file
     */
    const CACHE_FILE = '/../cache/twitter';

    /**
     * Posts fetched from Twitter
     * @var array
     */
    public $posts;

    /**
     * Reference to SS_Settings
     * @var object
     */
    private $settings;

    /**
     * Reference to TwitterOAuth
     * @var Object
     */
    private $connection;

    /**
     * Let the magic begin
     */
    public function __construct() {
        $this->settings = new SS_Settings();
        $this->load_oauth();
    }

    /**
     * Load the oauth class
     * @return self
     */
    private function load_oauth() {
        if (!class_exists('TwitterOAuth')) {
            require_once(__DIR__.'/../vendor/twitter_oauth/twitteroauth.php');
        }

        return $this;
    }

    /**
     * Fetch posts from WordPress
     * @return self
     */
    public function fetch($number = 25) {
        if(!$this->valid()) {
            $this->posts = array();
            return $this;
        }

        if(!$this->check_cache()) {

            $options = array(
                'screen_name' => $this->settings->username,
                'count' => $number,
            );

            $this->connection = new TwitterOAuth($this->settings->consumer_key, $this->settings->consumer_secret, $this->settings->access_token, $this->settings->access_secret);
            $result = $this->connection->get('statuses/user_timeline', $options);

            if(isset($result['errors'])) {
                $this->posts = array();
            } else {
                $this->posts = $result;
                $this->write_cache();
            }

        }

        return $this;
    }

    /**
     * Checks if the required settings have been set
     * @return self
     */
    private function valid() {
        return ($this->settings->consumer_key || $this->settings->consumer_secret || $this->settings->access_token || $this->settings->access_secret || $this->settings->username);
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
     * Parse the Twitter text with Regex to load links, @replies and hashtags
     * @param  string $content The original post content
     * @return string          The parsed post content
     */
    public static function parse($content) {
        $content = nl2br($content);
		$content = preg_replace('/(http:\/\/[a-z0-9\.\/]+)/i', '<a href="$1" target="_blank">$1</a>', $content);
		$content = preg_replace('/(https:\/\/[a-z0-9\.\/]+)/i', '<a href="$1" target="_blank">$1</a>', $content);
		$content = preg_replace('/( @|^@)(\w+)/', '<a rel="nofollow" href="http://www.twitter.com/$2" target="_blank" title="Follow $2 on Twitter">$1$2</a>', $content);
		$content = preg_replace('/( #|^#)(\w+)/', '<a rel="nofollow" href="https://twitter.com/#!/search?q=%23$2" target="_blank" title="$2">$1$2</a>', $content);

        return $content;
    }

}
