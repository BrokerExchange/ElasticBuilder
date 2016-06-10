# ElasticBuilder

[![Latest Stable Version](https://poser.pugx.org/brokerexchange/elasticbuilder/version)](https://packagist.org/packages/brokerexchange/elasticbuilder)
[![Total Downloads](https://poser.pugx.org/brokerexchange/elasticbuilder/downloads)](https://packagist.org/packages/brokerexchange/elasticbuilder)
[![License](https://poser.pugx.org/brokerexchange/elasticbuilder/license)](https://packagist.org/packages/brokerexchange/elasticbuilder)


_Query DSL Builder for Elasticsearch queries_

Use ElasticBuilder to easily combine multiple queries/filters/aggregations into Elasticsearch Query DSL within Laravel projects!

## License

ElasticBuilder is released under the MIT Open Source License, <https://opensource.org/licenses/MIT>

## Copyright

ElasticBuilder &copy; Broker Exchange Network

## Overview 

ElasticBuilder is a series of Laravel Framework Package consisting of Static Methods and Abstract classes you can use to build 
Elasticsearch query DSL AND map your query input arguments to the DSL as it is generated. 
Also handles paging arguments, sorting, and aggregations. Provides Laravel Framework Service Provider and Facade, 
as well as a Trait you can apply to your eloquent models.

## Installation

ElasticBuilder must use Elasticsearch 1.x or greater, and Laravel 5.x

* Add ```"brokerexchange/elasticbuilder": "^1.0.0"``` to your `composer.json` file
* Run `composer update`
* Add provider `ElasticBuilder\ElasticBuilderServiceProvider::class` to your list of providers in `app/config/app.php` of your laravel project
* Add facade `'Eb' => ElasticBuilder\Eb::class` to your list of aliases in `app/config/app.php` of your laravel project


## Examples


### Facade

Example of using a Facade

Here is how you add a clause to a query (in this case must clause to bool query).

```php
<?php
$query = Eb::boolean()
    ->must(Eb::term('category_id',1))
    ->filter(Eb::range('published_at',['lte' => Carbon::now()->toIso8601String(),'gte' => Carbon::now()->subDay(10)->toIso8601String()]));
var_dump($query);
```

```php
<?php
$query = \Eb::multi_match(['title^3','summary^1','body','userName^2','categoryName^2','tag_string^1'],'lorim ipsum','and','cross_fields');
var_dump($query);
```

Bool query with aggregation

```php
<?php
$query = Article::boolean()
    ->must(Eb::term('category_id',1))
    ->aggregate(Eb::agg()->terms('categories','category_id'));
var_dump($query);
```

## Trait

Apply the trait class to an eloquent model (possibly one already using [Elasticquent/Elasticquent](https://github.com/elasticquent/Elasticquent) or similar package)

```php
<?php
    use ElasticBuilder\ElasticBuilderTrait;

    /**
     * Class Article
     * @package App
     */
    class Article extends Model
    {
        use ElasticBuilderTrait;
```

Now you can use a static bool,dismax,boosting etc query from within a model simlilar to the eloquent query builder!

```php
<?php
    Article::bool()->filter(Eb::term('category_id','1);
```

or 

```php
<?php
    Article::dis_max()->query(Eb::match('body',$keywords));
```


### Extending A Query Class

Any of the simply extend one of the query classes ... in this case Boolean

```php
<?php
use ElasticBuilder\Query\Boolean;
use ElasticBuilder\Eb;
use Carbon\Carbon;

class ArticleQuery extends Boolean
{
     private function published()
     {
         $filter = Eb::range('published_at',['lte' => Carbon::now()->toIso8601String()]);
         
         $this->filter($filter);
     }
}
```


```php
<?php
if($this->request->has('search')){
    $search = $this->request->get('search');
    $match = \Eb::multi_match(['title^3','summary^1','body','userName^2','categoryName^2','tag_string^1'],$search,'and','cross_fields');
} else {
    $match = \Eb::match_all();
}
$this->must($match);
```

Here is an example of adding a filter to the bool query from within the extended class

```php
<?php
$filter = \Eb::range('published_at',['lte' => Carbon::now()->toIso8601String()]);
$this->filter($filter);
```

### Other

More Examples

```php
<?php
$query = Article::agg()
    ->terms('categories','category_id');
var_dump($query);

```