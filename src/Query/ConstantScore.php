<?php
/**
 * Created by PhpStorm.
 * User: bmix
 * Date: 6/9/16
 * Time: 11:37 AM
 */

namespace ElasticBuilder\Query;

/**
 * Class ConstantScore
 * @package ElasticBuilder
 */
class ConstantScore extends Query
{
    /**
     * ConstantScore constructor.
     * @param int|float|null $boost
     */
    public function __construct($boost=1)
    {
        $this->query = ['constant_score'=>['boost'=>$boost]];
    }

    /**
     * @param $filter
     * @return $this
     */
    public function filter($filter)
    {
        $this->query['constant_score']['filter'][] = $filter;
        return $this;
    }
}
