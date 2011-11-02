=== Thinkun Remind ===
Contributors: thinkun
Tags: email template, send email, reminder, email, html, custom fields
Requires at least: 3.0.0
Tested up to: 3.2.1
Stable tag: 1.1.3

Send a branded and personalised HTML or text email to anyone, using your own custom email templates and custom fields.

== Description ==

Send a branded and personalised HTML or text email to anyone, such as a customer who you'd like to remind to leave feedback for you. This can be used for almost any HTML or text email templates, and provides an easy interface for a user of any skill level to send a personalised HTML email.

=Features=

* Create your own text or HTML email templates and reuse them.
* Preview these templates as fully rendered HTML before sending.
* Include and manage custom fields to personalise the email, which can then be filled out to populate variables by even the most novice user on the Send Email screen. 
* Export a history of all emails sent as a CSV file.

== Installation ==

1. Download the zip file from http://wordpress.org/extend/plugins/thinkun-remind/
1. Unzip it in your plugins directory (/wp-content/plugins/)
1. Configure your From Name and From Email in the Mail Settings tab of the Thinkun Remind Settings page.
1. Add your email templates and custom fields.

== Frequently Asked Questions ==

= How do I use custom fields in email templates? =

First, add the custom fields you'd like to use, on the Custom Fields tab of Thinkun Remind Settings. Thinkun Remind will automatically sanitise your field title into a variable you can use anywhere in your email templates.
These fields will now also appear on the Send Reminder Email page, as optional fields you can fill in depending on your chosen template.
The variables [name] and [message] are included by default.
Note: these variable codes are always sanitised to lowercase and must be lowercase in your email templates - variables such as "[NAME]" or "[CusTomfield]" in your email templates will not be recognised by Thinkun Remind.

== Screenshots ==

1. The Send Email page after previewing.
2. The Edit Template page after previewing.

== Changelog ==

= 1.1.1 =

* Fixed issue with plugin not functioning when installed from repository

= 1.1 =

* Initial release.