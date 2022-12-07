<?php
/**
 * Created by PhpStorm.
 * User: brian@brokerbin.com
 * Date: 6/8/16
 * Time: 8:30 AM
 * License: The MIT License (MIT)
 * Copyright: (c) <Broker Exchange Network>
 */

namespace ElasticBuilder\Query;

/**
 * Class AbstractQuery
 * @package App\Elastic\Builder
 */
abstract class Query
{
    /**
     * @var array aggregations to execute along with the query
     */
    protected array $aggregations = [];

    /**
     * @var array sorts to be passed along with the query
     */
    protected array $sorts = [];

    /**
     * @var array the full query ... it's a bool
     */
    protected array $query;

    /**
     * @var
     */
    protected mixed $model;

    /**
     * @return array
     */
    public function get(): array
    {
        return $this->query;
    }
    
    /**
     * @return array
     */
    public function aggregations(): array
    {
        return $this->aggregations;
    }
    
    /**
     * @return array
     */
    public function sorts(): array
    {
        return $this->sorts;
    }

    /**
     * @param array $agg
     * @return $this
     */
    public function aggregate(array $agg): Query
    {
        $this->aggregations = array_merge($this->aggregations,$agg);
        return $this;
    }

    /**
     * @param array $sort
     * @return $this
     */
    public function sort(array $sort): Query
    {
        $this->sorts = array_merge($this->sorts,$sort);
        return $this;
    }
    
    /**
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->get());
    }
}
