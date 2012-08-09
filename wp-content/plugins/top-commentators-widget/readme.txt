=== Top Commentators Widget ===
Contributors: webgrrrl
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=me%40webgrrrl%2enet&item_name=TCW%20Donation&no_shipping=1&tax=0&currency_code=USD&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8
Tags: comments, widget, seo, sidebar
Requires at least: 2.8
Tested up to: 3.1
Stable tag: 1.4.2

Adds a sidebar widget to show the top commentators in your WP site. Adapted from Show Top Commentators plugin at Personal Financial Advice.

== Description ==

The Top Commentators Widget a sidebar widget to show the top commentators in your WP site. Adapted from Show Top Commentators plugin at Personal Financial Advice, this widget is easier to manage via the control form (no need to edit the PHP file); additional options are also available to make it more flexible. Read the FAQ section on how to customize the widget. Read the Changelog as well as http://webgrrrl.net/tags/tcw for the latest news on this widget.

== Installation ==

= (WordPress 2.5 and above) = 

1. Unzip to get the topcommentators_widget.php file.
2. Upload the file into the wp-content/plugins folder.
3. In your WP admin console, go to Plugins and activate Top Commentators Widget.
4. Drag the Top Commentators to wherever you want it to be, and click the Save Changes button if you want to stick to the default values.
5. You're done!

= (WordPress 2.3.3 and lower) = 

1. Unzip to get the topcommentators_widget.php file.
2. Upload the file into the wp-content/plugins folder.
3. In your WP admin console, go to Plugins and activate Top Commentators Widget.
4. In your WP admin console, go to Presentation | Widgets (for WordPress 2.2 and later) or Presentation | Sidebar Widgets (for older WordPress versions), and drag the Top Commentators widget to wherever you want it to be, and click the Save Changes button.
5. Nah, that's it! Remember to read the FAQ to see what options you can play around in the Top Commentators Widget.

== Changelog ==

= v.1.4.2 =
1. Further improved query against URL hijacking (searching based on e-mail instead of username).

= v.1.4.1 beta =
1. MAJOR CODE REWRITE! This may break any customized CSS you may have on TCW.
2. Support for multiple instances of the widget. This means that you can have TCW on as many sidebars in your blog as you want, each with its own unique settings.

