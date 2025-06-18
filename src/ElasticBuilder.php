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

use ElasticBuilder\Query\Boolean as QueryBoolean;
use ElasticBuilder\Query\Boosting;
use ElasticBuilder\Query\DisMax;
use ElasticBuilder\Query\ConstantScore;
use ElasticBuilder\Query\FunctionScore;

use ElasticBuilder\Query\Query;
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
    public Elastic $elastic;

    /**
     * @var
     */
    public string $index;

    /**
     * @var string specify the handler used by \Elastic\Client
     */
    protected string $handler;

    /**
     * @var array the client parameters to be used by the client handler
     */
    private array $client = [];

    /**
     * @var int the offset of the results window
     */
    private int $from = 0;

    /**
     * @var int the size of the results window
     */
    private int $size = 10;

    /**
     * Create a new engine instance.
     *
     * @param Elastic $elastic
     * @param string $index
     * @param string $handler the handler
     * @return void
     */
    public function __construct(Elastic $elastic, string $index, string $handler = 'curl')
    {
        $this->elastic = $elastic;
        $this->handler = $handler;
        $this->index = $index;
    }


    /**
     * @return Aggregation
     */
    public function agg(): Aggregation
    {
        return new Aggregation;
    }

    /**
     * note: QueryBoolean is used here because "Boolean" is a reserved word in PHP, the interpreter was "having a fit"
     *
     * @param float|int $boost
     * @param int $minimum_should_match
     * @return QueryBoolean
     */
    public function boolean(float|int $boost = 1, int $minimum_should_match = 1): QueryBoolean
    {
        return new QueryBoolean($boost,$minimum_should_match);
    }

    /**
     * @param float|int $boost
     * @return DisMax
     */
    public function dis_max(float|int $boost = 1): DisMax
    {
        return new DisMax($boost);
    }

    /**
     * @param float|int $boost
     * @return ConstantScore
     */
    public function constant_score(float|int $boost = 1): ConstantScore
    {
        return new ConstantScore($boost);
    }
    
    /**
     * @param float|int|null $boost
     * @param float|int|null $max_boost
     * @param string $boost_mode
     * @param float|int|null $min_score
     * @param string $score_mode
     * @return FunctionScore
     */
    public function function_score(float|int|null $boost = null, float|int|null $max_boost = null, string $boost_mode='multiply', float|int|null $min_score = null, string $score_mode = 'multiply'): FunctionScore
    {
        return new FunctionScore($boost, $max_boost, $boost_mode, $min_score, $score_mode);
    }

    /**
     * @param float|int $negative_boost
     * @return Boosting
     */
    public function boosting(float|int $negative_boost = 1): Boosting
    {
        return new Boosting($negative_boost);
    }

    /**
     * @param string $field
     * @param bool|string $value
     * @param float|int|null $boost
     * @return array
     */
    public function term(string $field, bool|string $value, float|int|null $boost = null): array
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
     * @param string $field
     * @param array $values
     * @return array
     */
    public function terms(string $field, array $values = []): array
    {
        return [
            'terms' => [
                $field => $values
            ]
        ];
    }

    /**
     * @param string $field
     * @param array $ranges
     * @param float|int|null $boost
     * @return array
     */
    public function range(string $field, array $ranges = [], float|int|null $boost = null): array
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
     * @param string $field
     * @param string $query
     * @param string $operator
     * @param int|null $minimum
     * @param float|int|null $boost
     * @param string $analyzer
     * @param int|null $fuzziness
     * @return array
     */
    public function match(
        string $field,
        string $query,
        string $operator='or', 
        int|string|null $minimum=null, 
        float|int|null $boost=null, 
        string $analyzer='', 
        int|null $fuzziness=null
    ): array
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
     * @param string $query
     * @param string $operator
     * @param string|null $type
     * @param int|null $minimum
     * @param float|int|null $boost
     * @param string $analyzer
     * @param int|null $fuzziness
     * @return array
     */
    public function multi_match(
        array $fields = [],
        string $query = '',
        string $operator = 'or',
        string $type = null,
        int|string|null $minimum = null,
        float|int|null $boost = null,
        string $analyzer = '',
        int|string|null $fuzziness = null
    ): array
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
     * @param string $field
     * @param string $query
     * @param float|int|null $boost
     * @param string $analyzer
     * @param int|null $fuzziness
     * @return array
     */
    public function match_phrase_prefix(
        string $field, 
        string $query, 
        float|int|null $boost = null, 
        string $analyzer = '', 
        int|string|null $fuzziness = null
    ): array
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
    public function match_all(): array
    {
        return [
            'match_all' => []
        ];
    }

    /**
     * @param string $field
     * @param string $value
     * @param float|int $boost
     * @param float $cuttoff
     * @param string $low_freq_operator
     * @param string $high_freq_operator
     * @param string $analyzer
     * @return array
     */
    public function common(
        string $field, 
        string $value, 
        float|int $boost = 1, 
        float $cuttoff = .1, 
        string $low_freq_operator='or', 
        string $high_freq_operator='or', 
        string $analyzer='standard'
    ): array
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
     * @param string $field
     * @return array
     */
    public function exists(string $field): array
    {
        return [
            'exits' => [
                'field' => $field
            ]
        ];
    }

    /**
     * @param string $field
     * @return array
     */
    public function missing(string $field): array
    {
        return [
            'missing' => [
                'field' => $field
            ]
        ];
    }

    /**
     * @param string $field
     * @param string $value
     * @param float|int $boost
     * @return array
     */
    public function prefix(string $field, string $value, float|int $boost = 1)
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
     * @param string $field
     * @param string $value
     * @param float|int $boost
     * @param string $fuzziness
     * @param int $prefix_length
     * @param int $max_exp
     * @return array
     */
    public function fuzzy(
        string $field, 
        string $value, 
        float|int $boost = 1, 
        int|string $fuzziness='AUTO', 
        int $prefix_length=0, 
        int $max_exp=50
    ): array
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
     * @param string $type
     * @param array $values
     * @return array
     */
    public function ids(string $type, array $values = []): array
    {
        return [
            'ids' => [
                'type' => $type,
                'values' => $values
            ]
        ];
    }

    /**
     * @param string $value
     * @return array
     */
    public function limit(string $value): array
    {
        return [
            'limit' => [
                'value' => $value
            ]
        ];
    }

    /**
     * @param string $path
     * @param array $query
     * @param string $score_mode
     * @return array
     */
    public function nested(string $path, array $query, string $score_mode = 'avg'): array
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
     * @param string $type
     * @param array $query
     * @param string $score_mode
     * @param int $min_children
     * @param int $max_children
     * @return array
     */
    public function has_child(string $type, array $query, string $score_mode='none', int $min_children = 0, int $max_children = 0): array
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
     * @param string $type
     * @param array $query
     * @param string $score_mode
     * @return array
     */
    public function has_parent(string $type, array $query, string $score_mode='none'): array
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
     * @param string $field
     * @param array|float|string $top_left
     * @param array|float|string $bottom_right
     * @return array
     */
    public function geo_bounding_box(string $field, array|float|string $top_left, array|float|string $bottom_right): array
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
     * @param array $origin the location data of the point of origin where the radius begins (center)
     * @param string $distance_type what method is used to determine distance between points (ex: arc, plane)
     * @param string $origin_format how to build the field data in the request (ex: array,geohash,properties,string)
     * @param string $name optional name used to identify the query
     * @param string $validation_method determine how points are considered valid (ex: COERCE,IGNORE_MALFORMED,STRICT)
     * @return array
     */
    public function geo_distance(string $field, string $distance, array $origin, string $distance_type='arc', string $origin_format='array', string $name='', string $validation_method='STRICT'): array
    {

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
                //reset to 'arc' if unavailable option is sent
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
                //reset to 'arc' if unavailable option is sent
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
     * @return void
     */
    public function from(int $offset = 0): void
    {

        if (is_integer($offset)) {

            $this->from = $offset;

        }

    }

    /**
     * Set the result window size
     *
     * @param int $hits the number of hits in the results window
     * @return void
     */
    public function size(int $hits = 10): void
    {

        if (is_integer($hits)) {

            $this->size = $hits;

        }

    }

    /**
     * Set the client parameters
     *
     * @param array $client
     * @return void
     */
    public function client_parameters(array $client = []): void
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
     * @param array $params
     * @param string $type
     * @return array
     */
    public function search(array $params, string $type): array
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

        }catch(\Exception $e)
        {
            \Log::info($e);
        }
    }


}
