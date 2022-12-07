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
    protected array $queries; //the queries portion of the dis_max query

    /**
     * DisMax constructor.
     * @param int $boost
     */
    public function __construct(int $boost = 1)
    {
        $this->query = [
            'dis_max' => [
                'boost' => $boost
            ]
        ];
    }

    /**
     * @param array $query
     * @return void
     */
    public function query(array $query): void
    {
        $this->query['dis_max']['queries'] = array_merge_recursive(($this->query['dis_max']['queries'] ?? []), $query);
    }

    /**
     * @param array $queries
     * @return void
     */
    public function queries(array $queries=[]): void
    {
        $this->query['dis_max']['queries'] = $queries;
    }

}