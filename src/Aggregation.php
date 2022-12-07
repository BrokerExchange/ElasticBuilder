<?php
/**
 * Created by PhpStorm.
 * User: brian@brokerbin.com
 * Date: 6/8/16
 * Time: 9:35 PM
 * License: The MIT License (MIT)
 * Copyright: (c) <Broker Exchange Network>
 */

namespace ElasticBuilder;

use ElasticBuilder\AggregationCompositeValuesSource;

/**
 * Class Aggregation
 * @package ElasticBuilder
 */
class Aggregation
{

    /**
     * @param string $namespace
     * @param string $field
     * @param int $size
     * @param array|null $order
     * @return array
     */
    public function terms(string $namespace, string $field, int $size=10, array $order = null): array
    {
        $terms = [
            'field' => $field,
            'size' => $size
        ];

        if(!empty($order))
        {
            $terms = array_merge($terms,['order' => $order]);
        }

        return [
            $namespace => [
                'terms' => $terms
            ]
        ];
    }

    /**
     * @param string $namespace
     * @param string $field
     * @return array
     */
    public function stats(string $namespace, string $field): array
    {
        return [
            $namespace => [
                'stats' => [
                    'field' => $field
                ]
            ]
        ];
    }

    /**
     * @param string $namespace
     * @param string $field
     * @return array
     */
    public function extended_stats(string $namespace, string $field): array
    {
        return [
            $namespace => [
                'extended_stats' => [
                    'field' => $field
                ]
            ]
        ];
    }

    /**
     * @param string $namespace
     * @param string $field
     * @return array
     */
    public function min(string $namespace, string $field): array
    {
        return [
            $namespace => [
                'min' => [
                    'field' => $field
                ]
            ]
        ];
    }

    /**
     * @param string $namespace
     * @param string $field
     * @return array
     */
    public function max(string $namespace, string $field): array
    {
        return [
            $namespace => [
                'max' => [
                    'field' => $field
                ]
            ]
        ];
    }

    /**
     * @param string $namespace
     * @param string $field
     * @return array
     */
    public function avg(string $namespace, string $field): array
    {
        return [
            $namespace => [
                'avg' => [
                    'field' => $field
                ]
            ]
        ];
    }

    /**
     * @param string $namespace
     * @param string $field
     * @return array
     */
    public function cardinality(string $namespace, string $field): array
    {
        return [
            $namespace => [
                'cardinality' => [
                    'field' => $field
                ]
            ]
        ];
    }

    /**
     * @param string $namespace
     * @param string $field
     * @return array
     */
    public function sum(string $namespace, string $field): array
    {
        return [
            $namespace => [
                'sum' => [
                    'field' => $field
                ]
            ]
        ];
    }

    /**
     * @param string $namespace
     * @param string $field
     * @return array
     */
    public function value_count(string $namespace, string $field): array
    {
        return [
            $namespace => [
                'value_count' => [
                    'field' => $field
                ]
            ]
        ];
    }

    /**
     * @param string $namespace
     * @param string $field
     * @param bool $wrap_lon
     * @return array
     */
    public function geo_bounds(string $namespace, string $field, bool $wrap_lon = true): array
    {
        return [
            $namespace => [
                'geo_bounds' => [
                    'field' => $field,
                    'wrap_longitude' => $wrap_lon
                ]
            ]
        ];
    }

    /**
     * @param string $namespace
     * @param array $filter
     * @param array $aggs
     * @return array
     */
    public function filter(string $namespace,array $filter, array $aggs): array
    {
        return [
            $namespace => [
                'filter' => $filter,
                'aggs' => $aggs
            ]
        ];
    }

    /**
     * @param string $namespace
     * @param array $filters
     * @return array
     */
    public function filters(string $namespace, array $filters): array
    {
        return [
            $namespace => [
                'filters' => [
                    'filters' => $filters
                ]
            ]
        ];
    }

    /**
     * @param string $namespace
     * @param string $field
     * @param array $ranges
     * @return array
     */
    public function range(string $namespace, string $field, array $ranges): array
    {
        return [
            $namespace => [
                'range' => [
                    'field' => $field,
                    'ranges' => $ranges
                ]
            ]
        ];
    }

    /**
     * @param string $namespace
     * @param string $type
     * @return array
     */
    public function children(string $namespace, string $type): array
    {
        return [
            $namespace => [
                'children' => [
                    'type' => $type
                ]
            ]
        ];
    }

    /**
     * @param string $namespace
     * @param string $field
     * @param string $interval
     * @param string|null $format
     * @param string|null $timezone
     * @param string|null $offset
     * @return array
     */
    public function date_histogram(
        string $namespace,
        string $field,
        string $interval,
        string|null $format=null,
        string|null $timezone=null,
        string|null $offset=null
    ): array
    {
        return [
            $namespace => [
                'date_histogram' => array_filter([
                    'field' => $field,
                    'interval' => $interval,
                    'format' => $format,
                    'time_zone' => $timezone,
                    'offset' => $offset
                ])
            ]
        ];
    }

