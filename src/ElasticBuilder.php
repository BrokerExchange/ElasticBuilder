<?php

/**
 * Created by PhpStorm.
 * User: brian@brokerbin.com, jpage@brokerbin.com
 * Date: 6/8/16
 * Time: 8:17 PM
 * License: The MIT License (MIT)
 * Copyright: (c) <Broker Exchange Network>
 */

namespace ElasticBuilder;

use ElasticBuilder\Query\Boolean;
use ElasticBuilder\Query\Boosting;
use ElasticBuilder\Query\DisMax;
use ElasticBuilder\Query\ConstantScore;

use Elasticsearch\Client as Elastic;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as BaseCollection;



/**
 * Class ElasticBuilder
 * @package ElasticBuilder
 */
class ElasticBuilder
{

    /**
     * Create a new engine instance.
     *
     * @param  \Elasticsearch\Client  $elastic
     * @return void
     */
    public function __construct(Elastic $elastic, $index)
    {
        $this->elastic = $elastic;
        $this->index = $index;
    }


    /**
     * @return Aggregation
     */
    public function agg()
    {
        return new Aggregation;
    }

    /**
     * @param int $boost
     * @param int $minimum_should_match
     * @return Query\Boolean
     */
    public function boolean($boost=1,$minimum_should_match=1)
    {
        return new Boolean($boost,$minimum_should_match);
    }

    /**
     * @param int $boost
     * @return DisMax
     */
    public function dis_max($boost=1)
    {
        return new DisMax($boost);
    }

    /**
     * @param int $boost
     * @return ConstantScore
     */
    public function constant_score($boost=1)
    {
        return new ConstantScore($boost);
    }

