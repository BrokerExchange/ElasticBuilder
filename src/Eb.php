<?php
/**
 * Created by PhpStorm.
 * User: brian@brokerbin.com
 * Date: 6/8/16
 * Time: 2:57 PM
 * License: The MIT License (MIT)
 * Copyright: (c) <Broker Exchange Network>
 */

namespace ElasticBuilder;

use Illuminate\Support\Facades\Facade;

/**
 * Class ElasticQueryBuilderFacade
 * @package ElasticBuilder
 */
class Eb extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'elasticbuilder';
    }
}
