=== Social Stream ===
Contributors: steve228uk
Donate link: http://stephenradford.me/wordpress-social-stream/
Tags: posts, social, facebook, twitter, feed
Requires at least: 3.0.1
Tested up to: 4.1.1
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Combine Twitter and Facebook feeds with WordPress posts to create one Social Stream

== Description ==

Social Stream takes your latest posts from Twitter, Facebook and your WordPress blog to merge them into a single feed. This can be displayed anywhere on your site with a shortcode or series of helper PHP functions.

== Installation ==

1. Upload the plugin folder to the `/wp-content/plugins/` directory
2. Activate the plugin in the WordPress dashboard
3. To enable Twitter, you need to create a new "App" at [https://developers.facebook.com/apps](https://apps.twitter.com)
4. Once you've create an app, opt to create an access token for your account
5. Copy the details into Settings -> Social Stream
6. To setup Facebook you need to create a new "App" at [https://developers.facebook.com/apps](https://developers.facebook.com/apps). Select "Website" and then "Skip and Create App ID".
7. Copy the App ID and App Secret into Settings -> Social Stream
8. Next, you need to find the ID of your Facebook page. If you're a page admin you can find this in your page settings. If not, you may be able to paste in the url at [Find my Facebook ID](http://findmyfacebookid.com)

== Frequently Asked Questions ==

= How do I disable Twitter, Facebook or WordPress posts from showing in Social Stream =

Easy! Just don't populate the relevant fields in the settings and it'll be excluded automatically.

= How do I display Social Stream on my website =

There are a few ways you can do this. The easiest way is through a shortcode:

`[social_stream]`

Place this anywhere you want the stream to be shown in your CMS and it'll pull it out using Ajax. If you prefer to pull things out in your theme then that's possible too:

* `<?php the_social_stream() ?>` - Will pull out the Social Stream in HTML.
* `<?php ajax_social_stream() ?>` - **Recommended**: This will use Ajax to display the social stream which is advantageous due to load speeds.
* `<?php get_social_stream() ?>` - Pulls out an array of all the posts so you choose how you want to display it.

= How can I override the default theme? =

Copy the file `views/social_stream.php` from the plugin to the root of your theme and style as desired.

= What if I just want to pull out Twitter or Facebook posts? =

There are a couple of functions that will allow you to pull out an array of Twitter or Facebook posts on their own.

* `<?php twitter_posts($numberOfPosts) ?>` - Will return an array of Twitter posts
* `<?php facebook_posts($numberOfPosts) ?>` - Will return an array of Facebook posts

= How do I determine what type of post this is? =

We've included a couple of functions that will return a true/false so you can easily work out if it's a Twitter or Facebok post.

* `is_twitter_post($post)` - Pass in a post to check
* `is_facebook_post($post)` - Pass in a post to check

= I want to parse links, @replies and hashtags, how can I do this? =

You don't need to roll your own regex as we've included a couple of static methods on the relevant classes.

`<?php SS_Twitter::parse($postText) ?>` - Will return the tweet with links, hashtags and @replies parsed
`<?php SS_Facebook::parse($postText) ?>` - Will return the post with links parsed

= Who made this? =

[Stephen Radford](http://stephenradford.me) ([@steve228uk](http://twitter.com/steve228uk)), a web developer at [Cocoon](http://wearecocoon.co.uk).

== Changelog ==

= 1.0 =
* We made it to 1.0!