    /**
     * @param int $negative_boost
     * @return Boosting
     */
    public function boosting($negative_boost=1)
    {
        return new Boosting($negative_boost);
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
                $field => $value
            ]
        ];
    }

    /**
     * @param $field
     * @param array $values
     * @return array
     */
    public function terms($field,$values=[])
    {
        return [
            'terms' => [
                $field => $values
            ]
        ];
    }

    /**
     * @param $field
     * @param array $ranges
     * @param int $boost
     * @return array
     */
    public function range($field,$ranges=[],$boost=null)
    {

        if(!is_null($boost))
        {
            $ranges = array_merge($ranges,['boost'=>$boost]);
        }

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
     * @param int $minimum
     * @param int $boost
     * @param string $analyzer
     * @param int $fuzziness
     * @return array
     */
    public function match($field,$query,$operator='or',$minimum=null,$boost=null,$analyzer='',$fuzziness=null)
    {

        $params = [

            'match' => [
                $field => array_filter([
                    'query' => $query,
                    'operator' => $operator,
                    'analyzer' => $analyzer,
                    'minimum_should_match' => $minimum,
                    'boost' => $boost,
                    'fuzziness' => $fuzziness
                ])
            ]
        ];

        return $params;
    }

    /**
     * @param array $fields
     * @param $query
     * @param string $operator
     * @param string $type
     * @param int $minimum
     * @param int $boost
     * @param string $analyzer
     * @param int $fuzziness
     * @return array
     */
    public function multi_match($fields=[],$query,$operator='or',$type=null,$minimum=null,$boost=null,$analyzer='',$fuzziness=null)
    {

        $params = [

            'multi_match' => array_filter([
                'query' => $query,
                'type' => $type,
                'fields' => $fields,
                'operator' => $operator,
                'analyzer' => $analyzer,
                'minimum_should_match' => $minimum,
                'fuzziness' => $fuzziness,
                'boost' => $boost,
            ])
        ];

        return $params;
    }

    /**
     * @param $field
     * @param $query
     * @param int $boost
     * @param string $analyzer
     * @param int $fuzziness
     * @return array
     */
    public function match_phrase_prefix($field, $query, $boost = null, $analyzer = '', $fuzziness = null)
    {
        $params = [
            'match_phrase_prefix' => [
                $field => array_filter([
                    'query' => $query,
                    'analyzer' => $analyzer,
                    'boost' => $boost,
                    'fuzziness' => $fuzziness
                ])
            ]
        ];

        return $params;
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

    /**
     * @param $field
     * @param $value
     * @param int $boost
     * @param float $cuttoff
     * @param string $low_freq_operator
     * @param string $high_freq_operator
     * @param string $analyzer
     * @return array
     */
    public function common($field,$value,$boost=1,$cuttoff=.1,$low_freq_operator='or',$high_freq_operator='or',$analyzer='standard')
    {
        return [
            'common' => [
                $field => [
                    'query' => $value,
                    'cuttoff_frequency' => $cuttoff,
                    'low_freq_operator' => $low_freq_operator,
                    'high_freq_operator' => $high_freq_operator,
                    'boost' => $boost,
                    'analyzer' => $analyzer,
                ]
            ]
        ];
    }

    /**
     * @param $field
     * @return array
     */
    public function exists($field)
    {
        return [
            'exits' => [
                'field' => $field
            ]
        ];
    }

    /**
     * @param $field
     * @return array
     */
    public function missing($field)
    {
        return [
            'missing' => [
                'field' => $field
            ]
        ];
    }

    /**
     * @param $field
     * @param $value
     * @param int $boost
     * @return array
     */
    public function prefix($field,$value,$boost=1)
    {
        return [
            'prefix' => [
                $field => [
                    'value' => $value,
                    'boost' => $boost
                ]
            ]
        ];
    }

    /**
     * @param $field
     * @param $value
     * @param int $boost
     * @param string $fuzziness
     * @param int $prefix_length
     * @param int $max_exp
     * @return array
     */
    public function fuzzy($field,$value,$boost=1,$fuzziness='AUTO',$prefix_length=0,$max_exp=50)
    {
        return [
            'fuzzy' => [
                $field => [
                    'value' => $value,
                    'boost' => $boost,
                    'fuzziness' => $fuzziness,
                    'prefix_length' => $prefix_length,
                    'max_expansions' => $max_exp
                ]
            ]
        ];
    }

    /**
     * @param $type
     * @param array $values
     * @return array
     */
    public function ids($type,$values=[])
    {
        return [
            'ids' => [
                'type' => $type,
                'values' => $values
            ]
        ];
    }

    /**
     * @param $value
     * @return array
     */
    public function limit($value)
    {
        return [
            'limit' => [
                'value' => $value
            ]
        ];
    }

    /**
     * @param $path
     * @param $query
     * @param string $score_mode
     * @return array
     */
    public function nested($path,$query,$score_mode='avg')
    {
        return [
            'nested' => [
                'path' => $path,
                'query' => $query,
                'score_mode' => $score_mode
            ]
        ];
    }

    /**
     * @param $type
     * @param $query
     * @param string $score_mode
     * @param $min_children
     * @param $max_children
     * @return array
     */
    public function has_child($type,$query,$score_mode='none',$min_children,$max_children)
    {
        $query = [
            'has_child' => [
                'type' => $type,
                'query' => $query,
                'score_mode' => $score_mode
            ]
        ];

        if(!empty($min_children) && is_numeric($min_children)){
            $query['has_child']['min_children'] = $min_children;
        }

        if(!empty($max_children) && is_numeric($max_children)){
            $query['has_child']['min_children'] = $max_children;
        }

        return $query;
    }

    /**
     * @param $type
     * @param $query
     * @param string $score_mode
     * @return array
     */
    public function has_parent($type,$query,$score_mode='none')
    {
        $query = [
            'has_child' => [
                'parent_type' => $type,
                'query' => $query,
                'score_mode' => $score_mode
            ]
        ];

        return $query;
    }



    /**
     * @param array $functions
     * @return array
     */
    public function function_score($functions=[])
    {
        return [
            'function_score' => [
                'functions' => $functions
            ]
        ];
    }

    /**
     * @param $field
     * @param $origin
     * @param $offset
     * @param $scale
     * @param int $weight
     * @return array
     */
    public function gauss($field,$origin,$offset,$scale,$weight=1)
    {

        $args = [
            'origin' => $origin,
            'scale' => $scale
        ];

        if(!empty($offset))
        {
            $args = array_merge($args,['offset'=>$offset]);
        }

        return [
            'gauss' => [
                $field => $args
            ],
            'weight' => $weight
        ];
    }

    /**
     * @param $field
     * @param $top_left
     * @param $bottom_right
     * @return array
     */
    public function geo_bounding_box($field,$top_left,$bottom_right)
    {
        return [
            'geo_bounding_box' => [
                $field => [
                    'top_left' => $top_left,
                    'bottom_right' => $bottom_right
                ]
            ]
        ];
    }

    /**
     * @param $params
     * @param $type
     * @return array
     */
    public function search($params, $type)
    {
        $query = [
            'index' => $this->index,
            'type' => $type,
            'body' => [
                'query' =>  $params
            ]
        ];
        try{
            $search = $this->elastic->search($query);
            return $search;

        }catch(Exception $e)
        {
            \Log::info($e);
        }
    }


}
