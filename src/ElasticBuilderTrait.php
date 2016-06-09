<?php
/**
 * Created by PhpStorm.
 * User: bmix
 * Date: 6/9/16
 * Time: 7:31 AM
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