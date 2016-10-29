YOURLS API - Regenerate URL
====================

Plugin for [YOURLS](http://yourls.org) v1.7 (earlier versions not tested). Adds a custom API action `regenerate_url`, which generates a new keyword for a URL that has already been shortened.

Description
-----------
The API action `regenerate_url` accepts two parameters:
* `old`: the URL you want to regenerate. Accepts both keywords (`abc`) and shortened URL's (`http://sho.rt/abc`).
* `new` (optional): the new keyword for the shortened URL. If this parameter is not passed, a random keyword will be generated (according to the rules and plugins you currently use).

Installation
------------
1. Copy this folder to `/user/plugins`.
2. Go to the Plugins Administration page (e.g. `http://sho.rt/admin/plugins.php`) and activate the plugin.
3. Have fun!

License
-------
YOURLS' license, aka "Do whatever the hell you want with it".

Contact
-------
Feel free to contact me through GitHub if you have feedback or questions!