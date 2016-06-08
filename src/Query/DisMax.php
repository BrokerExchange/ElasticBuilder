<?php
/**
 * Created by PhpStorm.
 * User: bmix
 * Date: 6/8/16
 * Time: 9:04 AM
 */

namespace ElasticBuilder\Query;

/**
 * Class DisMaxQuery
 * @package App\Elastic\Builder
 */
class DisMax extends Query
{
    /**
     * @var
     */
    protected $queries; //the queries portion of the dis_max query

    /**
     * ElasticBoolQuery constructor.
     */
    public function __construct()
    {
        $this->query = ['dis_max'=>[]];
    }

    /**
     * @param $query
     */
    public function addQuery($query)
    {
        $this->query['dis_max']['query'][] = $query;
    }

}