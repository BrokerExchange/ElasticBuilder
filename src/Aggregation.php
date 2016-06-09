<?php
/**
 * Created by PhpStorm.
 * User: bmix
 * Date: 6/8/16
 * Time: 9:35 PM
 */

namespace ElasticBuilder;

class Aggregation
{
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
}