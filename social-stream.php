<?php
/*
Plugin Name: Social Stream
Description: Pull your Twitter, Facebook and WordPress posts into one neat section
Version: 1.0
License: MIT
License URI: http://opensource.org/licenses/MIT
Author: Cocoon Development Ltd
Author URI: http://wearecocoon.co.uk
*/


/**
 * Require the necessary classes
 */

require_once __DIR__.'/classes/SS_Facebook.php';
require_once __DIR__.'/classes/SS_Posts.php';
require_once __DIR__.'/classes/SS_Settings.php';
require_once __DIR__.'/classes/SS_Stream.php';
require_once __DIR__.'/classes/SS_Twitter.php';

/**
 * Initiate classes
 */

global $ss_stream;
$ss_stream = new SS_Stream();

/**
 * Include the function that determine where the social stream can be pulled out
 */

require_once __DIR__.'/functions.php';
