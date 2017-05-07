=== WP Security Audit Log addon for Paid Memberships Pro ===
Contributors: bwsolutions
Tags: WP Security Audit Log, Paid Memberships Pro, pmpro, memberships, audit
Requires at least: 4
Tested up to: 4.7.2
Depends on : WP Security Audit Log, Paid Memberships Pro
Stable tag: 1.0

An Addon to WP Security Audit Log to log events in Paid Memberships Pro

== Description ==

Extend the plugin WP Security Audit Log to log events in Paid Memberships Pro.
This also include audit logs to track changes to User Meta values.

= Actions / Hooks Supported in the plugin =
This plugin currently supports the following actions.

User Meta Actions:
1.  "added_user_meta"  - logs alert whenever a Meta Field is created for user account.
2.  "update_user_meta" - Saves current value of a Meta Field so we can see what changes.
3.  "updated_user_meta" - logs alert with Value of Meta Field that changed.
4.  "deleted_user_meta" - logs event when a User Meta Field is deleted for a user account.

Actions that are related to Paid Memberships Pro (PMPro) plugin:

PMPro creates what it calls an "order" everytime a payment is made on an account. This includes the initial payment and recurring or subscription payments.
PMPro provides several actions to allow us to log creation and changes of these orders/payments.

1. 'pmpro_added_order' - logs an event when a PMPro Order for a membership payment is created.
2. 'pmpro_delete_order' - logs an event when a PMPro Order for a membership payment is deleted.
3. 'pmpro_update_order' - saved information about a PMPro Order for a membership payment to see what changes.
4. 'pmpro_updated_order' - logs an event when a PMPro Order for a membership payment with information that was changed.

When a user either joins a PMPro defined membership level or the account is cancelled or changed to another level, we can use the following actions
to try to see what was changed and who changed the account.

5. 'pmpro_before_change_membership_level' - saves information about an user that is about to change or cancel current PMPro membership level
6. 'pmpro_after_change_membership_level' - logs event that details changes in membership levels for a user. Could be upgrade or canceling PMPro membership.
7. 'pmpro_after_checkout' - logs information after PMPro checkout process complete. Displays related PMPro order number and any discount codes applied during checkout.

PMPro allows the system to define several different levels, with differt criteria. These actions allow use to monitor changes to these levels and
what users are making the changes.

8. 'pmpro_delete_membership_level' - logs an event when a PMPro membership level is deleted and no longer available for users to select.
9. 'pmpro_save_membership_level' - logs an event when a PMPro membership level is added or updated. logs details of level to see new values.

PMPro allows the use of discount codes during the checkout / payment process.  These actions allow us the ability to monitor changes
to the discount codes.

10. 'pmpro_delete_discount_code' - logs event when a PMPro discount code is deleted from the system.
11. 'pmpro_save_discount_code' - logs event when a PMPro discount code is added or updated. Log details about discount code.
12. 'pmpro_save_discount_code_level' - Discount codes can change PMPro Membership Leves in different ways, this logs a event detailing the discount to be applied to s specific PMPro Membership Level

PMPro supports multiple payment gateways. Some of the gateways allow subscription payments. The following actions in PMPro are called when certain subscription
events occur. Some of these actions are payment gateway specific.

13. 'pmpro_subscription_cancelled' - logs and event when a payment subscription is cancelled. This could be from a user action or possible payment failure.
14. 'pmpro_subscription_expired' - logs an event when a payment subscription expires. Could be only valid for 5 payments.
15. 'pmpro_subscription_ipn_event_processed' - logs event when Paypal IPN event is processed. Usually only on a subscription payment initiated by PayPal.
16. 'pmpro_subscription_payment_completed' - logs event when a subscription payment is completed successfully.
17. 'pmpro_subscription_payment_failed' - logs an event when payment gateway is unable to process a subscription payment.
18. 'pmpro_subscription_payment_went_past_due' - logs an event when payment gateway is unable to process a subscription payment and the due date is past.

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

= 1.1.1 =
* Fixed discount code to show added or modified
* Fixed bug in call to sensor manager

= 1.1 =
* Fixed to work with version 2.6.2 of WP Security Audit Log
* Added activation checks to make sure plugin versions compatibility

= 1.0 =
* Initial version