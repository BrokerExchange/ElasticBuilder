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
    protected $sort = [["_score"=>"desc"]]; //sorting options

    /**
     * @var int
     */
    protected $size = 10; //per page

    /**
     * @var array
     */
    protected $query; // the full query ... it's a bool

    /**
     * @var int
     */
    protected $page = 1;

    /**
     * @var string
     */
    protected $index = '';

    /**
     * @var string
     */
    protected $type = '';

    /**
     * @return mixed
     */
    public function get()
    {

        if(!empty($this->index) && !empty($this->type)){
            $params['index'] = $this->index;
            $params['type'] = $this->type;
            $params['body']['query'] = $this->query;
            if(count($this->aggregations)) $params['body']['aggregations'] = $this->aggregations;
            $params['body']['sort'] = $this->sort;
            $params['size'] = $this->size;
            $params['from'] = $this->offset();

            $results = \Es::search($params);

            $this->hits = collect($results['hits']['hits']);

            return $this->hits;


        }

        return $this->query;
    }
    
    public function paginate($perPage,$page)
    {
        $this->page=$page;
        $this->size=$perPage;
        if(!empty($this->index) && !empty($this->type)){
            $params['index'] = $this->index;
            $params['type'] = $this->type;
            $params['body']['query'] = $this->query;
            if(count($this->aggregations)) $params['body']['aggregations'] = $this->aggregations;
            $params['body']['sort'] = $this->sort;
            $params['size'] = $this->size;
            $params['from'] = $this->offset();

            $results = \Es::search($params);
            
            //TODO: new up pagination class and return it
            
            $this->hits = collect($results['hits']['hits']);

            return $this->hits;


        }
    }

    /**
     * @param $index
     */
    public function setIndex($index)
    {

        $this->index = $index;
    }

    /**
     * @param $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
    
    /**
     * @return mixed
     */
    public function aggregations()
    {
        return $this->aggregations;
    }

    /**
     * @return int
     */
    public function size()
    {
        return $this->size;
    }

    /**
     * @return mixed
     */
    public function offset()
    {
        return ($this->page-1)*$this->size;
    }

    /**
     * @return array
     */
    public function sort()
    {
        return $this->sort;
    }

    /**
     * @param $agg
     * @return $this
     */
    public function aggregate($agg)
    {
        $this->aggregations = $agg;
        return $this;
    }

    /**
     * @param $sort
     * @return $this
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
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