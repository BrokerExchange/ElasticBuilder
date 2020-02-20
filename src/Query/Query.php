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
    protected $aggregations = [];

    /**
     * @var array sorts to be passed along with the query
     */
    protected $sorts = [];

    /**
     * @var array the full query ... it's a bool
     */
    protected $query;

    /**
     * @var
     */
    protected $model;

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->query;
    }
    
    /**
     * @return mixed
     */
    public function aggregations()
    {
        return $this->aggregations;
    }
    
    /**
     * @return mixed
     */
    public function sorts()
    {
        return $this->sorts;
    }

    /**
     * @param $agg
     * @return $this
     */
    public function aggregate(Array $agg)
    {
        $this->aggregations = array_merge($this->aggregations,$agg);
        return $this;
    }

    /**
     * @param $sort
     * @return $this
     */
    public function sort(Array $sort)
    {
        $this->sorts = array_merge($this->sorts,$sort);
        return $this;
    }
    
    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->get());
    }
}
