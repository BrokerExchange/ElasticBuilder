<?php
/**
 * Created by PhpStorm.
 * User: brian@brokerbin.com
 * Date: 6/8/16
 * Time: 9:04 AM
 * License: The MIT License (MIT)
 * Copyright: (c) <Broker Exchange Network>
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
    public function query($query)
    {
        $this->query['dis_max']['queries'][] = $query;
    }

    /**
     * @param array $queries
     */
    public function queries($queries=[])
    {
        $this->query['dis_max']['queries'] = $queries;
    }

}