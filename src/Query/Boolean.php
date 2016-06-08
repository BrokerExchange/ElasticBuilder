<?php
/**
 * Created by PhpStorm.
 * User: bmix
 * Date: 6/8/16
 * Time: 6:31 AM
 */

namespace ElasticBuilder\Query;

/**
 * Class ElasticBoolQuery
 * @package App
 */
class Boolean extends Query
{

    /**
     * ElasticBoolQuery constructor.
     */
    public function __construct()
    {
        $this->query = ['bool'=>[]];
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