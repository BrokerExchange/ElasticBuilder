<?php

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
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
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
