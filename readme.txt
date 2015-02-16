=== GC Testimonials to Testimonials by Axelerant ===

Contributors: comprock
Tags: gc testimonials, migration, convert, testimonials, testimonials widget
Requires at least: 3.6
Tested up to: 3.9.0
Stable tag: 1.3.0RC1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Migrate GC Testimonials entries to Testimonials by Axelerant custom post types.


== Description ==

Migrate GC Testimonials entries to Testimonials [by Axelerant](http://axelerant.com/about-axelerant/) custom post types for use by the best WordPress testimonials plugin there is, [Testimonials by Axelerant](http://wordpress.org/extend/plugins/testimonials-widget/).

= Primary Features =

* API
* Ajax based processing screen
* Migrates GC Testimonial fields, categories, and images to Testimonials by Axelerant format
* Settings export/import
* Settings screen

= Settings Options =

**Testing**

* Posts to Import - A CSV list of post ids to import, like '1,2,3'.
* Skip Importing Posts - A CSV list of post ids to not import, like '1,2,3'.
* Import Limit - Useful for testing import on a limited amount of posts. 0 or blank means unlimited.

**Compatibility & Reset**

* Export Settings – These are your current settings in a serialized format. Copy the contents to make a backup of your settings.
* Import Settings – Paste new serialized settings here to overwrite your current configuration.
* Remove Plugin Data on Deletion? - Delete all GC Testimonials to Testimonials data and options from database on plugin deletion
* Reset to Defaults? – Check this box to reset options to their defaults


== Installation ==

= Requirements =

* PHP 5.3+ [Read notice](https://nodedesk.zendesk.com/hc/en-us/articles/202331041)
* Plugin "[Testimonials by Axelerant](http://wordpress.org/plugins/testimonials-widget/)" is required to be installed and activated prior to activating "GC Testimonials to Testimonials".

= Install Methods =

* Through WordPress Admin > Plugins > Add New, Search for "GC Testimonials to Testimonials"
	* Find "GC Testimonials to Testimonials"
	* Click "Install Now" of "GC Testimonials to Testimonials"
* Download [`gc-testimonials-to-testimonials.zip`](http://downloads.wordpress.org/plugin/gc-testimonials-to-testimonials.zip) locally
	* Through WordPress Admin > Plugins > Add New
	* Click Upload
	* "Choose File" `gc-testimonials-to-testimonials.zip`
	* Click "Install Now"
* Download and unzip [`gc-testimonials-to-testimonials.zip`](http://downloads.wordpress.org/plugin/gc-testimonials-to-testimonials.zip) locally
	* Using FTP, upload directory `gc-testimonials-to-testimonials` to your website's `/wp-content/plugins/` directory

= Activation Options =

* Activate the "GC Testimonials to Testimonials" plugin after uploading
* Activate the "GC Testimonials to Testimonials" plugin through WordPress Admin > Plugins

= Usage =

1. Edit options through WordPress Admin > Testimonials > GCT Settings
1. Migrate GC Testimonials via WordPress Admin > Testimonials > GCT Migrator


= Upgrading =

* Through WordPress
	* Via WordPress Admin > Dashboard > Updates, click "Check Again"
	* Select plugins for update, click "Update Plugins"
* Using FTP
	* Download and unzip [`gc-testimonials-to-testimonials.zip`](http://downloads.wordpress.org/plugin/gc-testimonials-to-testimonials.zip) locally
	* Upload directory `gc-testimonials-to-testimonials` to your website's `/wp-content/plugins/` directory
	* Be sure to overwrite your existing `gc-testimonials-to-testimonials` folder contents


== Frequently Asked Questions ==

= Most Common Issues =

* Got `Parse error: syntax error, unexpected T_STATIC, expecting ')'`? Read [Most Axelerant Plugins Require PHP 5.3+](https://nodedesk.zendesk.com/hc/en-us/articles/202331041) for the fixes.
* [Debug common theme and plugin conflicts](https://nodedesk.zendesk.com/hc/en-us/articles/202330781)

= Still Stuck or Want Something Done? Get Support! =

1. [Testimonials Knowledge Base](https://aihrus.zendesk.com/categories/20104507) - read and comment upon 125+ frequently asked questions
1. [Open GC Testimonials to Testimonials Issues](https://github.com/michael-cannon/gc-testimonials-to-testimonials/issues) - review and submit bug reports and enhancement requests
1. [GC Testimonials to Testimonials Support on WordPress](http://wordpress.org/support/plugin/gc-testimonials-to-testimonials) - ask questions and review responses
1. [Contribute Code to GC Testimonials to Testimonials](https://github.com/michael-cannon/gc-testimonials-to-testimonials/blob/master/CONTRIBUTING.md)
1. [Beta Testers Needed](http://axelerant.com/become-beta-tester/) - get the latest GC Testimonials to Testimonials version


== Screenshots ==

1. GC Testimonials to Testimonials Settings
2. GC Testimonials to Testimonials Migrator

[gallery]


== Changelog ==

See [CHANGELOG](https://github.com/michael-cannon/gc-testimonials-to-testimonials/blob/master/CHANGELOG.md)


== Upgrade Notice ==

= 1.2.2 =

* Require Testimonials by Axelerant 2.19.0

= 1.2.1 =

* Require Testimonials by Axelerant 2.18.1

= 1.0.0 =

* Initial release


== Notes ==

TBD


== API ==

* Read the [GC Testimonials to Testimonials API](https://github.com/michael-cannon/gc-testimonials-to-testimonials/blob/master/API.md).

== Localization ==

You can translate this plugin into your own language if it's not done so already. The localization file `gc-testimonials-to-testimonials.pot` can be found in the `languages` folder of this plugin. After translation, please [send the localized file](http://axelerant.com/contact-axelerant/) for plugin inclusion.

**[How do I localize?](https://nodedesk.zendesk.com/hc/en-us/articles/202294892)**
