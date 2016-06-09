<?php
/**
 * Created by PhpStorm.
 * User: bmix
 * Date: 6/8/16
 * Time: 8:17 PM
 */

namespace ElasticBuilder;

use ElasticBuilder\Query\Boolean;
use ElasticBuilder\Query\DisMax;

class ElasticBuilder
{
    public function agg()
    {
        return new Aggregation;
    }
    
    public function boolean()
    {
        return new Boolean();
    }
    
    public function dis_max()
    {
        return new DisMax();
    }

    public function term($field,$value)
    {
        return [
            'term' => [
                $field=>$value
            ]
        ];
    }

    public function range($field,$ranges=[],$boost=1)
    {
        
        $ranges = array_merge($ranges,['boost'=>$boost]);
        
        return [
            'range' => [
                $field => $ranges
            ]
        ];
    }

    public function match($field,$query,$operator='or',$type='boolean',$minimum=1,$boost=1,$analyzer='standard')
    {
        return [
            'match' => [
                $field => [
                    'query' => $query,
                    'operator' => $operator,
                    'type' => $type,
                    'analyzer' => $analyzer,
                    'minimum_should_match' => $minimum

                ],
                'boost' => $boost
            ]
        ];
    }

    public function multi_match($fields=[],$query,$operator='or',$type='cross_match',$minimum=1,$boost=1,$analyzer='standard')
    {
        return [
            'multi_match' => [
                'query' => $query,
                'type' => $type,
                'fields' => $fields,
                'operator' => $operator,
                'minimum_should_match' => $minimum,
                'analyzer' => $analyzer,
                'boost' => $boost
            ]
        ];
    }

    public function match_all()
    {
        return [
            'match_all' => []
        ];
    }

}