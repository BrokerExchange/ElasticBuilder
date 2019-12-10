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
     * @return Aggregation
     */
    public function agg()
    {
        return new Aggregation;
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
     * @param int $boost
     * @return ConstantScore
     */
    public function constant_score($boost=1)
    {
        return new ConstantScore($boost);
    }
}
