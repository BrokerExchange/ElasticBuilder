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
     * @var
     */
    protected $aggregations = []; //aggregations to execute along with the query

    /**
     * @var array
     */
    protected $query; // the full query ... it's a bool

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
     * @param $agg
     * @return $this
     */
    public function aggregate(Array $agg)
    {
        $this->aggregations = array_merge($this->aggregations,$agg);
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