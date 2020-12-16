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
use ElasticBuilder\Query\FunctionScore;

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
     * @var Elastic
     */
    public $elastic;

    /**
     * @var
     */
    public $index;

    /**
     * @var string specify the handler used by \Elastic\Client
     */
    protected $handler;

    /**
     * @var array the client parameters to be used by the client handler
     */
    private $client = [];

    /**
     * @var int the offset of the results window
     */
    private $from = 0;

    /**
     * @var int the size of the results window
     */
    private $size = 10;

    /**
     * Create a new engine instance.
     *
     * @param \Elasticsearch\Client $elastic
     * @param string $index
     * @param string $handler the handler
     * @return void
     */
    public function __construct(Elastic $elastic, $index, $handler = 'curl')
    {
        $this->elastic = $elastic;
        $this->handler = $handler;
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
     * @param int|float $boost
     * @param int $minimum_should_match
     * @return Query\Boolean
     */
    public function boolean($boost=1,$minimum_should_match=1)
    {
        return new Boolean($boost,$minimum_should_match);
    }

    /**
     * @param int|float $boost
     * @return DisMax
     */
    public function dis_max($boost=1)
    {
        return new DisMax($boost);
    }

    /**
     * @param int|float $boost
     * @return ConstantScore
     */
    public function constant_score($boost=1)
    {
        return new ConstantScore($boost);
    }
    
    /**
     * @param int|float|null $boost
     * @param int|float|null $max_boost
     * @param string $boost_mode
     * @param int|float|null $min_score
     * @param string $score_mode
     * @return FunctionScore
     */
    public function function_score(){
        return new FunctionScore($boost=null,$max_boost=null,$boost_mode='multiply',$min_score=null,$score_mode='multiply');
    }

    /**
     * @param int|float $negative_boost
     * @return Boosting
     */
    public function boosting($negative_boost=1)
    {
        return new Boosting($negative_boost);
    }

    /**
     * @param $field
     * @param $value
     * @param int|float|null $boost
     * @return array
     */
    public function term($field,$value,$boost=null)
    {
        if(!is_null($boost))
        {
            return [
                'term' => [
                    $field => [
                        'value' => $value,
                        'boost' => $boost
                    ]
                ]
            ];
        }

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
     * @param int|float|null $boost
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
     * @param int|float|null $boost
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
     * @param int|float|null $boost
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
                'lenient' => true,
            ])
        ];

        return $params;
    }

    /**
     * @param $field
     * @param $query
     * @param int|float|null $boost
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
     * @param int|float $boost
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
     * @param int|float $boost
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
     * @param int|float $boost
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
     * @param string $field the name of the property-field with the location data
     * @param string $distance the length and units used to create the radius
     * @param array|string $origin the location data of the point of origin where the radius begins (center)
     * @param string $distance_type what method is used to determine distance between points (ex: arc, plane)
     * @param string $origin_format how to build the field data in the request (ex: array,geohash,properties,string)
     * @param string $name optional name used to identify the query
     * @param string $validation_method determine how points are considered valid (ex: COERCE,IGNORE_MALFORMED,STRICT)
     * @return array
     */
    public function geo_distance($field,$distance,$origin,$distance_type='arc',$origin_format='array',$name='',$validation_method='STRICT'){

        switch(strtolower($origin_format)){

            case 'properties': {
                $origin_formatted =[
                    'lat' => $origin['lat'],
                    'lon' => $origin['lon']
                ];
                break;
            }
            case 'geohash':
                //note: for geohash -- origin string must be passed as "geohash"

                //no break
            case 'string': {
                //note: for string -- origin string must be passed as "lat,lon"

                $origin_formatted = $origin;

                break;
            }
            case 'array':
                //no break
            default: {
                $origin_formatted = [
                    (double) $origin['lon'],
                    (double) $origin['lat']
                ];
                break;
            }
        }

        $distance_type = strtolower($distance_type);

        switch($distance_type){
            case 'arc':
                //no break
            case 'plane': {
                //do nothing
                break;
            }
            default: {
                //reset to 'arc' if unnavailable option is sent
                $distance_type = 'arc';
                break;
            }
        }

        $validation_method = strtoupper($validation_method);

        switch($validation_method){
            case 'COERCE':
                //no break
            case 'IGNORE_MALFORMED':
                //no break
            case 'STRICT': {
                //do nothing
                break;
            }
            default: {
                //reset to 'arc' if unnavailable option is sent
                $validation_method = 'STRICT';
                break;
            }
        }

        $geo_distance = [
            'geo_distance'=> [
                'distance' => $distance,
                'distance_type' => $distance_type,
                "{$field}" => $origin_formatted,
                'validation_method' => $validation_method
            ]
        ];

        if(!empty($name)){
            $geo_distance['geo_distance']['_name'] = $name;
        }

        return $geo_distance;
    }

    /**
     * Set the offset of the results window
     *
     * @param int $offset the offset of the results window
     */
    public function from($offset = 0)
    {

        if (is_integer($offset)) {

            $this->from = $offset;

        }

    }

    /**
     * Set the result window size
     *
     * @param int $hits the number of hits in the results window
     */
    public function size($hits = 10)
    {

        if (is_integer($hits)) {

            $this->size = $hits;

        }

    }

    /**
     * Set the client parameters
     *
     * @param array $client
     */
    public function client_parameters($client = [])
    {

        if ($this->handler == 'curl') {

            if (empty($client)) {

                $this->client = [
                    'curl' => [
                        CURLOPT_HTTPHEADER => [
                            'Content-type: application/json'
                        ]
                    ]
                ];

            } else {
                if (!empty($client)) {

                    $this->client = $client;

                    $needs_content_type = true;

                    if (!empty($this->client['curl'][CURLOPT_HTTPHEADER]) && is_array($this->client['curl'][CURLOPT_HTTPHEADER])) {

                        foreach ($this->client['curl'][CURLOPT_HTTPHEADER] as $headerParameter) {

                            if (strpos(strtolower($headerParameter), 'content-type:') !== false) {
                                $needs_content_type = false;
                                continue;
                            }

                        }

                    }

                    if ($needs_content_type === true) {

                        $this->client['curl'][CURLOPT_HTTPHEADER][] = 'Content-type: application/json';

                    }
                }
            }
        } //allow for custom client parameters on non-curl handlers
        else {
            if (!empty($client) && is_array($client)) {

                $query['client'] = $client;
            }
        }
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
            'from' => $this->from,
            'size' => $this->size,
            'client' => $this->client,
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