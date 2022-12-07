<?php
/**
 * Created by PhpStorm.
 * User: brian@brokerbin.com
 * Date: 6/9/16
 * Time: 7:31 AM
 * License: The MIT License (MIT)
 * Copyright: (c) <Broker Exchange Network>
 */

namespace ElasticBuilder;

use ElasticBuilder\Query\Boolean as QueryBoolean;
use ElasticBuilder\Query\Boosting;
use ElasticBuilder\Query\ConstantScore;
use ElasticBuilder\Query\DisMax;
use ElasticBuilder\Query\FunctionScore;
use ElasticBuilder\Query\Query;

/**
 * Class ElasticBuilderTrait
 * @package ElasticBuilder
 */
trait ElasticBuilderTrait
{
    /**
     * note: QueryBoolean is used here because "Boolean" is a reserved word in PHP, the interpreter was "having a fit"
     *
     * @param float|int $boost
     * @param int $minimum_should_match
     * @return QueryBoolean
     */
    public function boolean(float|int $boost=1, int $minimum_should_match = 1): QueryBoolean
    {
        return new QueryBoolean($boost,$minimum_should_match);
    }

    /**
     * @param float|int $boost
     * @return DisMax
     */
    public function dis_max(float|int $boost = 1): Dismax
    {
        return new DisMax($boost);
    }

    /**
     * @return Aggregation
     */
    public function agg(): Aggregation
    {
        return new Aggregation;
    }
    
    /**
     * @return AggregationCompositeValuesSource
     */
    public function aggCVS(): AggregationCompositeValuesSource
    {
        return new AggregationCompositeValuesSource;
    }
    
    /**
     * @return Sort
     */
    public function sort(): Sort
    {
        return new Sort;
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
     * @param float|int $boost
     * @return ConstantScore
     */
    public function constant_score(float|int $boost = 1)
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
    public function function_score(float|int|null $boost=null, float|int|null $max_boost = null, string $boost_mode = 'multiply', float|int|null $min_score=null, string $score_mode = 'multiply'): FunctionScore
    {
        return new FunctionScore($boost,$max_boost,$boost_mode,$min_score,$score_mode);
    }
}
