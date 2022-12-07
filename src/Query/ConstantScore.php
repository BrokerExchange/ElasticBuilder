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
     * @param float|int|null $boost
     */
    public function __construct(float|int|null $boost = 1)
    {
        $this->query = [
            'constant_score' => [
                'boost' => $boost
            ]
        ];
    }

    /**
     * @param array $filter
     * @return $this
     */
    public function filter(array $filter): ConstantScore
    {
        $this->query['constant_score']['filter'] = array_merge_recursive(($this->query['constant_score']['filter'] ?? []), $filter);
        return $this;
    }
}
