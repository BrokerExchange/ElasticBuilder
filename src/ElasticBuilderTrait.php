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


trait ElasticBuilderTrait
{
    static function boolean()
    {
        return new Boolean;
    }

    static function dis_max()
    {
        return new DisMax;
    }

    static function agg()
    {
        return new ElasticAggBuilder;
    }
}