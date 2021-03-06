<?php

namespace Geocoder\Tests\Provider;

use Geocoder\Tests\TestCase;
use Geocoder\Provider\IpInfoDb;

class IpInfoDbTest extends TestCase
{
    public function testGetName()
    {
        $provider = new IpInfoDb($this->getMockAdapter($this->never()), 'api_key');
        $this->assertEquals('ip_info_db', $provider->getName());
    }

    /**
     * @expectedException \Geocoder\Exception\InvalidCredentials
     */
    public function testGetDataWithNullApiKey()
    {
        $provider = new IpInfoDb($this->getMock('\Ivory\HttpAdapter\HttpAdapterInterface'), null);
        $provider->getGeocodedData('foo');
    }

    /**
     * @expectedException Geocoder\Exception\UnsupportedOperation
     * @expectedExceptionMessage The IpInfoDb does not support Street addresses.
     */
    public function testGetGeocodedDataWithRandomString()
    {
        $provider = new IpInfoDb($this->getMockAdapter($this->never()), 'api_key');
        $provider->getGeocodedData('foobar');
    }

    /**
     * @expectedException Geocoder\Exception\UnsupportedOperation
     * @expectedExceptionMessage The IpInfoDb does not support Street addresses.
     */
    public function testGetGeocodedDataWithNull()
    {
        $provider = new IpInfoDb($this->getMockAdapter($this->never()), 'api_key');
        $provider->getGeocodedData(null);
    }

    /**
     * @expectedException Geocoder\Exception\UnsupportedOperation
     * @expectedExceptionMessage The IpInfoDb does not support Street addresses.
     */
    public function testGetGeocodedDataWithEmpty()
    {
        $provider = new IpInfoDb($this->getMockAdapter($this->never()), 'api_key');
        $provider->getGeocodedData('');
    }

    /**
     * @expectedException Geocoder\Exception\UnsupportedOperation
     * @expectedExceptionMessage The IpInfoDb does not support Street addresses.
     */
    public function testGetGeocodedDataWithAddress()
    {
        $provider = new IpInfoDb($this->getMockAdapter($this->never()), 'api_key');
        $provider->getGeocodedData('10 avenue Gambetta, Paris, France');
    }

    public function testGetGeocodedDataWithLocalhostIPv4()
    {
        $provider = new IpInfoDb($this->getMockAdapter($this->never()), 'api_key');
        $result   = $provider->getGeocodedData('127.0.0.1');

        $this->assertInternalType('array', $result);
        $this->assertCount(1, $result);

        $result = $result[0];
        $this->assertInternalType('array', $result);
        $this->assertArrayNotHasKey('latitude', $result);
        $this->assertArrayNotHasKey('longitude', $result);
        $this->assertArrayNotHasKey('postalCode', $result);
        $this->assertArrayNotHasKey('timezone', $result);

        $this->assertEquals('localhost', $result['locality']);
        $this->assertEquals('localhost', $result['region']);
        $this->assertEquals('localhost', $result['county']);
        $this->assertEquals('localhost', $result['country']);
    }

    /**
     * @expectedException \Geocoder\Exception\UnsupportedOperation
     * @expectedExceptionMessage The IpInfoDb does not support IPv6 addresses.
     */
    public function testGetGeocodedDataWithLocalhostIPv6()
    {
        $provider = new IpInfoDb($this->getMockAdapter($this->never()), 'api_key');
        $provider->getGeocodedData('::1');
    }

    /**
     * @expectedException \Geocoder\Exception\NoResult
     * @expectedExceptionMessage Could not execute query http://api.ipinfodb.com/v3/ip-city/?key=api_key&format=json&ip=74.125.45.100
     */
    public function testGetGeocodedDataWithRealIPv4GetsNullContent()
    {
        $provider = new IpInfoDb($this->getMockAdapterReturns(null), 'api_key');
        $provider->getGeocodedData('74.125.45.100');
    }

    /**
     * @expectedException \Geocoder\Exception\NoResult
     * @expectedExceptionMessage Could not execute query http://api.ipinfodb.com/v3/ip-city/?key=api_key&format=json&ip=74.125.45.100
     */
    public function testGetGeocodedDataWithRealIPv4GetsEmptyContent()
    {
        $provider = new IpInfoDb($this->getMockAdapterReturns(''), 'api_key');
        $provider->getGeocodedData('74.125.45.100');
    }

    public function testGetGeocodedDataWithRealIPv4()
    {
        if (!isset($_SERVER['IPINFODB_API_KEY'])) {
            $this->markTestSkipped('You need to configure the IPINFODB_API_KEY value in phpunit.xml');
        }

        $provider = new IpInfoDb($this->getAdapter(), $_SERVER['IPINFODB_API_KEY']);
        $result   = $provider->getGeocodedData('74.125.45.100');

        $this->assertInternalType('array', $result);
        $this->assertCount(1, $result);

        $result = $result[0];
        $this->assertInternalType('array', $result);
        $this->assertEquals(37.406, $result['latitude'], '', 0.001);
        $this->assertEquals(-122.079, $result['longitude'], '', 0.001);
        $this->assertEquals(94043, $result['postalCode']);
        $this->assertEquals('MOUNTAIN VIEW', $result['locality']);
        $this->assertEquals('CALIFORNIA', $result['region']);
        $this->assertEquals('UNITED STATES', $result['country']);
        $this->assertEquals('US', $result['countryCode']);
        $this->assertEquals('America/Denver', $result['timezone']);
    }

    /**
     * @expectedException \Geocoder\Exception\UnsupportedOperation
     * @expectedExceptionMessage The IpInfoDb does not support IPv6 addresses.
     */
    public function testGetGeocodedDataWithRealIPv6()
    {
        if (!isset($_SERVER['IPINFODB_API_KEY'])) {
            $this->markTestSkipped('You need to configure the IPINFODB_API_KEY value in phpunit.xml');
        }

        $provider = new IpInfoDb($this->getAdapter(), $_SERVER['IPINFODB_API_KEY']);
        $provider->getGeocodedData('::ffff:74.125.45.100');
    }

    /**
     * @expectedException \Geocoder\Exception\UnsupportedOperation
     * @expectedExceptionMessage The IpInfoDb is not able to do reverse geocoding.
     */
    public function testReversedData()
    {
        $provider = new IpInfoDb($this->getMock('\Ivory\HttpAdapter\HttpAdapterInterface'), 'api_key');
        $provider->getReversedData(array());
    }
}