= v.1.4 =
1. Added the Award option which will display image or icon of a medal/badge once a commentator reaches a certain number of comments determined by blog owner.
2. Modified the form to allow default values to be entered automatically when initialized (i.e. widget's Save button is pressed).
3. Added support for setting default Gravatars to 404, Mystery Man, Identicons, MonsterIDs or Wavatars.
4. Repaired the query statement for filtering e-mails.

= v.1.3 =
1. Rectified the SQL query for filtering list by hour.

= v.1.2 =
1. Added target=_blank option which will open links in a new browser window.
2. Added filtering based on range of dates.

= v.1.1 =
1. Fixed the Year value error which prevented it from being highlighted when selected and saved in the widget control form. (noted by Sebastien, http://wordpress.org/support/topic/205463?replies=2#post-876591)
2. Added Gravatar support (thanks to the codes by Sergio Nascimento, http://www.coisasdosanduba.com/).
3. Added Display only commentors with URLs.
4. Fixed the bug which displays the URL of a comment marked as spam, but having the name of a valid commentor (since TCW always selects the latest URL entered by the same commentor user name).

= v.1.0 =

1. Corrected the extra closing widget tag that appears when Show In Home Only is selected.

= v.0.999a =

1. Re-arranged functions to avoid redeclare error in WP 2.5.
2. Modified widget control form to repair unclickable control form.
3. Added NoFollow option to links.
4. Added show comment count option.
5. Fixed the reset period (not tested extensively).
6. Fixed the URL filtering.
7. Include option to list users by e-mail or user names.
8. Include option to filter users by e-mail.

== Buglist ==

= For those using the v.0.999a widget in WordPress 2.3.3 or lower =

1. The widget control form may not appear so pretty; the blue control form background does not run to the end of the form.
2. Filtering by e-mail does not seem to work in WP 2.3.3 using full or partial address.

This widget is extensively tested with the following settings: Google Chrome 9.0.597.98, PHP 5.3.0, Apache 2.2.12 (Win32), MySQL 5.1.37, WordPress 3.0.5. Further testing and bug report on this widget is greatly welcomed and appreciated.

== Frequently Asked Questions ==

= How do I use this in my blog? =

Obviously, you must be using WordPress on your site. Your theme must also support widgets (if unsure, check with the person who designed the theme). If it doesn't support widget, please use the <a href="http://www.pfadvice.com/wordpress-plugins/show-top-commentators/">Show Top Commentators plugin</a> instead; it should work similarly to this widget.

= What options do I need to set in this widget? =

None. Just follow the installation steps and and it is ready to run. Customization is not necessary if you don't want to bother doing so.

= What are the options that I can change in this widget? =
Good Lord, a lot! Ensure that you first follow the Installation instructions to activate the widget. Once you have the Top Commentators widget on the sidebar, click the control icon of this widget to change the following:

1. **Change widget title**: Change the standard title (Top Commentators) to any snazzy title, like Bloggers of the Month or Commentors Who Owe Me a Million Bucks.
2. **Add description below the title**: You can add an extra description if you want to. NOTE: Certain WP themes may not display this correctly.
3. **Exclude these users**: Exclude commentators based on their names, like Administrator or yourself. Don't enter any email filters here; they belong in the Filter full/partial emails field.
4. **Reset list every period**: Reset the list to generate commentors hourly, daily, weekly, monthly, yearly, or all-time. You can also reset the list within a certain number of days, like every 15 days or 66 days and so forth. As of version 1.2, you can specify a specific range of date; for example, to display top commentators between January 1, 2009 and March 31, 2009, you should type 20090101 and 20090331.
5. **Limit the number of names listed**: If you want to list the top 20 commentators, type in 20.
6. **Limit the number of characters in each name**: Useful if you want to control the list from breaking your sidebar design. Names that have characters longer than your setting will have a trailing ellipses. If you want the names to be longer, change to a higher value such as 35. Otherwise, if, say, you change it to 3, then Lorna will become Lor... .
7. **Add remarks for empty list**: Display some notice to appear when your top commentator list is empty, like "Be the first person to comment".
8. **Filter full/partial URLs**: Works just like Exclude Users, except this will filter by URLs.
9. **Filter full/partial e-mails**: Works just like Exclude Users, except this will filter by e-mail address. NOTE: This may not work with WP 2.3.3 and lower and currently in the buglist.
10. **Display list type as bulleted or numbered list**: That's pretty much straight-forward.
11. **Hyperlink names**: Choose whether you want the commentors' names to be linked to their URLs or not.
12. **Open each link in a new window**: Choose whether you want the links to open a new window (target=_blank).
13. **NoFollow links**: In case you want to display their URLs but make it NoFollow to stop Google juice.
14. **Show number of comments made**: Like it says.
15. **Group commentors based on e-mail or user names**: This option is added to solve the hijacking problem various blogs have been reporting if commentors are grouped by user names.
16. **Show in home page only**: If you select Yes, then the Top Commentators list will only appear in your main page; otherwise, the list will appear in all pages that have your sidebar displayed.
17. **Display only commentators with URL**: If you select Yes, then the Top Commentators list filter out commentators who did not leave any web site URL in their latest comment.
18. **Display Gravatar**: If you select Yes, then a Gravatar will appear on the left side of each commentators name in the Top Commentators list.
19. **Gravatar type and size**: If you select Yes in Display Gravatar, the images will appear based on the type and size you specify here.
20. **Show an Award icon if comments reach a certain range**: If the number of comments is equal or greater than the number specified here, then an award icon will appear. No icon will appear if this number is set to zero (0).
21. **Align Award icon**: Set where you want the award icon to appear.

= The widget screwed with my sidebar design!! WTF?? =
Firstly, take a deep breath and calm down.

OK now? Great.

When this happens, it could verily mean that you will need to tweak your CSS file of the WordPress theme you are using. Not only will you need to look into formatting the UL, OL and LI of your sidebar, you also need to consider the use of A in the existence -- or absence -- of hyperlinked commentators' names. The easiest way to do this is:
- Identify the CSS elements that exist around and within the Top Commentators Widget. You can identify the tags surrounding the TC Widget with the words "top-commentators" or "widget widget_topcomm".
- You may or may not want to add a new CSS element specifically for the TC Widget. Get someone you know who's good with CSS if you're not comfortable doing this yourself.

By the way, if you want to tinker with the style for this widget's Gravatar, just add a class called tcwGravatar to your style.css file and add any CSS element to it. For the Award icon, look for the tcwAward class.

== Screenshots ==

1. Top Commentators Widget v.1.0 and v.0.999a control form snapshot in WordPress 2.5.1.
2. Top Commentators Widget v.0.999 control form snapshot in WordPress 2.3.3.
3. Top Commentators Widget v.1.4 control form snapshot in WordPress 3.0.5.

<?php code(); // goes in backticks ?>