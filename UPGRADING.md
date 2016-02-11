UPGRADING
=========

Upgrade from version 2.\* to 3.\*
-------------------------------
* Since version 3.\*, we support version 2.\* of the Google API. Although this doesn't change much to the API of this bundle, you should be aware that many things changed (including authentication) in the Google API. See https://github.com/google/google-api-php-client/blob/master/UPGRADING.md.
If you do not want to upgrade, you should add the version constraint `"google/apiclient": "1.*"` to your `composer.json`.