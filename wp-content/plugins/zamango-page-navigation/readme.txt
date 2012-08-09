=== Zamango Page Navigation ===
Contributors: Zamango
Donate link: http://www.zamango.com/
Tags: navigation, page bar, paged, navigation, page list, navigation bar, next post, previous post, next page, previous page, quick link, page navi
Requires at least: 2.8.0
Tested up to: 2.9.2
Version: 1.3
Stable tag: 1.3

It creates  pagebar on lists (for ex. on category or search results) and Next Post & Previous Post links on each post.

== Description ==

Zamango Page Navigation creates pagebar on lists (for ex. on category or search results) and Next Post & Previous Post links on each post.

[Zamango Page Navigation](http://www.zamango.com/ "Zamango blog") plugin inserts customizable pagebars into your Wordpress, allowing reader to reach page # X in one click. Also it features quick links on single post (to previous and next posts).

Main features of [Zamango Page Navigation](http://www.zamango.com/ "Zamango developer's blog"):

* Pagebar of quick links on a lists (like index, category, tag or archive and etc.)
* Pagebar of links to next and previous posts
* Independently show/hide pagebar in the beginning and end of page
* Highly customizable appearance (style and content) by using of simple HTML and CSS
* Independent amount of links in left, central and right parts of pagebar
* Other options which make plugin's behaviour very customizable for any website needs

The main reason to develop the plugin was absence of any plugin of such functionality and stable on modern Wordpress builds, so we was obliged to make it for storefronts build by [Zamango Money Extractor plugin](http://wordpress.org/extend/plugins/zamango-money-extractor/). But we hope that Zamango Page Navigation will be helpful to other Wordpress users.

Classical Wordpress theme contains quick links on a post page but due to creator's joke or mistake quick links of all posts does not make a linear scheme. For example if you click on "next" link and then to "previous link" it's highly possible that you will not return to original post. Such small thing can break internal website linking and pagerank allocation.


== Installation ==

Installation procedure is the same as most other plugins have:

1. Extract the `zamango-page-navigation.zip` to your hard drive
1. Upload `zamango-page-navigation` folder into `/wp-content/plugins/` directory of your website
1. Activate `Zamango Page Navigation` plugin on `Plugins` page of your wordpress admin panel


== Frequently Asked Questions ==

= Is it obligatory to use v.2.8+ of Wordpress? Will pluging work on older wordpress builds? =

Zamango Page Navigation uses few features which were implemented in WordPress v.2.8.0 so it will not work correctly on older versions.
Althought I use it myself on wordpress 2.7.4 with few limitaions.

= What if I don't want to use quick links on post page? =

Just uncheck both "before post" & "after post" checkboxes on a `Quick links on single post` tab of plugin options page.

= I'm using Wordpress theme already had quick links and they confuse visitors =

To make native theme navigation bar absolutely invisible just add following string into plugin's CSS (without apostrophes):
`.navigation {display:none;}`
It must help in most themes. If not then it's needed to edit your theme files (but do it on your own responsibility).

== Screenshots ==

1. Page bar customization for lists (index, search results, category, archive or tags browsing)
2. Page bar customization for single posts
3. You may customize CSS right from Wordpress admin panel

== Changelog ==

= 1.3 =
* Bugfix: fix multiple pager rendering in case of many loops per page

= 1.2 =
* Bugfix: fixed rss feed

= 1.1 =
* Bugfix: fix wrong function pointer definition

= 1.0 =
* Changes: code rewritten from scratch and optimized, reduced CSS and JS files includes, activation and deactivation hooks are binded only to /wp-admin/plugins.php (increases WP performance), human readable plugin URL in admin page, new admin UI (suitable for WP themes).
* Bugfix: fix all warnings

= 0.2.1 =
* Changes: improved look & feel.

= 0.2 =
* Changes: improved look & feel.

= 0.1 =
* First public build.

== Upgrade Notice ==

= 1.0 =
Don't update Zamango Page Navigation if you have Zamango Money Extractor plugin version lower than 1.0 installed
