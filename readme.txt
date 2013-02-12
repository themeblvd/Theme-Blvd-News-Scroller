=== Theme Blvd News Scroller Widget ===
Contributors: themeblvd
Tags: themeblvd, slider, posts, scroll
Tested up to: 3.5
Stable tag: 1.0.9

This plugin is a simple widget with slider that rotates through posts of specified category.

== Description ==

This plugin is a simple widget with slider that rotates through posts of specified category. It incorporates the Flexslider plugin, which is the default responsive slider plugin already used within the Theme Blvd framework. 

== Installation ==

1. Upload `theme-blvd-news-scroller` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. Frontend view in sidebar.
2. Backend view in Appearance > Widgets after placing in sidebar.

== Changelog ==

= 1.0.9 =

* Fixed bug with incorrect date showing when using "fade" transition.

= 1.0.8 =

* Added check for featured image existing before trying to show it.
* Added legacy support for scroll direction in FlexSlider v1.x.

= 1.0.7 =

* Updated Flexslider fom v1.8 to v2.1.
* Added option for "Horizontal Slide" on transitions.
* The flexslider.js file is now only enqueued as needed.
* Added standard localization support.
* Added standard Theme Blvd plugin constants.

= 1.0.6 =

* While this update does not directly incorporate FlexSlider v2.0, it ensures compatibility with it and allows your theme to determine the version of FlexSlider used.

= 1.0.5 =

* Added wp reset query function after widget's posts in case widget is used before the primary WordPress loop.

= 1.0.4 =

* Removed "entry-title" class from titles of posts in widget. This should allow for some more consitant styling as the widget sits throughout your site on different pages. 

= 1.0.3 =

* Couldn't figure out weird bug with horizontal scrolling, so replaced with fade transition.
* Fixed scroll timeout issues to allow for 0 to turn off auto-rotation.
* Fixed category selection bug.

= 1.0.2 =

* Adjusted some stylings to work better with [Alyeska](http://themeforest.net/item/alyeska-responsive-wordpress-theme/164366?ref=themeblvd "Alyeska WordPress Theme")'s sidebars.

= 1.0.1 =

* Fixed bug with saving show/hide featured images.

= 1.0.0 =

* This is the first release.
