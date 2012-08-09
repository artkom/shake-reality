=== Plugin Name ===
Contributors: borkweb
Donate link: http://borkweb.com/
Tags: comments, avatars, formatting, ajax
Requires at least: 2.1
Tested up to: 2.2
Stable tag: 1.4.6

Sexify your comments with avatars and forum-like formatting and Ajax helper features.  

== Description ==

Plugin by: Matthew Batchelder at [BorkWeb](http://borkweb.com/ "Author Homepage")

Replacing the comment section of your template, this plugin outputs forum style comments optionally with avatars.  The latest update has brought Ajax helper features! The full feature list is as follows:

* Ajax comment preview (new feature)
* Author post highlighting
* Avatars
      * Either display/hide avatars
      * Select your avatar service of choice (Gravatar and MyBlogLog options are available)
      * Specify maximum avatar dimension (Gravatar Only)
      * Customize default/trackback avatars
* Comment Reply-To (new feature)
* Comment Themes (new feature)
* CSS overriding
* "Number of Comments" message customization
* jQuery inclusion toggling (new feature)

== Installation ==

1. Download Sexy Comments v1.4 from the WordPress plugin directory
2. Unzip that little sucker
3. Place sexy-comments folder in your wp-content/plugins directory (it should look like this: wp-content/plugins/sexy-comments/
4. Log in to your WordPress plugin admin page and activate the plugin.
5. In the plugin admin page, click the SexyComments sub-menu.
6. Customize the settings until you have something that works for you.
7. Locate your theme's template file that displays comments (typically comments.php). Remove the comment output loop and replace with:

					<?php sexycomments::show($comments); ?>

8.If you plan to use the Ajax features or the Reply-To features, you will need to do two things.
   1. Enable jQuery and jQuery Form Extension via the Plugin > SexyComments administration page.
   2. Locate the template file that contains the comment submission form (typically comments.php near the bottom) and replace that chunk of code with:

					<?php sexycomments::form(); ?>

		NOTE: Be sure not to touch the section that generates the form for adding comments! This plugin does not re-create the comment creation form.
 9. Lastly, consider disabling the plugin CSS and taking the example CSS provided and customize it to suit your theme's color scheme.
 10. You should be all set, now! w00t w00t! Go make a MyBlogLog or Gravatar account if you don't already have one and upload an avatar. Gravatar tends to be pretty flakey so I'd suggest using MyBlogLog.


== Frequently Asked Questions ==

= When I'm replacing my comment section with your plugin, should I back up my comments template? =

Yes.  Backing up your template file is always a good plan.

= What is this "comment loop" you speak of? =

Ah, yes. That thing. Well, its anatomy looks similar to this (there will be some variation from theme to theme): 

= Ok...so I just upgraded to a new version and there is nothing in the SexyComments admin page...WTF? =

Yeah. Sorry about that. In this version, the directory structure has changed drastically and Sexy Comments should no longer live in wp-content/plugins/sexycomments.php OR wp-content/plugins/sexycomments/sexycomments.php, but instead it should be in wp-content/plugins/sexy-comments/. Make sure that the plugin is in the correct location of your plugins directory.

= What happened to sexycomments_print($comments)? I used to use that to get my comments to display...will it still work? =

Along with a directory structure overhaul, this version had a large code overhaul as well. The old function (sexycomments_print) is deprecated but will still work for the time being. I greatly urge you to move over to the new function call sexycomments::show($comments) as that is the new *impoved* function.

= Are you a wooden hippo? =

No.  But I do like to eat peanut butter and jelly sandwiches.


== Screenshots ==

1. [Screenshot 1](http://www.flickr.com/photos/borkweb/448918442/)

== Arbitrary section ==

Special thanks to Cliff at [Spiralbound](http://spiralbound.net) for being my beta testing slave.
