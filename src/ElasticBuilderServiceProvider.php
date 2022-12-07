<?php

/**
 * Created by PhpStorm.
 * User: brian@brokerbin.com, jpage@brokerbin.com
 * Date: 6/8/16
 * Time: 8:17 PM
 * License: The MIT License (MIT)
 * Copyright: (c) <Broker Exchange Network>
 */

namespace ElasticBuilder;

use Illuminate\Support\ServiceProvider;
use Elasticsearch\ClientBuilder as Builder;

class ElasticBuilderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind('elasticbuilder', function() {
            return new ElasticBuilder(Builder::create()
                ->setHosts(config('eb.elasticsearch.hosts'))
                ->build(),
                config('eb.elasticsearch.index')
            );
        });

    }
}
