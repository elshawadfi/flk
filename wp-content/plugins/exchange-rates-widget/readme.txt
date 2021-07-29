=== Exchange Rates Widget ===
Contributors: falselight
Tags: exchange rates, currency exchange, foreign fx, currency, euro, dollar, bitcoin, currencies, currency, widgets
Donate link: http://currencyrate.today/exchangerates-widget
Tested up to: 5.7
Requires at least: 3.1
Requires PHP: 5.3
Stable tag: 1.2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Simple and powerful currency exchange rates widget for your website or blog. Included 195+ world currencies with popular cryptocurrencies. Updates each hour automatically. Multi Language support: English, Русский, Italiano, Français, Español, Deutsch, 中国, Português, 日本語, Bahasa Indonesia, हिन्दी.

== Description ==
Simple and powerful currency exchange rates widget for your website or blog. Included 195+ world currencies with popular cryptocurrencies. Updates each hour automatically. Multi Language support: English, Русский, Italiano, Français, Español, Deutsch, 中国, Português, 日本語, Bahasa Indonesia, हिन्दी.

= Features =
1. 195+ currencies and popular cryptocurrencies;
2. You can use the plugin on any page using shortcode;
3. Does not create a load on the site, all data is processed on a third-party server;
4. Updates automatically;
5. Multi languages and SSL support;
6. Responsive design.

== Installation ==

= From your WordPress dashboard =
1. Visit 'Plugins > Add New'
2. Search for 'Exchange Rates Widget'
3. Activate Exchange Rates Widget from your Plugins page.
4. Add widgets on yourdomain.com/wp-admin/widgets.php page.

= From WordPress.org =
1. Download Exchange Rates Widget.
2. Upload the 'Exchange Rates Widget' directory to your '/wp-content/plugins/' directory, using your favorite method (ftp, sftp, scp, etc...).
3. Activate Exchange Rates Widget from your Plugins page.
4. Add widgets on yourdomain.com/wp-admin/widgets.php page.

= From WordPress 'Add Plugins' =
1. Download Exchange Rates Widget.
2. Go to yourdomain.com/wp-admin/plugin-install.php
3. Press button 'Upload Plugin'
3. Choise 'Exchange Rates Widget' zip archive and press button 'Install Now'.
4. Add widgets on yourdomain.com/wp-admin/widgets.php page.

== Frequently asked questions ==
= How to install a widget on an arbitrary page? =
1. You can generate a shortcode manual
Example:
[erw_exchange_rates_widget lg="ru" tz="0" fm="EUR" to="USD" st="info" bg="FFFFFF" lr="1" rd="0"][/erw_exchange_rates_widget]
Params:
lg="ru" - languages, use: en, ru, it, fr, es, de, cn, pt, ja, id, hi
tz="0" - timezone
fm="EUR" - currency code from (list of currency codes: http://currencyrate.today/different-currencies)
to="AUD,GBP,EUR,CNY,JPY,RUB" - currency codes separated by commas (list of currency codes: http://currencyrate.today/different-currencies)
st="info" - theme (color scheme) primary, info, danger, warning, gray, success (used bootstrap3 color classes)
cd="1" - 0 only currencu code, 1 - full currency name
am="100" - amount of the exchange rate
2. You can generate a shortcode automatic
a. Go to yourdomain.com/wp-admin/widgets.php page;
b. Add widget "Exchange Rates Widget";
c. Select options, click save;
d. Copy shortcode from textarea.
3. Languages
en - English
ru - Русский
it - Italiano
fr - Français
es - Español
de - Deutsch
cn - 中国
pt - Português
ja - 日本語
id - Bahasa Indonesia
hi - हिन्दी

== Screenshots ==
1. screanshot-1.jpg - Widget settings
2. screanshot-2.jpg - How it looks on the website
3. screanshot-3.jpg - Dark blue theme
4. screanshot-4.jpg - Gray theme
5. screanshot-5.jpg - Green theme
6. screanshot-6.jpg - Yellow theme
7. screanshot-7.jpg - Red theme
8. screanshot-8.jpg - Blue theme

== Changelog ==
= 1.2.0 =
* Add new languages: Português, 日本語, Bahasa Indonesia, हिन्दी
= 1.1.2 =
* Minor bug fixes
= 1.1.1 =
* Fix bug (generated shortcode $params with empty value)
= 1.1.0 =
* Added support for shortcodes
= 1.0.0 =
* First release