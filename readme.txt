=== Mindestbestellwert - Minimum Order Value for Woocommerce ===
Contributors: andreasdoelling
Tags: mindestbestellwert, bestellwert, preis, price, minimum order value, order value, woocommerce
Requires at least: 3.0.1
Tested up to: 5.7
Requires PHP: 7.0
Stable tag: 1.2
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Lets you set a minimum order value for your WooCommerce shop. Shows a notification in cart or checkout view when the total order value is too low.

== Description ==

If you run a web shop based on the (great!) plugin [WooCommerce](https://wordpress.org/plugins/woocommerce/) you might have run into the problem of how to define a minimum order value for a shop.

Our little plugin provides exactly this additional feature.

== Installation & Usage ==

It’s easy:

1. Install the “Minimum Order Value” plugin.
1. Activate it.
1. Head for WC Settings / Checkout (admin.php?page=wc-settings&tab=checkout)
1. Scroll down to the subheadline “Minimum Order Value” and adjust the value and notification texts.
1. Save – et voilà!

== Screenshots ==

1. Screenshot showing the settings section for “Minimum Order Value” (here in the provided German translation).
2. Screenshot of the notification shown in the cart view if the user’s total cart value is below the specified minimum.

== Changelog ==

= 1.0 =
* Hello world.

= 1.1 =
* Enhancement - Reworked the logic so as to allow discounts (coupons): now the cart subtotal (before discounts are applied) is compared to the minimum order value.
* Fix - Adjusted the file names for the plugin file and the i18n files (so as to reflect the plugin slug).
* Dev - Added plugin icon.
* Dev - Tested plugin in current WP version.

= 1.2 =
* Dev - Tested plugin in current WP version.
