=== WP Security Audit Log addon for Paid Memberships Pro ===
Contributors: bwsolutions
Tags: WP Security Audit Log, Paid Memberships Pro, pmpro, memberships, audit
Requires at least: 4
Tested up to: 4.7.2
Stable tag: 1.0

An Addon to WP Security Audit Log to log events in Paid Memberships Pro

== Description ==

Extend the plugin WP Security Audit Log to log events in Paid Memberships Pro.
This also include audit logs to track changes to User Meta values.

== Installation ==

= Download, Install and Activate! =
In your WordPress admin, go to Plugins > Add New to install WP Security Audit Log for Paid Memberships Pro, or:

1. Download the latest version of the plugin.
2. Unzip the downloaded file to your computer.
3. Upload the /wpsecurity-audit-log-for-pmpro/ directory to the /wp-content/plugins/ directory of your site.
4. Activate the plugin through the 'Plugins' menu in WordPress.

= Complete the Initial Plugin Setup =

All Audits are enabled at installation.

To Enable/Disable alerts:

1. Go to Audit Log in the WordPress admin
2. Click on Enable/Disable Alerts under Audit Log
3. In tab "Paid Memberships Pro" - you can enable or disable specific alerts
4. In tab "User Meta Alerts" - you can enable or disable User Meta alerts

To Exclude alerts:

1. Go to Settings un Audit Log
2. Click Exclude Objects tab
3. In Custom Fields section add fields you don't want logged.

    Example:  Enter "pmpro_views" and click add.

    This will no longer log changes to the user meta field "pmpro_views"

== Screenshots ==

== Changelog ==

= 1.0 =
* Initial version