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
abstract class Boolean extends Query
{
    /**
     * @var
     */
    protected $shoulds; //the shoulds portion of the bool query

    /**
     * @var
     */
    protected $musts; //the musts portion of the bool query

    /**
     * @var
     */
    protected $filters; //the filters section of the bool query

    /**
     * ElasticBoolQuery constructor.
     */
    public function __construct()
    {
        $this->query = ['bool'=>[]];
    }

    /**
     * @param $query
     */
    public function addMust($query)
    {
        $this->query['bool']['must'][] = $query;
    }

    /**
     * @param $filter
     */
    public function addFilter($filter)
    {
        $this->query['bool']['filter'][] = $filter;
    }

    /**
     * @param $query
     */
    public function addShould($query)
    {
        $this->query['bool']['should'][] = $query;
    }

}