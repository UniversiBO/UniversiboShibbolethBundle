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
(to be continued)
