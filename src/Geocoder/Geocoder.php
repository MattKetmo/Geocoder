<?php

/**
 * This file is part of the Geocoder package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

namespace Geocoder;

use Geocoder\Model\Address;

/**
 * @author William Durand <william.durand1@gmail.com>
 */
interface Geocoder
{
    /**
     * Version
     */
    const VERSION = '3.0.0-dev';

    /**
     * Geocodes a given value.
     *
     * @param string $value A value to geocode.
     *
     * @return Address[]
     */
    public function geocode($value);

    /**
     * Reverses geocode given latitude and longitude values.
     *
     * @param double $latitude  Latitude.
     * @param double $longitude Longitude.
     *
     * @return Address[]
     */
    public function reverse($latitude, $longitude);
}
