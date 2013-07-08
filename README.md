GoogleAPIBundle
===============

A symfony2 bundle to communicate to Google API. This bundle implements the services I've previously used. If your service is
not implemented, please do a PR or write me a nice email and we will fix it together. 


What is HappyR?
---------------
The HappyR namespace is developed by [HappyRecruiting][1]. We put some of our bundles here because we love to share.
Since we use a lot of open source libraries and bundles in our application it feels natural to give back something.
You will find all our Symfony2 bundles that we've created for the open source world at [developer.happyr.se][2]. You
will also find more documentation about each bundle and our API clients, WordPress plugins and more.



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
        "happyr/google-api-bundle": "1.0.*",
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



[1]: http://happyrecruiting.se
[2]: http://developer.happyr.se
[3]: http://developer.happyr.se/symfony2-bundles/google-api-bundle
