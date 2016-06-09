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
use ElasticBuilder\Query\DisMax;

/**
 * Class ElasticBuilderTrait
 * @package ElasticBuilder
 */
trait ElasticBuilderTrait
{
    /**
     * @return Boolean
     */
    static function boolean()
    {
        return new Boolean;
    }

    /**
     * @return DisMax
     */
    static function dis_max()
    {
        return new DisMax;
    }

    /**
     * @return Aggregation
     */
    static function agg()
    {
        return new Aggregation;
    }
}