=== GC Testimonials to Testimonials ===

Contributors: comprock
Donate link: http://aihr.us/about-aihrus/donate/
Tags: gc testimonials, migration, convert, testimonials, testimonials widget
Requires at least: 3.6
Tested up to: 3.8.0
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Migrate GC Testimonials entries to Testimonials custom post types.


== Description ==

Migrate GC Testimonials entries to Testimonials custom post types for use by the best WordPress testimonials plugin there is, [Testimonials](http://wordpress.org/extend/plugins/testimonials-widget/).

= Primary Features =

* API
* Ajax based processing screen
* Migrates GC Testimonial fields, categories, and images to Testimonials format
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

= API =

* Read the [GC Testimonials to Testimonials API](https://github.com/michael-cannon/gc-testimonials-to-testimonials/blob/master/API.md).

= Languages =

You can translate this plugin into your own language if it's not done so already. The localization file `gc-testimonials-to-testimonials.pot` can be found in the `languages` folder of this plugin. After translation, please [send the localized file](http://aihr.us/contact-aihrus/) to the plugin author.

See the FAQ for further localization tips.

= Support =

Please visit the [GC Testimonials to Testimonials Knowledge Base](https://aihrus.zendesk.com/categories/20104507-Testimonials-Widget) for frequently asked questions, offering ideas, or getting support.

If you want to contribute and I hope you do, visit the [GC Testimonials to Testimonials Github repository](https://github.com/michael-cannon/gc-testimonials-to-testimonials).


== Installation ==

1. Via WordPress Admin > Plugins > Add New, Upload the `gc-testimonials-to-testimonials.zip` file
1. Alternately, unzip `gc-testimonials-to-testimonials.zip` the file and then via FTP, upload `gc-testimonials-to-testimonials` directory to the `/wp-content/plugins/` directory
1. Activate the 'GC Testimonials to Testimonials' plugin after uploading or through WP Admin > Plugins
1. Edit options via WP Admin > Testimonials > GCT Settings
1. Import via WP Admin > Testimonials > GCT Migrator


== Frequently Asked Questions ==

Please visit the [GC Testimonials to Testimonials Knowledge Base](https://aihrus.zendesk.com/categories/20104507-Testimonials-Widget) for frequently asked questions, offering ideas, or getting support.


== Screenshots ==

1. TBD

[gallery]


== Changelog ==

See [CHANGELOG](https://github.com/michael-cannon/gc-testimonials-to-testimonials/blob/master/CHANGELOG.md)


== Upgrade Notice ==

= 1.0.0 =

* Initial release


== TODO ==

See [TODO](https://github.com/michael-cannon/gc-testimonials-to-testimonials/blob/master/TODO.md)
