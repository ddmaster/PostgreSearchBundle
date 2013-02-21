PostgreSearchBundle
===================================

Tools to use <a target="_blank" href="http://www.postgresql.org/docs/9.1/static/textsearch.html">full-text search PostgreSQL</a> in Doctrine.

### Step 1: Download PostgreSearchBundle using composer

Add PostgreSearchBundle in your composer.json:

```js
{
    "require": {
        "ddmaster/postgre-search-bundle": "dev-master"
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update ddmaster/postgre-search-bundle
```

Composer will install the bundle to your project's `vendor/ddmaster/postgre-search-bundle` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Ddmaster\PostgreSearchBundle\PostgreSearchBundle(),
    );
}
```

### Step 3: Configure

Add in your config.yaml:

```yml
# Doctrine Configuration
doctrine:
    dbal:
        types:
            tsvector: Ddmaster\PostgreSearchBundle\Dbal\TsvectorType
    orm:
        entity_managers:
            default:
                dql:
                    string_functions:
                        TSQUERY: Ddmaster\PostgreSearchBundle\DQL\TsqueryFunction
                        TSRANK: Ddmaster\PostgreSearchBundle\DQL\TsrankFunction
```
