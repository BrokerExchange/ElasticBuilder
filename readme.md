# ElasticBuilder

_Query DSL Builder for Elasticsearch queries_

Use ElasticBuilder to easily combine multiple queries/filters/aggregations into Elasticsearch Query DSL.

## Usage 

ElasticBuilder is a series of Abstract classes you can use to map your query input arguments to the query DSL itself.

## Examples

**Extend Abstract Class**

First you simply extend one of the abstract query classes ... in this case Boolean

```php
use ElasticBuilder\Query\Boolean;

class ArticleQuery extends Boolean
{
 //do stuff
}
```

**Add Clause**

Here is how you go about adding a clause to a query (in this case must clause to bool query).

```php
if($this->request->has('search')){
    $search = $this->request->get('search');

    $match = [
        'multi_match'=>[
            'query' => $search,
            'fields' => ['title^3','summary^1','body','userName^2','categoryName^2','tag_string^1'],
            'type' => 'cross_fields',
            'operator' => 'and'
        ]
    ];

} else {
    $match = [
        'match_all' => []
    ];
}

$this->addMust($match);
```

Here is an example of adding a filter to the bool query.

```php
$filter = [
    'range' => [
        'published_at' => [
            'lte' => Carbon::now()->toIso8601String()
        ]
    ]
];

$this->addFilter($filter);
```

Example of using a Facade

```php
$query = Boolean::must(['term'=>['category_id'=>1]])
    ->filter(['range' => ['published_at' => ['lte' => Carbon::now()->toIso8601String()]]])
    ->get();
```