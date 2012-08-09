=== AdSense Manager ===
Contributors: mutube
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=martin%2efitzpatrick%40gmail%2ecom&item_name=Donation%20to%20mutube%2ecom&currency_code=USD&bn=PP%2dDonationsBF&charset=UTF%2d8
Tags: adsense, ad, link, referral, manage, widget, google, adbrite, cj, adpinion, shoppingads, ypn, widgetbucks
Requires at least: 3.0.0
Stable tag: 4.0.3

AdSense Manager lets you manage your ads from within WordPress. With support for AdSense, AdBrite and many more.

== Description ==

AdSense Manager was replaced by [Advertising Manager](http://wordpress.org/extend/plugins/advertising-manager/), however that is no longer developed. In response to a
number of queries I have updated my most recent version of the plugin to work with the latest WordPress and released it here. Unfortunately, if you upgraded to Advertising Manager
you may need to reimport your ads.

AdSense Manager supports most Ad networks including [AdBrite](http://www.adbrite.com/mb/landing_both.php?spid=51549&afb=120x60-1-blue), [AdRoll](http://www.adroll.com/tag/wordpress?r=ZPERWFQF25BGNG5EDWYBUV), [Project Wonderful](https://www.projectwonderful.com/?tag=12152) and plain old HTML code.

== Installation ==

1. Unzip the downloaded package and upload the Adsense Manager folder into your Wordpress plugins folder
1. Log into your WordPress admin panel
1. Go to Plugins and “Activate” the plugin
1. Previous installations will be updated and a notice displayed. If you have not used AdSense Manager before but have used AdSense Deluxe, you will be offered the change to import those ads.
1. “Adsense Manager” will now be displayed in your Settings section and “Ad Units” appears under Posts.
1. For first step instructions, go to Options &raquo; AdSense
1. Import, create and modifty your Ad blocks under Manage &raquo; Ad Units

== Frequently Asked Questions ==

= What is Be Nice? =

It was a way to support development of this plugin while it was actively being worked on. It is no longer included.

If you are able [please consider making a PayPal donation](https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=martin%2efitzpatrick%40gmail%2ecom&item_name=Donation%20to%20mutube%2ecom&currency_code=USD&bn=PP%2dDonationsBF&charset=UTF%2d8).

= Do I still need AdSense Manager now I can manage ads through Google's system? =

No, and Yes. While the original purpose of being able to modify colours etc. without digging into code is now gone (although still supported) there are other advantages to AdSense Manager. For example: positioning.  Additionally there are some plans afoot to provide intelligent ad placing methods to make all this work even better.

= How do I place Ad code at the top, bottom, left, right, etc. of the page? =

There is a (nice tutorial here)[http://www.tamba2.org.uk/wordpress/adsense/] which explains positioning using code in template files. You can use this together with AdSense Manager: just place the ad code tags <?php adsensem_ad(); ?> where it says "place code here". 

= Upgrading has gone a bit wrong... What can I do? =

To revert to an old copy of your Ad database, go to your Dashboard and add ?adsensem-revert-db=X to your URL. Replace X with the major version that you want to revert to.
 
If the latest version to work was 2.1, enter: ?adsensem-revert-db=2

Load the page and AdSense Manager will revert to that version of the database and re-attempt the upgrade.

= What else do you have planned? =

1. Ad Zones to allow grouping of ads at a particular location, and switching depending on the visitors language, country, etc.
1. Auto-inserting of ads into posts based on configurable rules (i.e. All Posts, 2nd Paragraph)
1. Localisation: multi-language support
1. Support for Amazon Affiliates and any other networks I hear about.

= Where can I get more information? =

[Complete usage instructions are available here.](http://www.mutube.com/mu/getting-started-with-adsense-manager-3x)

== Change Log ==

By popular demand, below are the changes for versions listed. Use this to determine whether it is worth upgrading and also to see when bugs you've reported have been fixed.

As a general rule the version X.Y.Z increments Z with bugfixes, Y with additional features, and X with major overhaul.

* **4.0** Well, well, well. Update to latest WordPress, bugfixes and simplifications. Transitional release to new backend/structure.
* **3.3** Final release cycle. Minor bugfixes, removal of Be Nice. Recommend you install Advertising Manager if able.
* **3.2.13** Fix for WordPress 2.3.3 compatibility.
* **3.2.11** Database/bugfixing code, only neccessary if you're experiencing errors.
* **3.2.10** Database/bugfixing code, only neccessary if you're experiencing errors.
* **3.2.9** Database/bugfixing code, only neccessary if you're experiencing errors.
* **3.2.8** Upgrade fixes, should fix ->network errors, see plugin homepage for instructions how to fix if you're stuck here.
* **3.2.7** Fixes to Javascript errors (minor, will not impact plugin function). Upgrade fix. Prevents error on 2.5>3.2
* **3.2.6** Default ad checking fix. Ads will continue to work even if default-ad not set. Fixed Javascript errors.
* **3.2.5** Fix to widgets to match updated WordPress code. May require replacement of widgets again. Fix to default ad selection, prevents errors in Widgets & ensures ads appear on site.
* **3.2.4** Bugfixes to upgrade path from 2.5, prevents requirement to open/save each ad unit. Account ID is now copied across correctly during upgrades.

