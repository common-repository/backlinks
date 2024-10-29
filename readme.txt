=== Backlinks ===
Contributors: db0
Donate link: http://svcs.affero.net/rm.php?&r=dbzer0&p=Void_Infinite  
Tags: backlinks, links, google blog search, feed, simplepie
Requires at least: 2.3
Tested up to: 2.7.1
Stable tag: 1.1.2

A simple function to show blog posts linking back to any article, similar to Google Backlinks

== Description ==
This is a simple function to show blog posts linking back to any article, similar to [google backlinks](http://help.blogger.com/bin/answer.py?answer=42533). 

As many people still (for some strange reason) insist on using blogger which [does not support trackbacks](http://help.blogger.com/bin/answer.py?hl=en&answer=42304) very often, you do not see the full number of returning links.

This plugin uses a [simple google blog search](http://blogsearch.google.com/) to find articles linking to your posts and lists them in a simple format, ordered by date.

**Important Notes**

* This plugin requires the [Simplepie Core plugin](http://wordpress.org/extend/plugins/simplepie-core) in order to work.

== Installation ==

1. Extract the directory
1. Upload `/backlinks/` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `<?php if (function_exists('backlinks')) backlinks(); ?>` in a page template, a widget or in your sidebar. Alternatively, use the included widget.
1. Use the options page to change the way it looks and functions.

== Frequently Asked Questions ==

= The default stylesheet sucks. Why don't you make it better =

I suck at design. If you have any ideas for a good default look, let me know and I'll implement them

= Forget that! How do I format the output to fit my own blog =

I've used various css class on the elements that exist

* Use the <a> class `backlinks_title` to format the title link.
* Use the <a> class `morelink` to format the "more results" link.
* Use the <ul> class `dates` to format the list
* Use the <span> class `date` to format the date of each link

= I don't get it. What is a backlink and why do I need it? =

Can't you read the google description? *Sigh*
Ok, a backlink is basically another blog linking directly to your article. Since not all blogs send trackbacks, you wordpress blog does not always know who is responding to, or commenting on your posts. Sure, you might be able to see it in your dashboard but that's just you. 
Also, since google filters out splogs from the blog search, it's a nice spam-free option to have.

You can also use it if you've disabled trackbacks or if you prefer all your incoming links to be shown in the same place.

= I want/need so-and-so feature =

Patches are welcome and I'm always open to more developers who want to join.
I'm learning as I go so I don't know if I'll be able to do it myself or when.

== Screenshots ==

1. A page with a lot of incoming links
2. The options page

== Other Notes ==

* Caching is enabled. If you find any problems with it let me know
* At the moment the plugin returns only the most recent 100 links
* You can link directly to the backlinks in any page by using the #backlinks anchor

== Changelog == 

= 1.1.2 =

Updated for WP 2.8+. Plugin page now accessible

= 1.1 =

Added option to hide backlinks when there are no results 

= 1.0 =

Widget now has full capability

= 0.9 = 

Enabled Caching

= 0.8 =

Enabled Custom Styling Capability

= 0.5 =

Added Options page

= 0.3 =

Created basic widget

= 0.2 =

Basic Plugin created