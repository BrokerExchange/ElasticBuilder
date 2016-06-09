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

/**
 * Class ElasticBuilder
 * @package ElasticBuilder
 */
class ElasticBuilder
{
    /**
     * @return Aggregation
     */
    public function agg()
    {
        return new Aggregation;
    }

    /**
     * @return Boolean
     */
    public function boolean()
    {
        return new Boolean();
    }

    /**
     * @return DisMax
     */
    public function dis_max()
    {
        return new DisMax();
    }

    /**
     * @param $field
     * @param $value
     * @return array
     */
    public function term($field,$value)
    {
        return [
            'term' => [
                $field=>$value
            ]
        ];
    }

    /**
     * @param $field
     * @param array $ranges
     * @param int $boost
     * @return array
     */
    public function range($field,$ranges=[],$boost=1)
    {
        
        $ranges = array_merge($ranges,['boost'=>$boost]);
        
        return [
            'range' => [
                $field => $ranges
            ]
        ];
    }

    /**
     * @param $field
     * @param $query
     * @param string $operator
     * @param string $type
     * @param int $minimum
     * @param int $boost
     * @param string $analyzer
     * @return array
     */
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

    /**
     * @param array $fields
     * @param $query
     * @param string $operator
     * @param string $type
     * @param int $minimum
     * @param int $boost
     * @param string $analyzer
     * @return array
     */
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

    /**
     * @return array
     */
    public function match_all()
    {
        return [
            'match_all' => []
        ];
    }

}