    /**
     * @param string $namespace
     * @param string $field
     * @param array $ranges
     * @param string|null $format
     * @param string|null $missing
     * @return array
     */
    public function date_range(string $namespace, string $field, array $ranges, string $format = null, string $missing = null): array
    {
        $date_range = [
            $namespace => [
                'date_range' => array_filter([
                    'field' => $field,
                    'ranges' => $ranges
                ])
            ]
        ];

        if(!empty($format)){
            $date_range[$namespace]['date_range']['format'] = $format;
        }

        if(!empty($missing)){
            $date_range[$namespace]['date_range']['missing'] = $missing;
        }

        return $date_range;
    }

    /**
     * @param string $namespace
     * @param string $field
     * @param string $origin
     * @param array $ranges
     * @param string|null $unit
     * @return array
     */
    public function geo_distance(string $namespace, string $field, string $origin, array $ranges, string $unit=null): array
    {

        $geo_distance =  [
            $namespace => [
                'geo_distance' => array_filter([
                    'field' => $field,
                    'origin' => $origin,
                    'ranges' => $ranges
                ])
            ]
        ];

        if(!empty($unit)){
            $geo_distance[$namespace]['geo_distance']['unit'] = $unit;
        }

        return $geo_distance;
    }

    /**
     * @param string $namespace
     * @param string $field
     * @param int $percision
     * @param int $size
     * @param int $shard_size
     * @return array
     */
    public function geohash_grid(string $namespace, string $field, int $percision = 2, int $size = 10000, int $shard_size = 0): array
    {
        return [
            $namespace => [
                'geohash_grid' => [
                    'field' => $field,
                    'percision' => $percision,
                    'size' => $size,
                    'shard_size' => $shard_size
                ]
            ]
        ];
    }

    /**
     * note: this is the global aggregation ... but can't use global as a function name (hence: "all")
     *
     *
     * @param string $namespace
     * @param array $aggs
     * @return array
     */
    public function all(string $namespace, array $aggs): array
    {
        return [
            $namespace => [
                'global' => [],
                'aggs' => $aggs
            ]
        ];
    }

    /**
     * @param string $namespace
     * @param string $field
     * @param int $interval
     * @param array $order
     * @param int $offset
     * @param bool|null $keyed
     * @param int|string|null $missing
     * @param int|null $minimum_doc_count
     * @return array
     */
    public function histogram(string $namespace, string $field, int $interval, array $order = ['_key' => 'asc'], int $offset = 0, bool|null $keyed = null, int|string $missing = null, int $minimum_doc_count = null): array
    {

        $histogram = [
            $namespace => [
                'histogram' => array_filter([
                    'field' => $field,
                    'interval' => $interval,
                    'order' => $order,
                    'offset' => $offset
                ])
            ]
        ];

        if(!empty($keyed)){
            $histogram[$namespace]['histogram']['keyed'] = $keyed;
        }

        if(!empty($minimum_doc_count)){
            $histogram[$namespace]['histogram']['min_doc_count'] = $minimum_doc_count;
        }

        if(!empty($minimum_doc_count)){
            $histogram[$namespace]['histogram']['missing'] = $missing;
        }

        return $histogram;
    }

    /**
     * @param string $namespace
     * @param string $field
     * @return array
     */
    public function missing(string $namespace, string $field): array
    {
        return [
            $namespace => [
                'missing' => [
                    'field' => $field
                ]
            ]
        ];
    }

    /**
     * @param string $namespace
     * @param string $path
     * @param array $aggs
     * @return array
     */
    public function nested(string $namespace,string $path, array $aggs): array
    {
        return [
            $namespace => [
                'nested' => [
                    'path' => $path
                ],
                'aggs' => $aggs
            ]
        ];
    }

    /**
     * @param string $namespace
     * @param string $field
     * @return array
     */
   public function significant_terms(string $namespace, string $field): array
   {
       return [
           $namespace => [
               'significant_terms' => [
                   'field' => $field
               ]
           ]
       ];
   }
   
   /**
     * @param string $namespace
     * @param string $field
     * @return array
     */
   public function significant_text(string $namespace, string $field): array
   {
       return [
           $namespace => [
               'significant_text' => [
                   'field' => $field
               ]
           ]
       ];
   }

   /**
     * @param string $namespace the aggregation's output namespace
     * @param array $values_source the sources of the values
     * @param int $size maximum number of composite buckets to be returned
     * @return array[][]
     */
   public function composite(string $namespace, array $values_source, int $size = 10): array
   {

       return [
           $namespace => [
                'composite' => [
                    'size' => $size,
                    'sources' => $values_source
                ]
           ]
       ];

   }

}
