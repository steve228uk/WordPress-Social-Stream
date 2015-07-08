<?php

/**
 * Get the posts that make up the social stream
 * @return array
 */
function get_social_stream() {
    global $ss_stream;
    return $ss_stream->fetch();
}

/**
 * Render the social stream using the default template
 * @return void
 */
function the_social_stream() {
    $posts = get_social_stream();
    $theme_file = get_stylesheet_directory().'/social_stream.php';
    if(file_exists($theme_file)) {
        require_once $theme_file;
    } else {
        require_once __DIR__.'/views/social_stream.php';
    }
}

/**
 * Pull out the social stream via Ajax
 * @return void
 */
function ajax_social_stream() {
    require_once __DIR__.'/views/loader.php';
}

/**
 * Check if it's a Twitter post
 * @param  array  $post Post from the social stream
 * @return boolean
 */
function is_twitter_post($post) {
    return (isset($post['retweet_count']));
}

/**
 * Check if it's a Facebook post
 * @param  array  $post Post from the social stream
 * @return boolean
 */
function is_facebook_post($post) {
    return (isset($post['created_time']));
}

/**
 * Pull out an array of Twitter posts on their own
 * @param  int $number Number or twitter posts to pull out
 * @return array
 */
function twitter_posts($number) {
    global $ss_stream;
    $chunks = array_chunk($ss_stream->twitter->fetch()->posts, $number);
    return $chunks[0];
}

/**
 * Pull out an array of Facebook posts on their own
 * @param  int $number Number or posts posts to pull out
 * @return array
 */
function facebook_posts($number) {
    global $ss_stream;
    $chunks = array_chunk($ss_stream->facebook->fetch()->posts, $number);
    return $chunks[0];
}
