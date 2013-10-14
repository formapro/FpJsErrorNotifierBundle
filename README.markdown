Getting Started With FpJsErrorNotifierBundle
============================================

After a simple setup this module will allow you to get notifications by email about occured errors in Javasccript.
This bundle can be used as an independent module or together with BadaBoom bundle.

## Installation

### Step 1: Download FpJsErrorNotifierBundle using composer

Add FOSUserBundle in your composer.json:

```js
{
    "require": {
        "fp/jserrornotifier-bundle": "dev-master"
    }
}
```
Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update fp/jserrornotifier-bundle
```

Composer will install the bundle to your vendors

### Step 2: Enable the Bundle

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Fp\JsErrorNotifierBundle\FpJsErrorNotifierBundle(),
    );
}
```

### Step 3: Configure your application's security.yml

``` yaml
# app/config/security.yml
security:
    access_control:
        - { path: ^/fp_js_error_notifier, role: IS_AUTHENTICATED_ANONYMOUSLY }
```

### Step 4: Import FpJsErrorNotifierBundle routing

``` yaml
# app/config/routing.yml

fp_js_error_notifier:
    resource: "@FpJsErrorNotifierBundle/Resources/config/routing.xml"
```

### Step 5. Add Javascript to your layout

``` twig
{% javascripts '@FpJsErrorNotifierBundle/Resources/public/js/jsErrorNotifier.js' %}
    <script type="text/javascript" src="{{ asset_url }}"></script>
{% endjavascripts %}
```

### Step 6: Configure the FpJsErrorNotifierBundle

If you have an enabled and configured BadaBoom bundle, you can skip this step.
Otherwise you have to add some configurations for this bundle:

``` yaml
# app/config/config.yml
fp_js_error_notifier:
    email_from: 'noreply@test.com' # sender email
    email_to: ['john@example.com'] # list of recipients notification
```