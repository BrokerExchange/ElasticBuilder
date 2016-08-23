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
     * @var
     */
    protected $model;

    /**
     * @return mixed
     */
    public function get()
    {

        if(
            is_object($this->model) &&
            is_subclass_of($this->model,'Illuminate\Database\Eloquent\Model')
        ){

            return $this->model->searchByQuery($this->query,$this->aggregations,$source=null,$this->size,$this->offset(),$this->sort());

        }

        return $this->query;
    }

    /**
     * @param $limit
     * @return array
     */
    public function paginate($limit)
    {
        if(
            is_object($this->model) && 
            is_subclass_of($this->model,'Illuminate\Database\Eloquent\Model')
        ){

            $this->size = $limit;
            $results = $this->model->searchByQuery($this->query,$this->aggregations,$source=null,$this->size,$this->offset(),$this->sort());
            return $results->paginate($limit);

        }

        return $this->query;
    }

    /**
     * @param $model
     */
    public function setModel($model)
    {
        $this->model = $model;
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
    public function aggregate(Array $agg)
    {
        array_merge($this->aggregations,$agg);
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