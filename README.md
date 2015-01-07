Google API Bundle
=================

A symfony2 bundle to communicate to Google API. This bundle is a Symfony2 wrapper for the [google apiclient][1].
There are some services not yet implemented. Please submit a PR and I'm happy to merge.



Installation
------------

### Step 1: Using Composer

Install it with Composer!

```js
// composer.json
{
    // ...
    require: {
        // ...
        "happyr/google-api-bundle": "~2.1",
    }
}
```

Then, you can install the new dependencies by running Composer's ``update``
command from the directory where your ``composer.json`` file is located:

```bash
$ php composer.phar update
```

### Step 2: Register the bundle

To register the bundles with your kernel:

```php
<?php

// in AppKernel::registerBundles()
$bundles = array(
    // ...
    new HappyR\Google\ApiBundle\HappyRGoogleApiBundle(),
    // ...
);
```

### Step 3: Configure the bundle

``` yaml
# app/config/config.yml
# you will get these parameters form https://code.google.com/apis/console/"
happy_r_google_api:
  application_name: MySite
  oauth2_client_id: 
  oauth2_client_secret: 
  oauth2_redirect_uri: 
  developer_key: 
  site_name: mysite.com
```


[1]: https://github.com/google/google-api-php-client
