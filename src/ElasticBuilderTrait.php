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

use ElasticBuilder\Query\Boolean;
use ElasticBuilder\Query\Boosting;
use ElasticBuilder\Query\ConstantScore;
use ElasticBuilder\Query\DisMax;

/**
 * Class ElasticBuilderTrait
 * @package ElasticBuilder
 */
trait ElasticBuilderTrait
{
    /**
     * @param int $boost
     * @param int $minimum_should_match
     * @return Boolean
     */
    static function boolean($boost=1,$minimum_should_match=1)
    {
        return new Boolean($boost,$minimum_should_match);
    }

    /**
     * @param int $boost
     * @return DisMax
     */
    static function dis_max($boost=1)
    {
        return new DisMax($boost);
    }

    /**
     * @return Aggregation
     */
    static function agg()
    {
        return new Aggregation;
    }

    /**
     * @param int $negative_boost
     * @return Boosting
     */
    static function boosting($negative_boost=1)
    {
        return new Boosting($negative_boost);
    }

    /**
     * @param int $boost
     * @return ConstantScore
     */
    static function constant_score($boost=1)
    {
        return new ConstantScore($boost);
    }
}