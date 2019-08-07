<?php
/**
 * Created by PhpStorm.
 * User: brian@brokerbin.com
 * Date: 6/8/16
 * Time: 6:31 AM
 * License: The MIT License (MIT)
 * Copyright: (c) <Broker Exchange Network>
 */

namespace ElasticBuilder\Query;

/**
 * Class ElasticBoolQuery
 * @package App
 */
class Boolean extends Query
{

    /**
     * Boolean constructor.
     * @param int $boost
     * @param int $minimum_should_match
     */
    public function __construct($boost=1,$minimum_should_match=1)
    {
        $this->query = ['bool'=>['boost'=>$boost,'minimum_should_match'=>$minimum_should_match]];
    }

    /**
     * @param $query
     * @return $this
     */
    public function must($query)
    {
        $this->query['bool']['must'][] = $query;
        return $this;
    }

    /**
     * @param $query
     * @return $this
     */
    public function must_not($query)
    {
        $this->query['bool']['must_not'][] = $query;
        return $this;
    }

    /**
     * @param $filter
     * @return $this
     */
    public function filter($filter)
    {
        $this->query['bool']['filter'][] = $filter;
        return $this;
    }

    /**
     * @param $query
     * @return $this
     */
    public function should($query)
    {
        $this->query['bool']['should'][] = $query;
        return $this;
    }

}
