<?php
/**
 * Created by PhpStorm.
 * User: bmix
 * Date: 6/8/16
 * Time: 8:30 AM
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
     * @return array
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
     */
    public function aggregate($agg)
    {
        $this->aggregations = $agg;
    }

    /**
     * @param $sort
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
    }
    
    public function toJson()
    {
        return json_encode($this->get());
    }
}