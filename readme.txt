=== Export2PDF ===
Contributors: export2pdf
Donate link: http://www.export2pdf.com/
Tags: shortcode, posts, export, pdf
Requires at least: 4.0.0
Tested up to: 4.5.2
Stable tag: 1.02
License: GPL
License URI: http://www.gnu.org/licenses/gpl-3.0.en.html

Easily export WooCommerce invoices, Formidable Forms and WordPress posts to PDF.

== Description ==

= Features =

* For simple PDF layouts: PDF creation tool. As simple as a WordPress posts editor.
* For complex PDF layouts: upload your own PDF templates, and fill PDF fields with data from WordPress.
* Use any Google font, any color and any paper size for your PDF.
* Password protection of your PDF files.
* PDF optimizations for images and fonts.
* Attach PDFs to e-mails
* Easy translation editor
* Shortcodes
* Supports special UTF-8 characters (Ó, ć, Á, ń, Đ, ů, etc.)
* ... and more in the future updates.

= Formidable Forms =

These field types are supported: 

* Single line text
* Paragraph text
* Checkboxes
* Radio buttons
* Dropdown
* Email address
* Website/URL 
* Number
* Phone number
* Date
* Time
* Image URL

Also,

* PDFs can be attached to notifications

== Installation ==

1. Visit 'Plugins → Add New'
2. Search for 'Export2PDF'
3. Click 'Activate'.

This plugin can be downloaded and used for free.
However, a license must be purchased for continued use.

== Frequently Asked Questions ==

= What are the requirements? =

PHP >= 5.4 is required. This is usually the case for most of websites.
If you don't have the required version of PHP, you'll see a simple warning that PHP should be upgraded.

= How does it work? =

You create or upload a PDF to your WordPress dashboard.
The template is sent to our server, and we generate the file for you.
The file is sent back to your website.

= Why can't I generate PDF files on my server? =

The reason for this is that PDF generation requires a lot of software, which is not available on most websites.
For example, Java, GhostScript, PdfTk, etc.
This means that you simply can't run it on any server.
Nevertheless, if you own a VPS server, you can generate PDFs on your server. Please refer to how to install offline version article on our website.

= Security =

We don't store any data except for PDF templates.
Storing PDFs is done to speed up PDF generation process.
Sensible data (e-mails, addresses, phone numbers, ...) is never stored on our server.
Communication between your side and API is done via a secured SSL (https://) protocol.
To keep your data safe, you can install the offline version article on our website.

= New Features =

Feel free to leave comments about what features you'd like to see.
We can integrate almost any WordPress plugin with our PDF generation software. 

= Licence =

This plugin is licenced under [GPL licence](http://www.gnu.org/licenses/gpl-3.0.en.html).
Server-side plugin uses different free open-source projects licences under Apache 2.0, MIT and LGPL licence.

== Screenshots ==

1. Advanced PDF designer.
1. Simple PDF designer.

== Changelog ==

= 1.02 =
* More translations
* Validation for checkbox/radio values
* Improvements for Image and Date/Time format
* Bugfixes
* Updated readme.txt

= 1.01 =
* Initial release.
