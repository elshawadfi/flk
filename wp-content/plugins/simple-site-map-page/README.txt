=== Simple Site Map Page ===
Contributors: audrasjb,whodunitagency
Donate link: https://www.paypal.me/audrasjb
Tags: site map, site map page, html site map, plan du site, plan de site, html, map, site, sitemap, menu
Requires at least: 5.3
Tested up to: 5.7
Requires PHP: 5.6
Stable tag: 1.2.1
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Build your HTML site map page easily and manually with WordPress native menus.

== Description ==

Simple Site Map Page can be used to build a HTML site map page easily and manually. 

It uses WordPress native menus manager so you can customize your site map yourself, and it’s translation ready (Polylang, WPML and others).

Native WP menu manager is good to choose exactly what must be displayed or not in the site map, e.g if you want to prevent visitors to access private parts of your website.

If you use custom links with `#` target, ce plugin will automatically remove the link and replace it with a `<span>` element. It can be usefull to create unlinked virtual pages to structure your sitemap.

**Credits** : 

*   [Jean-Baptiste Audras](http://jeanbaptisteaudras.com/ "Jean-Baptiste Audras"), project manager at [Whodunit](http://www.whodunit.fr/ "Whodunit WordPress Agency") and WordPress developer. @audrasjb on [Twitter](https://twitter.com/audrasjb "@audrasjb on Twitter") or [Github](https://github.com/audrasjb "@audrasjb on Github").

Add your own language pack on GlotPress or do pull requests on [Github](https://github.com/audrasjb "Plugin repo on Github"). Contributors will be credited here :)

== Installation ==

1. Install the plugin and activate.
2. Go to Reading options to select the sitemap page.
3. Go to Appearance &gt; Menus to build your site map and register it under "Site map" location.
4. Done! The site map will be displayed under the page main content.

== Frequently Asked Questions ==

= My site map doesn’t appear on the selected page :( =

After selecting your site map page in reading options, you have to build a new menu in Appearance &gt; Menus and register it under the "Site map" location. See the screenshots provided for visual indications.

= How to setup virtual pages without link? =

Use custom link and the target / URL `#`. It will be automatically converted into simple `<span>` element instead of `<a href="#">`.

= Can I use custom CSS styles? =
Sure! The site map list markup is wrapped with `<div class="menu-site-map-menu-container"><ul id="menu-site-map-menu" class="ssmp simple-site-map"> … </ul></div>` so you can customize it quite easily.

== Screenshots ==

1. Choose a page under Settings &gt; Readings options.
2. Build your site map under Appearance &gt; Menus and register it under "Site map" location.
3. Your site map appears under the page's main content.

== Changelog ==

= 1.2.1 =
- Small fix on Polylang compatibility. Props @bastienmartinent.

= 1.2 =
- Polylang compatibility

= 1.1.1 =
- Gutenberg compatibility

= 1.1 =
* Internationalization bug fix.

= 1.0 =
* Plugin initial commit. Works fine :)