<?php
/**
 * Created by PhpStorm.
 * User: sean@brokerbin.com
 * Date: 2/19/20
 * Time: 3:11 PM
 * License: The MIT License (MIT)
 * Copyright: (c) <Broker Exchange Network>
 */

namespace ElasticBuilder;

/**
 * Class Sort
 * @package ElasticBuilder
 */
class Sort
{

    /**
     * @param string $field the field to sort on
     * @param string $order the order of results -- asc (ascending) or desc (descending)
     * @param string $mode when sorting by array values, determinant of which array value is chosen when sorting the respective document (min, max, sum, avg, median)
     * @param string $missing_values what to do with docs which are missing the respective sort field (_last, _first, or a custom value to be used by missing docs as their sort value)
     * @param string $unmapped_type what sort values to emit if the respective field is not mapped
     */
    public function byField(string $field, string $order = 'asc', string $mode = '', string $missing = '', string $unmapped_type = ''): array
    {
        $sort = [
            $field => [
                'order' => $order
            ]
        ];

        if(!empty($mode)){
            $sort[$field]['mode'] = $mode;
        }

        if(!empty($missing)){
            $sort[$field]['missing'] = $missing;
        }

        if(!empty($unmapped_type)){
            $sort[$field]['unmapped_type'] = $unmapped_type;
        }

        return $sort;

    }

    /**
     * @param string $order the order of results -- asc (ascending) or desc (descending)
     * @return array
     */
    public function byScore( string $order = 'desc' ): array
    {
        return $this->byField('_score', $order);
    }

    /**
     * @param string $field the name of the property-field with the location data
     * @param array $origin the point of origin to begin calculation distance
     * @param string $unit the unit of measure to determine distance by (ex: mi, km, m)
     * @param string $order the order of results -- asc (ascending) or desc (descending)
     * @param string $distance_type how to measure the distance (arc or plane)
     * @param string $mode how to handle a field with multiple geo points
     * @param bool $ignore_unmapped should the unmapped field be treated as a missing value?
     * @param string $origin_format how to build the field data in the request (ex: array,geohash,multiple_reference_points,properties,string)
     */
    public function byGeoDistance(string $field, array $origin, string $unit = 'm', string $order = 'asc', string $mode = 'min', string $distance_type = 'arc', bool $ignore_unmapped = false, string $origin_format = 'array'): array
    {
        //options which are handled by "byField" (a.k.a. not unique to _geo_distance sorting)

        $mode = strtolower($mode);

        switch($mode){
            case 'avg':
                //no break
            case 'max':
                //no break
            case 'median':
                //no break
            case 'min': {
                //do nothing
            }
            default: {
                $mode = 'min';
            }
        }

        $order = strtolower($order);

        switch($order){
            case 'asc':
                //no break
            case 'max':
                //no break
            case 'median':
                //no break
            case 'min': {
                //do nothing
            }
            default: {
                $mode = 'min';
            }
        }

        //reset $ignore_unmapped back to default if non-boolean is passed
        if(!is_bool($ignore_unmapped)){
            $ignore_unmapped = false;
        }

        $sort = $this->byField('_geo_distance',$order,$mode,'');

        //options which are unique to geo_distance sorting

        switch(strtolower($origin_format)){

            case 'properties': {
                $origin_formatted =[
                    'lat' => $origin['lat'],
                    'lon' => $origin['lon']
                ];
                break;
            }
            case 'geohash':
                //note: for geohash -- origin string must be passed as "geohash"

                //no break
            case 'string': {
                //note: for string -- origin string must be passed as "lat,lon"

                $origin_formatted = $origin;

                break;
            }
            case 'multiple_reference_points': {

                $origin_formatted = [
                    $origin
                ];
                break;
            }
            case 'array':
                //no break
            default: {
                $origin_formatted = [
                    (double) $origin['lon'],
                    (double) $origin['lat']
                ];
                break;
            }
        }

        $distance_type = strtolower($distance_type);

        switch($distance_type){
            case 'arc':
                //no break
            case 'plane': {
                //do nothing
                break;
            }
            default: {
                //reset to 'arc' if unnavailable option is sent
                $distance_type = 'arc';
                break;
            }
        }

        $unit = strtolower($unit);

        switch ($unit) {
            case 'cm':
                //no break
            case 'centimeters':
                //no break
            case 'ft':
                //no break
            case 'feet':
                //no break
            case 'in':
                //no break
            case 'inch':
                //no break
            case 'km':
                //no break
            case 'kilometers':
                //no break
            case 'm':
                //no break
            case 'meters':
                //no break
            case 'mi':
                //no break
            case 'miles':
                //no break
            case 'mm':
                //no break
            case 'millimeters':
                //no break
            case 'nmi':
                //no break
            case 'nauticalmiles':
                //no break
            case 'yards':
                //no break
            case 'yd':{
                //do nothing
                break;
            }

            case 'nm': {
                $unit = 'NM';
                break;
            }

            default: {
                $unit = 'm';
            }
        }

        $sort['_geo_distance'][$field] = $origin_formatted;
        $sort['_geo_distance']['distance_type'] = $distance_type;
        $sort['_geo_distance']['unit'] = $unit;

        return $sort;
    }

}
