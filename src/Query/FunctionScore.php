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
     * @param int|float|null $boost boost for the given query
     * @param int|float|null $max_boost the maximum boost allowed to be applied
     * @param string $boost_mode how the function_score score is combined with the query score
     * @param int|float|null $min_score the minimum score threshold for a document to count as a result
     * @param string $score_mode how the function_score score is computed
     */
    public function __construct($boost=null,$max_boost=null,$boost_mode='multiply',$min_score=null,$score_mode='multiply')
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
    public function query($query=[]){
        $this->query['function_score']['query'] = $query;
        return $this;
    }

    /**
     * @param array $filter the function filter which are used to score
     * @param int|float|null $weight the score applied to this function
     * @param string $score_function_name the name of the scoring function used for this filter (when applicable)
     * @param array $score_function_body the body of the scoring function used for this filter (when applicable)
     * @return $this
     */
    public function functions_filter($filter,$weight=null,$score_function_name='',$score_function_body=[])
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
}
