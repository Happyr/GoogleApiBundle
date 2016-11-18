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

Basic Usage
------------

### Step 1: Create a controller
Create a controller with `authenticate` and `redirect` methods.

```php
<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class GoogleOAuthController extends Controller
{
  /**
   * @Route("/oauth/google/auth")
   */
  public function getAuthenticationCodeAction()
  {
  }

  /**
   * @Route("/oauth/google/redirect")
   */
  public function getAccessCodeRedirectAction(Request $request)
  {
  }
}
```

### Step 2: Get the access code

Setup the required scope of your app and redirect the user to complete their part of the OAuth request.

```php
// ...

  private $accessScope = [
    \Google_Service_Calendar::CALENDAR
  ];

  /**
   * @Route("/oauth/google/auth")
   */
  public function getAuthenticationCodeAction()
  {
    $client = $this->container->get('happyr.google.api.client');

    // Determine the level of access your application needs
    $client->getGoogleClient()->setScopes($this->accessScope);

    // Send the user to complete their part of the OAuth
    return $this->redirect($client->createAuthUrl());
  }

 // ...
 ```

### Step 3: Handle the redirect

Determine if an access code has been returned. If there is an access code then exchange this for an access token by using the client `authenticate` method.

```php
// ...

  private $accessScope = [
    \Google_Service_Calendar::CALENDAR
  ];

// ...

  /**
   * @Route("/oauth/google/redirect")
   */
  public function getAccessCodeRedirectAction(Request $request)
  {
    if($request->query->get('code'))
    {
      $code = $request->query->get('code');

      $client = $this->container->get('happyr.google.api.client');
      $client->getGoogleClient()->setScopes($this->accessScope);
      $client->authenticate($code);

      $accessToken = $client->getGoogleClient()->getAccessToken();

      // TODO - Store the token, etc...
    } else {
      $error = $request->query->get('error');
      // TODO - Handle the error
    }
  }

// ...
```

If successful the response should include `access_token`, `expires_in`, `token_type`, and `created`.
