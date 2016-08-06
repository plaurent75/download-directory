=== Download Directory ===
Contributors: eoni
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=RBZUGUFT2BRFC
Tags: download, directory, download manager, downloads, AJAX, admin, newsletter, alert, custom post type
Requires at least: 4
Tested up to: 4.5.3
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create a download directory website in a minute.  Allow user to receive update alert for new software's version.

== Description ==

Download Directory allow you to create a download directory website. You will be abble to provide to your visitor a listing of software, freeware, shareware ...

Work with mostly any theme

**Multilingual ready :** currently translated in French and English. .Pot file provided, can be fully translated in any language.

**Slug translatable:** Every archive, post, categories, tags ... link are translatable. it's mean than you can provide post slug in your language, like :

* site.dev/download/wordpress
* site.dev/descargas/wordpress
* site.dev/telecharger/wordpres
* ...

With Download Directory, you can track the number of times each software are downloaded.

Visitor can also register to an update alert list, allowing them to receive an email alert when new release are available to their favorites softwares.

Updating the version field of a sofware automatically send an email alert to the suscriber of this current software.

User are also able to unregister themself from an update list whenever they want, simply by following the link at the bottom of the mail alerte they have received.

When user click on "download", they are redirected to a "downloading" page, providing an automatic loading of the download, incrementing download counter and allowing you to display whatever you want in this page (via widgets), like advertising, etc ...

This page also display the download link, and, in case it is broken, a link to the website editor, and even link to miror link if you provided it.

You can use 3 miror links, more the direct download link.

French Demo at <http://www.patricelaurent.net/telechargements/>

See it in action here : <http://www.patricelaurent.net/telecharger/wordpress/download-directory/>

== Installation ==

### From your WordPress dashboard ###

1. Visit 'Plugins > Add New'
2. Search for 'download-directory'
3. Activate Download Repository Pro from your Plugins page.
4. Enjoy

### From WordPress.org ###

1. Download the plugin.
2. Upload the 'download-directory' directory to your '/wp-content/plugins/' directory, using your favorite method (ftp, sftp, scp, etc...)
3. Activate the plugin from your Plugins page.
4. Enjoy

### From GitHub ###

1. Download the plugin.
2. Upload the 'download-directory' directory to your '/wp-content/plugins/' directory, using your favorite method (ftp, sftp, scp, etc...)
3. Activate the plugin from your Plugins page.
4. Enjoy

== Frequently Asked Questions ==

= 404 not found Error for the post, category or tags =

You should refresh your rewrite links :  Go to Admin > Settings > Permalink.
Clic the Save button. This will refresh your permalink

== Screenshots ==

1. List downloads with views
2. The metabox of custom post type Downlaods with software informations
3. Ajax Alert subscription

== Changelog ==

= 1.0 =

* Initial Release.

== Usage ==

1. Create a new Downloads, as you will normally do for a normal post.
2. In the "software informations" meta box, provide the version, size, download link, editor website, editor url miror link if available
3. Choose a category, add tags et license type (eg: freeware, shareware,etc..)
4. Add featured image to illustrate it
5. publish

== Upgrade Notice ==

= 1.0 =
Initial release, this is the first version.

== Advanced usage ==

You can use your own template for archive, category, tags and single file.

For archives pages, just create an file called archive-down_repo.php in your current theme directory.

For single page, simply create a single-down_repo.php file in your theme directory.

Adding your own css and style.

create a css file in your theme directory. Name it as you want.
then add this code in your theme function file (or in any personnal plugin):

`add_filter('down_repo_style','my_custom_css',10,1);`

`function my_custom_css($css){`

`$css=get_stylesheet_directory_uri().'/mycss.css';`

`return $css;`

`}`

This will symply replace the current css file of the plugin by your one.
If you jsut want to disable the css of the plugin, just add a null filter

`add_filter('down_repo_style',function(){return null;},10,1);`
