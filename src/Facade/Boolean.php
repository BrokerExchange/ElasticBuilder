<?php
/**
 * Created by PhpStorm.
 * User: bmix
 * Date: 6/8/16
 * Time: 2:57 PM
 */

namespace ElasticBuilder\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Elasticquent\ElasticquentServiceProvider
 */
class Boolean extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'elasticbuilder.boolean';
    }
}
