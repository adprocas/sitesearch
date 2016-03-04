===== version 0.1.0 =====
Friday March 4, 2016
- script crawls single initial domain

===== Information =====

This is a php script that will crawl web pages and store data into a database that can later be used for searches

Currently the script doesn't work well with JavaScript enabled webpages that create a lot of dynamic content and require JavaScript
to generate much of this content.  This is due to the fact that JavaScript is not run in the crawler.  The plan is to include this 
functionality in a later release


===== Installation =====

Edit the config-example.php file and rename it to config.php

create /path-to-your-dir/cache, and /path-to-your-dir/templates_c directories

create /path-to-your-dir/admin/cache, /path-to-your-dir/admin/templates_c

You can edit the view of the search results in the /path-to-your-dir/templates/index.tpl file

go to http://your-domain-and-path-to-sitesearch/admin to begin the setup process

Please download and put Smarty into the /path-to-your-dir/smarty folder - I have tested this with version 3.1.27 - you should end up with
file in a smarty\libs folder