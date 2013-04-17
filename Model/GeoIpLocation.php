<?php
/**
 * GeoIP Location
 *
 * Model class for finding a location based on an IP Address using the MaxMind
 * GeoIP database and the PEAR Net_GeoIP package.
 *
 * PHP version 5
 *
 * LICENSE: This source file is subject to the MIT License that is available
 * through the world-wide-web at the following URI:
 * http://www.opensource.org/licenses/mit-license.php.
 *
 * @author     Robert Love <robert@pollenizer.com>
 * @copyright  Copyright 2011, Pollenizer (http://pollenizer.com/)
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @version    1.0
 * @since      File available since Release 2.0
 * @see        http://www.maxmind.com/app/ip-location
 * @see        http://pear.php.net/package/Net_GeoIP/
 * @see        http://pear.php.net/manual/en/package.networking.net-geoip.lookuplocation.php
 */

App::uses('AppModel', 'Model');

/**
 * Include PEAR Net_GeoIP class
 */
App::import('GeoIp.Lib', 'GeoIP');

/**
 * GeoIP Location class
 */
class GeoIpLocation extends AppModel
{
    /**
     * Container for data returned by the find method
     *
     * @var array
     * @access public
     */
    public $data = array();

    /**
     * The name of the model
     *
     * @var string
     * @access public
     */
    public $name = 'GeoIpLocation';

    public $useTable = false;

    /**
     * Find
     *
     * @param string $ipAddr The IP Address for which to find the location.
     * @return mixed Array of location data or null if no location found.
     * @access public
     */
    public function find($type = 'first', $query = array())
    {
        $ipAddr = $type;
        $GeoIp = Net_GeoIP::getInstance(dirname(dirname(__FILE__)) . DS . 'data' . DS . 'GeoIP.dat');
        try {
            $location = $GeoIp->lookupLocation($ipAddr);
            if (!empty($location)) {
                $this->data = array($this->name => array(
                    'country_code' => $location->countryCode,
                    'country_code_3' => $location->countryCode3,
                    'country_name' => $location->countryName,
                    'region' => $location->region,
                    'city' => $location->city,
                    'postal_code' => $location->postalCode,
                    'latitude' => $location->latitude,
                    'longitude' => $location->longitude,
                    'area_code' => $location->areaCode,
                    'dma_code' => $location->dmaCode
                ));
            }
        } catch (Exception $e) {
            return null;
        }
        return $this->data;
    }
}
