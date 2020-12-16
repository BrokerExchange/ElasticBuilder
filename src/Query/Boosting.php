<?php
/**
 * Created by PhpStorm.
 * User: bmix
 * Date: 6/9/16
 * Time: 11:46 AM
 */

namespace ElasticBuilder\Query;

/**
 * Class Boosting
 * @package ElasticBuilder\Query
 */
class Boosting
{
    /**
     * Boosting constructor.
     * @param int $negative_boost
     */
    public function __construct($negative_boost=1)
    {
        $this->query = ['boosting'=>['negative_boost'=>$negative_boost]];
    }

    /**
     * @param $query
     * @return $this
     */
    public function positive($query)
    {
        $this->query['boosting']['positive'] = array_merge_recursive(($this->query['boosting']['positive'] ?? []), $query);
        return $this;
    }

    /**
     * @param $query
     * @return $this
     */
    public function negative($query)
    {
        $this->query['boosting']['negative'] = array_merge_recursive(($this->query['boosting']['negative'] ?? []), $query);
        return $this;
    }
}