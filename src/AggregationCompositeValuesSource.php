<?php
/**
 * Created by PhpStorm.
 * User: sean@brokerbin.com
 * Date: 10/16/2020
 * Time: 10:15 AM
 * License: The MIT License (MIT)
 * Copyright: (c) <Broker Exchange Network>
 */

namespace ElasticBuilder;

/**
 * Class AggregationValueSource
 * @package ElasticBuilder
 */
class AggregationCompositeValuesSource
{

    /**
     * A simple version of the date_histogram aggregation, for use in composite aggregations as a values source
     *
     * @param string $namespace the namespace of the value source
     * @param string $field the field to be used for the values
     * @param string $interval the interval to be used to define the values
     * @param string $format the format of field key in the output of the composite aggregation,
     *      for accepted formats see:
     *  https://www.elastic.co/guide/en/elasticsearch/reference/6.8/search-aggregations-bucket-daterange-aggregation.html#date-format-pattern
     * @param string $time_zone the time zone with which to adjust results
     * @param string $order the sort order of the source values
     * @param bool $missing_bucket determines inclusion of documents which are missing the respective source value
     */
    public function date_histogram(string $namespace, string $field, string $interval, string $format = '', string $time_zone = '', string $order = 'asc', bool $missing_bucket = false)
    {
        $date_histogram = [
            $namespace => [
                'date_histogram' => [
                    'field' => $field,
                    'interval' => $interval
                ]
            ]
        ];

        if(!empty($format)){
            $date_histogram[$namespace]['date_histogram']['format'] = $format;
        }

        if(!empty($order)){
            $date_histogram[$namespace]['date_histogram']['order'] = $order;
        }

        if(!empty($missing_bucket) || is_int($missing_bucket)){
            $date_histogram[$namespace]['date_histogram']['missing_bucket'] = $missing_bucket;
        }

        if(!empty($time_zone)){
            $date_histogram[$namespace]['date_histogram']['time_zone'] = $time_zone;
        }

        return $date_histogram;
    }

    /**
     * A simple version of the histogram aggregation, for use in composite aggregations as a values source
     *
     * @param string $namespace the namespace of the value source
     * @param string $field the field to be used for the values
     * @param string $interval the interval to be used to define the values
     * @param string $order the sort order of the source values
     * @param bool $missing_bucket determines inclusion of documents which are missing the respective source value
     * @return array
     */
    public function histogram(string $namespace, string $field, string $interval, string $order = 'asc', bool $missing_bucket = false): array
    {

        $histogram = [
            $namespace => [
                'histogram' => [
                    'field' => $field,
                    'interval' => $interval
                ]
            ]
        ];

        if(!empty($order)){
            $histogram[$namespace]['histogram']['order'] = $order;
        }

        if(!empty($missing_bucket) || is_int($missing_bucket)){
            $histogram[$namespace]['histogram']['missing_bucket'] = $missing_bucket;
        }

        return $histogram;
    }

    /**
     * A simple version of terms aggregation, for use in composite aggregations as a values source
     *
     * @param string $namespace the namespace of the value source
     * @param string $field the field to be used for the values
     * @param string $order the sort order of the source values
     * @param bool $missing_bucket determines inclusion of documents which are missing the respective source value
     * @return array
     */
    public function terms(string $namespace, string $field, string $order = 'asc', bool $missing_bucket = false): array
    {
        $terms = [
            $namespace => [
                'terms' => [
                    'field' => $field
                ]
            ]
        ];

        if(!empty($order)){
            $terms[$namespace]['terms']['order'] = $order;
        }

        if(!empty($missing_bucket) || is_int($missing_bucket)){
            $terms[$namespace]['terms']['missing_bucket'] = $missing_bucket;
        }

        return $terms;
    }
}
