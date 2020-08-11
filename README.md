PostgreSearchBundle
===================================

Symfony2 bundle with tools <a target="_blank" href="http://www.postgresql.org/docs/9.1/static/textsearch.html">full-text search PostgreSQL</a> in Doctrine 2.

Added type 'tsvector' to be used in the mapping.

Added functions 'to_tsquery' and 'ts_rank' to be used in the DQL.

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

Add in your config.yml:

```yml
# Doctrine Configuration
doctrine:
    dbal:
        types:
            tsvector: Ddmaster\PostgreSearchBundle\Dbal\TsvectorType
        mapping_types:
            tsvector: tsvector
    orm:
        entity_managers:
            default:
                dql:
                    string_functions:
                        TSQUERY: Ddmaster\PostgreSearchBundle\DQL\TsqueryFunction
                        TSRANK: Ddmaster\PostgreSearchBundle\DQL\TsrankFunction
```

### Step 4: Mapping example
```php
/**
 * @var string
 *
 * @ORM\Column(name="search_fts", type="tsvector", nullable=true)
 */
private $searchFts;
```

### Step 5: Use in DQL

```php
$searchQuery = 'family | history';
$em = $this->getDoctrine()->getManager();
$query = $em->createQuery(
    'SELECT b.id, sum(TSRANK(b.searchFts, :searchQuery)) as rank 
        FROM DemoSearchBundle:Books b
        WHERE TSQUERY( b.searchFts, :searchQuery ) = true
        GROUP BY b.id
        ORDER BY rank DESC')
    ->setParameter('searchQuery', $searchQuery)
;
$result = $query->getArrayResult();
```

Result example:

```yml
Array
(
    [0] => Array
        (
            [id] => 2
            [rank] => 0.0607927
        )
    [1] => Array
        (
            [id] => 3
            [rank] => 0.0303964
        )
)
```
