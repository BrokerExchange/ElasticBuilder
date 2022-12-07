<?php
/**
 * Created by PhpStorm.
 * User: swahlstrom
 * Date: 12/10/19
 * Time: 8:54 AM
 */

namespace ElasticBuilder\Query;

/**
 * Class FunctionScore
 * @package ElasticBuilder
 */
class FunctionScore extends Query
{
    /**
     * FunctionScore constructor.
     *
     * @param float|int|null $boost boost for the given query
     * @param float|int|null $max_boost the maximum boost allowed to be applied
     * @param string $boost_mode how the function_score score is combined with the query score
     * @param float|int|null $min_score the minimum score threshold for a document to count as a result
     * @param string $score_mode how the function_score score is computed
     */
    public function __construct(float|int|null $boost = null,float|int|null $max_boost = null, string $boost_mode='multiply', float|int|null $min_score = null, string $score_mode = 'multiply')
    {
        $this->query = [
            'function_score'=>[],
        ];

        if(!is_null($boost)){
            $this->query['function_score']['boost'] = $boost;
        }

        if(!is_null($max_boost)){
            $this->query['function_score']['max_boost'] = $max_boost;
        }

        if(!empty($boost_mode)){
            $this->query['function_score']['boost_mode'] = $boost_mode;
        }

        if(!empty($min_score)){
            $this->query['function_score']['min_score'] = $min_score;
        }

        if(!empty($score_mode)){
            $this->query['function_score']['score_mode'] = $score_mode;
        }
    }

    /**
     * @param array $query
     * @return $this
     */
    public function query(array $query = []): FunctionScore
    {
        $this->query['function_score']['query'] = $query;
        return $this;
    }

    /**
     * @param array $filter the function filter which are used to score
     * @param float|int|null $weight the score applied to this function
     * @param string $score_function_name the name of the scoring function used for this filter (when applicable)
     * @param array $score_function_body the body of the scoring function used for this filter (when applicable)
     * @return $this
     */
    public function functions_filter(array $filter, float|int|null $weight = null, string $score_function_name = '', array $score_function_body = [])
    {
        $new_filter = ['filter' => $filter];

        if(!is_null($weight)){
            $new_filter['weight'] = $weight;
        }

        if(!empty($score_function_name)){
            $new_filter[$score_function_name] = $score_function_body;
        }

        $this->query['function_score']['functions'][] = $new_filter;

        return $this;
    }

    /**
     * score via functions which are determined against a distance value
     *
     * @param string $decayFunction the type of decay function to be used for calculation ('gauss'|'exp'|'linear')
     * @param string $field the field used for calculation, the type of this field determines whether origin is required or not by Elasticsearch
     * @param string $scale defines the distance from origin plus the offset at which computed score equals the decay
     * @param mixed $origin note: Required (by Elasticsearch) for Geo and Numeric Field Types
     * @param string $offset if set, the decay function will only begin computing decay functions with distances greater than the offset
     * @param string $decay how documents are scored at the distance provided via scale
     * @param float|int|null $weight the score applied to this function
     * @param string $multiValueMode if more than one origin is given, this value is used to determine the distance ('min'|'max'|'avg'|'sum')
     * @param array $filters additional criteria to filter the decay function by
     * @return FunctionScore
     */
    private function functions_decay(string $decayFunction, string $field, string $scale, string $origin = '', string $offset = '', string $decay = '',  float|int|null $weight = null, string $multiValueMode = 'min', array $filters = []): FunctionScore
    {
        $new_decay = [
            $decayFunction => [
                $field => [
                    'decay' => $decay,
                    'scale' => $scale,
                ],
                'multi_value_mode' => $multiValueMode,
            ]
        ];

        if (!empty($offset)) {
            $new_decay[$decayFunction][$field]['offset'] = $offset;
        }

        if (!empty($origin)) {
            $new_decay[$decayFunction][$field]['origin'] = $origin;
        }

        if(!is_null($weight)){
            $new_decay['weight'] = $weight;
        }

        if (!empty($filters)) {
            $new_decay = array_merge($new_decay, $filters);
        }

        $this->query['function_score']['functions'][] = $new_decay;

        return $this;
    }

    /**
     * a decay function based (a.k.a. normal decay)
     *
     * @param string $field the field used for calculation, the type of this field determines whether origin is required or not by Elasticsearch
     * @param string $scale defines the distance from origin plus the offset at which computed score equals the decay
     * @param mixed $origin note: Required (by Elasticsearch) for Geo and Numeric Field Types
     * @param string $offset if set, the decay function will only begin computing decay functions with distances greater than the offset
     * @param string $decay how documents are scored at the distance provided via scale
     * @param float|int|null $weight the score applied to this function
     * @param string $multiValueMode if more than one origin is given, this value is used to determine the distance ('min'|'max'|'avg'|'sum')
     * @param array $filters additional criteria to filter the gaussian decay function by
     * @return FunctionScore
     */
    public function gauss(string $field, string $scale, mixed $origin = '', string $offset = '0', string $decay = '0.5', float|int|null $weight = null, string $multiValueMode = 'min', array $filters = [])
    {

        return $this->functions_decay('gauss', $field, $scale, $origin, $offset, $decay, $weight, $multiValueMode, $filters);

    }

    /**
     * a decay function based (a.k.a. exponential decay)
     *
     * @param string $field the field used for calculation, the type of this field determines whether origin is required or not by Elasticsearch
     * @param string $scale defines the distance from origin plus the offset at which computed score equals the decay
     * @param mixed $origin note: Required (by Elasticsearch) for Geo and Numeric Field Types
     * @param string $offset if set, the decay function will only begin computing decay functions with distances greater than the offset
     * @param string $decay how documents are scored at the distance provided via scale
     * @param float|int|null $weight the score applied to this function
     * @param string $multiValueMode if more than one origin is given, this value is used to determine the distance ('min'|'max'|'avg'|'sum')
     * @param array $filters additional criteria to filter the exponential decay function by
     * @return FunctionScore
     */
    public function exp(string $field, string $scale, mixed $origin = '', string $offset = '0', string $decay = '0.5', float|int|null $weight = null, string $multiValueMode = 'min', array $filters = []): FunctionScore
    {

        return $this->functions_decay('exp', $field, $scale, $origin, $offset, $decay, $weight, $multiValueMode, $filters);

    }

    /**
     * a decay function based (a.k.a. linear decay)
     *
     * @param string $field the field used for calculation, the type of this field determines whether origin is required or not by Elasticsearch
     * @param string $scale defines the distance from origin plus the offset at which computed score equals the decay
     * @param mixed $origin note: Required (by Elasticsearch) for Geo and Numeric Field Types
     * @param string $offset if set, the decay function will only begin computing decay functions with distances greater than the offset
     * @param string $decay how documents are scored at the distance provided via scale
     * @param float|int|null $weight the score applied to this function
     * @param string $multiValueMode if more than one origin is given, this value is used to determine the distance ('min'|'max'|'avg'|'sum')
     * @param array $filters additional criteria to filter the linear decay function by
     * @return FunctionScore
     */
    public function linear(string $field, string $scale, mixed $origin = '', string $offset = '0', string $decay = '0.5', float|int|null $weight = null, string $multiValueMode = 'min', array $filters = []): FunctionScore
    {

        return $this->functions_decay('linear', $field, $scale, $origin, $offset, $decay, $weight, $multiValueMode, $filters);

    }
}
