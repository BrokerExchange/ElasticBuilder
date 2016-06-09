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

/**
 * Class Aggregation
 * @package ElasticBuilder
 */
class Aggregation
{

    /**
     * @param $namespace
     * @param $field
     * @return array
     */
    public function terms($namespace,$field)
    {
        return [
            $namespace => [
                'terms' => [
                    'field' => $field
                ]
            ]
        ];
    }

    /**
     * @param $namespace
     * @param $field
     * @return array
     */
    public function stats($namespace,$field)
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
     * @param $namespace
     * @param $field
     * @return array
     */
    public function extended_stats($namespace,$field)
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
     * @param $namespace
     * @param $field
     * @return array
     */
    public function min($namespace,$field)
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
     * @param $namespace
     * @param $field
     * @return array
     */
    public function max($namespace,$field)
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
     * @param $namespace
     * @param $field
     * @return array
     */
    public function avg($namespace,$field)
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
     * @param $namespace
     * @param $field
     * @return array
     */
    public function cardinality($namespace,$field)
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
     * @param $namespace
     * @param $field
     * @return array
     */
    public function sum($namespace,$field)
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
     * @param $namespace
     * @param $field
     * @return array
     */
    public function value_count($namespace,$field)
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
     * @param $namespace
     * @param $field
     * @param bool $wrap_lon
     * @return array
     */
    public function geo_bounds($namespace,$field,$wrap_lon=true)
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


}