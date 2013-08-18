=== MachForm Shortcode ===
Contributors: rlaymance
Donate Link: http://forms.laymance.com/view.php?id=16963
Tags: MachForm, forms, shortcode, AppNitro
Requires at least: 3.0
Tested up to: 3.6
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily use MachForm forms on your WordPress site!

== Description ==

[MachForm](http://appnitro.com/) is an excellent, easy to use form builder that you host on your own server or site. Until now, its been difficult and required "jumping through some hoops" to embed a form made with MachForm on your WordPress site. That is no more! You can now add a MachForm form anywhere on your site using a simple shortcode! Need a form in a blog post? Need a form on a page? No problem.

For more information, check out the [plugin page on our website](http://www.laymance.com/products/wordpress-plugins/machform-shortcode/).

Features include:
* Support for javascript based forms
* Support for iframe based forms


**How to use the shortcode:**
1. Click the "Code" option on your form inside of MachForm to see the embed codes.
2. Make note of your form's "ID" and the "height".
3. Use the shortcode to embed your form into your content using this format:  [machform type=("js" or "iframe") id=(ID #) height=(height #)]
4. You are done, your form should show in your content now!

== Installation ==

1. Install MachForm Shortcode either via the WordPress.org plugin directory, or by uploading the files to your server
2. After activating MachForm Shortcode, navigate to the "Machform Shortcode" menu link under "Settings" in your WordPress admin.
3. Supply the URL/location of your MachForm installation and click "Save Configuration".
4. You're done! Use the shortcode to add forms to your site!

== Frequently Asked Questions ==

= What version of MachForm does this plugin work with? =

We have only tested the plugin with version 3.5 of MachForm.  If you have an older version, and the plugin does not work with it, please contact us and we'll add support for it (we would need a sample form and a copy of what the embed string looks like)!  You can contact us by visiting our website at [www.laymance.com](http://www.laymance.com).

= I've found a bug, what can I do? =

Please let us know and we'll get it fixed right away. Contact us via our website at [www.laymance.com](http://www.laymance.com).

= What is the shortcode format? =

Here is how to use the shortcode:

[machform type=("js" or "iframe") id=(ID #) height=(height #)]

If the "type" is not given, it will default to the javascript ("js") method.

If the "height" is not given, it will default to a height of 800 pixels. But please give the height to ensure that your form appears correctly!

The ID is a REQUIRED field.

== Screenshots ==

1. Menu location for configuration.
2. Configuration screen.
3. MachForm embed example, where to get the ID and height.
4. Sample usage in a new post.

== Changelog ==

= 1.0 =
* Initial release