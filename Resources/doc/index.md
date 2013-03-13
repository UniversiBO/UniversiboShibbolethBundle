UniversiboShibbolethBundle
==========================
This bundle integrates Shibboleth Apache Module with Symfony 2.1 or 2.2

## Installation

### Step 1: download the bundle using composer
Add these lines to your composer.json

```js
{
    "require": {
        "universibo/shibboleth-bundle": "*"
    }
}
```

Run composer from command line to install it

``` bash
$ php composer.phar update universibo/shibboleth-bundle
```

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Universibo\Bundle\UniversiboShibbolethBundle(),
    );
}
```
### Step 3: Define a homepage rule
``` yaml
homepage:
    pattern: /
```
### Step 4: Implement user provider interface
Define a service implementing Universibo\Bundle\ShibbolethBundle\Security\User\ShibbolethUserProviderInterface

### Step 5: Configure your bundle (app/config/config.yml)
``` yaml
universibo_shibboleth:
  idp_url: # Identity Provider Web Page
    base: '%idp_url%'
    info: 'infoSSO.aspx'
    logout: 'prelogout.aspx'
  route:
    after_login:  'route_name'
  claims:
    - eppn
    - givenName
  user_provider: user.provider.service.id
  firewall_name: main # Optional, default value: main
```
(to be continued)
