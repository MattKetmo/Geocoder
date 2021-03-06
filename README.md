Geocoder
========

**Geocoder** is a library which helps you build geo-aware applications. It provides an abstraction layer for geocoding manipulations.
The library is split in two parts: `HttpAdapter` and `Provider` and is really extensible.

[![Build Status](https://secure.travis-ci.org/geocoder-php/Geocoder.png)](http://travis-ci.org/geocoder-php/Geocoder)


### HttpAdapters ###

_HttpAdapters_ are responsible to get data from remote APIs.

Currently, there are the following adapters:

* `BuzzHttpAdapter` to use [Buzz](https://github.com/kriswallsmith/Buzz), a lightweight PHP 5.3 library for issuing HTTP requests;
* `CurlHttpAdapter` to use [cURL](http://php.net/manual/book.curl.php);
* `GuzzleHttpAdapter` to use [Guzzle](https://github.com/guzzle/guzzle), PHP 5.3+ HTTP client and framework for building RESTful web service clients;
* `SocketHttpAdapter` to use a [socket](http://www.php.net/manual/function.fsockopen.php);
* `ZendHttpAdapter` to use [Zend Http Client](http://framework.zend.com/manual/2.0/en/modules/zend.http.client.html);
* `GeoIP2Adapter` to use [GeoIP2 Database Reader](https://github.com/maxmind/GeoIP2-php#database-reader) or the [Webservice Client](https://github.com/maxmind/GeoIP2-php#web-service-client) by MaxMind.


### Providers ###

_Providers_ contain the logic to extract useful information.

Currently, there are many providers for the following APIs:

Address-based geocoding

provider      | reverse | SSL | coverage | terms
:------------- |:--------- |:--------- |:--------- |:-----
[Google Maps](https://developers.google.com/maps/documentation/geocoding/) | yes | no | worldwide | requires API key. Limit 2500 requests per day
[Google Maps for Business](https://developers.google.com/maps/documentation/business/) | yes | no | worldwide | requires API key. Limit 100,000 requests per day
[Bing Maps](http://msdn.microsoft.com/en-us/library/ff701713.aspx) | yes | no | worldwide | requires API key. Limit 10,000 requests per month.
[OpenStreetMap](http://wiki.openstreetmap.org/wiki/Nominatim) | yes | no | worldwide | heavy users (>1q/s) get banned
Nominatim    | yes | supported | worldwide | requires a domain name (e.g. local installation)
[MapQuest](http://developer.mapquest.com/web/products/dev-services/geocoding-ws)  | yes | no | worldwide | both open and [commercial service](http://platform.mapquest.com/geocoding/) require API key
[OpenCage](http://geocoder.opencagedata.com/)  | yes | supported | worldwide | requires API key. 2500 requests/day free
[Yandex](http://api.yandex.com/maps/)  | yes | no | worldwide
[Geonames](http://www.geonames.org/commercial-webservices.html)  | yes |no | worldwide | requires registration, no free tier
[TomTom](https://geocoder.tomtom.com/app/view/index)  | yes | required | worldwide | requires API key. First 2500 requests or 30 days free
[ArcGIS Online](https://developers.arcgis.com/en/features/geocoding/) | yes | supported | worldwide | requires API key. 1250 requests free
Chain | | | | meta provider which iterates over a list of providers


IP-based geocoding

provider      | IPv6 | terms | notes
:------------- |:--------- |:--------- |:---------
[FreeGeoIp](http://freegeoip.net/) | yes
[HostIp](http://www.hostip.info/use.html) | no
[IpInfoDB](http://ipinfodb.com/) | no | city precision
Geoip| ? | | wrapper around the [PHP extension](http://php.net/manual/en/book.geoip.php)
[GeoPlugin](http://www.geoplugin.com/) | yes
[GeoIPs](http://www.geoips.com/en/) | no | requires API key
[MaxMind](https://www.maxmind.com/) web service | yes | requires Omni API key | City/ISP/Org and Omni services, IPv6 on country level
MaxMind binary file | yes | | needs locally installed database files
MaxMind [GeoIP2](https://www.maxmind.com/en/geoip2-databases) | yes |

The [Geocoder Extra](https://github.com/geocoder-php/geocoder-extra) library contains even more providers!


Installation
------------

The recommended way to install Geocoder is through
[Composer](http://getcomposer.org).

Create a `composer.json` file into your project:

``` json
{
    "require": {
        "willdurand/geocoder": "@stable"
    }
}
```

**Protip:** you should browse the
[`willdurand/geocoder`](https://packagist.org/packages/willdurand/geocoder) page
to choose a stable version to use, avoid the `@stable` meta constraint.

And run these two commands to install it:

``` bash
$ curl -sS https://getcomposer.org/installer | php
$ composer install
```

You're done.


Usage
-----

First, you need an `adapter` to query an API:

``` php
<?php

$adapter  = new \Geocoder\HttpAdapter\BuzzHttpAdapter();
```

The `BuzzHttpAdapter` is tweakable, actually you can pass a `Browser` object to this adapter:

``` php
<?php

$buzz    = new \Buzz\Browser(new \Buzz\Client\Curl());
$adapter = new \Geocoder\HttpAdapter\BuzzHttpAdapter($buzz);
```

Now, you have to choose a `provider` which is closed to what you want to get.


### FreeGeoIp ###

The `FreeGeoIp` named `free_geo_ip` is able to geocode **IPv4 and IPv6
addresses** only.


### HostIp ###

The `HostIp` named `host_ip` is able to geocode **IPv4 addresses** only.


### IpInfoDb ###

The `IpInfoDb` named `ip_info_db` is able to geocode **IPv4 addresses**
only.  A valid api key is required.


### GoogleMaps ###

The `GoogleMaps` named `google_maps` is able to geocode and reverse
geocode **street addresses**.  A locale and a region can be set as well as an
optional api key. This provider also supports SSL.


### GoogleMapsBusiness ###

The `GoogleMapsBusiness` named `google_maps_business` is able to geocode
and reverse geocode **street addresses**.  A valid `Client ID` is required. The
private key is optional. This provider also supports SSL.


### BingMaps ###

The `BingMaps` named `bing_maps` is able to geocode and reverse geocode
**street addresses**.  A valid api key is required.


### OpenStreetMap ###

The `OpenStreetMap` named `openstreetmap` is able to geocode and reverse
geocode **street addresses**.


### Nominatim ###

The `Nominatim` named `nominatim` is able to geocode and reverse geocode
**street addresses**.  Access to a Nominatim server is required. See the
[Nominatim Wiki Page](http://wiki.openstreetmap.org/wiki/Nominatim) for more
information.


### Geoip ###

The `Geoip` named `geoip` is able to geocode **IPv4 and IPv6 addresses**
only. No need to use an `HttpAdapter` as it uses a local database.  See the
[MaxMind page](http://www.maxmind.com/app/php) for more information.


### Chain ###

The `Chain` named `chain` is a special provider that takes a list of
providers and iterates over this list to get information.


### MapQuest ###

The `MapQuest` named `map_quest` is able to geocode and reverse geocode
**street addresses**.  A valid api key is required. Access to [MapQuest's
licensed
endpoints](http://developer.mapquest.com/web/tools/getting-started/platform/licensed-vs-open)
is provided via constructor argument.


### OpenCage ###

The `OpenCage` named `opencage` is able to geocode and reverse geocode
**street addresses**.  A valid api key is required.


### Yandex ###

The `Yandex` named `yandex` is able to geocode and reverse geocode
**street addresses**.  The default language-locale is `ru-RU`, you can choose
between `uk-UA`, `be-BY`, `en-US`, `en-BR` and `tr-TR`.  This provider can also
reverse information based on coordinates (latitude, longitude). It's possible to
precise the toponym to get more accurate result for reverse geocoding: `house`,
`street`, `metro`, `district` and `locality`.


### GeoPlugin ###

The `GeoPlugin` named `geo_plugin` is able to geocode **IPv4 addresses
and IPv6 addresses** only.


### GeoIPs ###

The `GeoIPs` named `geo_ips` is able to geocode **IPv4 addresses** only.
A valid api key is required.


### MaxMind ###

The `MaxMind` named `maxmind` is able to geocode **IPv4 and IPv6
addresses** only.  A valid `City/ISP/Org` or `Omni` service's api key is
required.  This provider provides two constants `CITY_EXTENDED_SERVICE` by
default and `OMNI_SERVICE`.


### MaxMindBinary ###

The `MaxMindBinary` named `maxmind_binary` is able to geocode **IPv4 and
IPv6 addresses** only. It requires a data file, and the
[geoip/geoip](https://packagist.org/packages/geoip/geoip) package must be
installed.

It is worth mentioning that this provider has **serious performance issues**,
and should **not** be used in production. For more information, please read
[issue #301](https://github.com/geocoder-php/Geocoder/issues/301).

### GeoIP2Database ###

The `GeoIP2` named `maxmind_geoip2` is able to geocode **IPv4 and IPv6
addresses** only - it makes use of the MaxMind GeoIP2 databases or the
webservice.

It requires either the [database
file](http://dev.maxmind.com/geoip/geoip2/geolite2/), or the
[webservice](http://dev.maxmind.com/geoip/geoip2/web-services/) - represented by
the GeoIP2 , which is injected to the `GeoIP2Adapter`. The
[geoip2/geoip2](https://packagist.org/packages/geoip2/geoip2) package must be
installed.

This provider will only work with the corresponding `GeoIP2Adapter`.

##### Usage

``` php
<?php

// Maxmind GeoIP2 Provider: e.g. the database reader
$reader   = new \GeoIp2\Database\Reader('/path/to/database');

$adapter  = new \Geocoder\HttpAdapter\GeoIP2Adapter($reader);
$provider = new \Geocoder\Provider\GeoIP2($adapter);
$geocoder = new \Geocoder\Geocoder($provider);

$address   = $geocoder->geocode('74.200.247.59');
```

### Geonames ###

The `Geonames` named `geonames` is able to geocode and reverse geocode
**places**.  A valid username is required.

### TomTom ###

The `TomTom` named `tomtom` is able to geocode and reverse geocode
**street addresses**.  The default langage-locale is `en`, you can choose
between `de`, `es`, `fr`, `it`, `nl`, `pl`, `pt` and `sv`.  A valid api key is
required.

### ArcGISOnline ###

The `ArcGISOnline` named `arcgis_online` is able to geocode and reverse
geocode **street addresses**.  It's possible to specify a sourceCountry to
restrict result to this specific country thus reducing request time (note that
this doesn't work on reverse geocoding). This provider also supports SSL.


### Using The Providers ###

You can use one of them or write your own provider. You can also register all
providers and decide later. That's we'll do:

``` php
<?php

$geocoder = new \Geocoder\ProviderBasedGeocoder();
$geocoder->registerProviders(array(
    new \Geocoder\Provider\GoogleMaps(
        $adapter, $locale, $region, $useSsl
    ),
    new \Geocoder\Provider\GoogleMapsBusiness(
        $adapter, '<CLIENT_ID>', '<PRIVATE_KEY>', $locale, $region, $useSsl
    ),
    new \Geocoder\Provider\Yandex(
        $adapter, $locale, $toponym
    ),
    new \Geocoder\Provider\MaxMind(
        $adapter, '<MAXMIND_API_KEY>', $service, $useSsl
    ),
    new \Geocoder\Provider\ArcGISOnline(
        $adapter, $sourceCountry, $useSsl
    ),
    new \Geocoder\Provider\Nominatim(
        $adapter, 'http://your.nominatim.server', $locale
    ),
));
```

Parameters:

* `$locale` is available for `Yandex`, `BingMaps`,
  `OpenCage` and `TomTom`
* `$region` is available for `GoogleMaps` and
  `GoogleMapsBusiness`
* `$toponym` is available for `Yandex`
* `$service` is available for `MaxMind`
* `$useSsl` is available for `GoogleMaps`, `GoogleMapsBusiness`,
  `OpenCage`, `MaxMind` and `ArcGISOnline`
* `$sourceCountry` is available for `ArcGISOnline`
* `$rootUrl` is available for `Nominatim`

### Using The Chain Provider ###

As said it's a special provider that takes a list of providers and iterates over
this list to get information. Note that it **stops** its iteration when a
provider returns a result. The result is returned by `GoogleMaps` because
`FreeGeoIp` and `HostIp` cannot geocode street addresses. `BingMaps` is ignored.

``` php
$geocoder = new \Geocoder\ProviderBasedGeocoder();
$adapter  = new \Geocoder\HttpAdapter\CurlHttpAdapter();
$chain    = new \Geocoder\Provider\Chain(array(
    new \Geocoder\Provider\FreeGeoIp($adapter),
    new \Geocoder\Provider\HostIp($adapter),
    new \Geocoder\Provider\GoogleMaps($adapter, 'fr_FR', 'France', true),
    new \Geocoder\Provider\BingMaps($adapter, '<API_KEY>'),
    // ...
));
$geocoder->registerProvider($chain);

try {
    $geocode = $geocoder->geocode('10 rue Gambetta, Paris, France');
    var_export($geocode);
} catch (Exception $e) {
    echo $e->getMessage();
}
```

Everything is ok, enjoy!

API
---

The main method is called `geocode()` which receives a value to geocode. It can
be an IP address or a street address (partial or not).

``` php
<?php

$address = $geocoder->geocode('88.188.221.14');
// Result is:
// "latitude"       => string(9) "47.901428"
// "longitude"      => string(8) "1.904960"
// "bounds"         => array(4) {
//     "south" => string(9) "47.813320"
//     "west"  => string(8) "1.809770"
//     "north" => string(9) "47.960220"
//     "east"  => string(8) "1.993860"
// }
// "streetNumber"   => string(0) ""
// "streetName"     => string(0) ""
// "cityDistrict"   => string(0) ""
// "city"           => string(7) "Orleans"
// "zipcode"        => string(0) ""
// "county"         => string(6) "Loiret"
// "countyCode"     => null
// "region"         => string(6) "Centre"
// "regionCode"     => null
// "country"        => string(6) "France"
// "countryCode"    => string(2) "FR"
// "timezone"       => string(6) "Europe/Paris"

$address = $geocoder->geocode('10 rue Gambetta, Paris, France');
// Result is:
// "latitude"       => string(9) "48.863217"
// "longitude"      => string(8) "2.388821"
// "bounds"         => array(4) {
//     "south" => string(9) "48.863217"
//     "west"  => string(8) "2.388821"
//     "north" => string(9) "48.863217"
//     "east"  => string(8) "2.388821"
// }
// "streetNumber"   => string(2) "10"
// "streetName"     => string(15) "Avenue Gambetta"
// "cityDistrict"   => string(18) "20E Arrondissement"
// "city"           => string(5) "Paris"
// "county"         => string(5) "Paris"
// "countyCode"     => null
// "zipcode"        => string(5) "75020"
// "region"         => string(14) "Ile-de-France"
// "regionCode"     => null
// "country"        => string(6) "France"
// "countryCode"    => string(2) "FR"
// "timezone"       => string(6) "Europe/Paris"
```

The `geocode()` method returns an array of `Address` objects, each providing the
following API:

* `getCoordinates()` will return a `Coordinates` object (with `latitude` and
  `longitude` properties);
* `getLatitude()` will return the `latitude` value;
* `getLongitude()` will return the `longitude` value;
* `getBounds()` will return an `Bounds` object (with `south`, `west`, `north`
  and `east` properties);
* `getStreetNumber()` will return the `street number/house number` value;
* `getStreetName()` will return the `street name` value;
* `getLocality()` will return the `locality` or `city`;
* `getPostalCode()` will return the `postalCode` or `zipcode`;
* `getSubLocality()` will return the `city district`, or `sublocality`;
* `getCounty()` will return a `County` object (with `name` and `code`
  properties);
* `getCountyCode()` will return the `county` code (county short name);
* `getRegion()` will return a `Region` object (with `name` and `code`
  properties);
* `getRegionCode()` will return the `region` code (region short name);
* `getCountry()` will return a `Country` object (with `name` and `code`
  properties);
* `getCountryCode()` will return the ISO `country` code;
* `getTimezone()` will return the `timezone`.

The `ProviderBasedGeocoder`'s API is fluent, you can write:

``` php
<?php

$address = $geocoder
    ->registerProvider(new \My\Provider\Custom($adapter))
    ->using('custom')
    ->limit(10)
    ->geocode('68.145.37.34')
    ;
```

The `using()` method allows you to choose the `provider` to use by its name.
When you deal with multiple providers, you may want to choose one of them.  The
default behavior is to use the first one but it can be annoying.

The `limit()` method allows you to configure the maximum number of results being
returned. Depending on the provider you may not get as many results as expected,
it is a maximum limit, not the expected number of results.


Reverse Geocoding
-----------------

This library provides a `reverse()` method to retrieve information from
coordinates:

``` php
$address = $geocoder->reverse($latitude, $longitude);
```


Dumpers
-------

**Geocoder** provides dumpers that aim to transform an `Address` object in standard formats.

### GPS eXchange Format (GPX) ###

The **GPS eXchange** format is designed to share geolocated data like point of
interests, tracks, ways, but also coordinates. **Geocoder** provides a dumper to
convert an `Address` object in an GPX compliant format.

Assuming we got a `$address` object as seen previously:

``` php
<?php

$dumper = new \Geocoder\Dumper\Gpx();
$strGpx = $dumper->dump($address);

echo $strGpx;
```

It will display:

``` xml
<gpx
    version="1.0"
    creator="Geocoder" version="1.0.1-dev"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xmlns="http://www.topografix.com/GPX/1/0"
    xsi:schemaLocation="http://www.topografix.com/GPX/1/0 http://www.topografix.com/GPX/1/0/gpx.xsd">
    <bounds minlat="2.388911" minlon="48.863151" maxlat="2.388911" maxlon="48.863151"/>
    <wpt lat="48.8631507" lon="2.3889114">
        <name><![CDATA[Paris]]></name>
        <type><![CDATA[Address]]></type>
    </wpt>
</gpx>
```

### GeoJSON ###

[GeoJSON](http://geojson.org/) is a format for encoding a variety of geographic
data structures.


### Keyhole Markup Language (KML) ###

[Keyhole Markup Language](http://en.wikipedia.org/wiki/Keyhole_Markup_Language)
is an XML notation for expressing geographic annotation and visualization within
Internet-based, two-dimensional maps and three-dimensional Earth browsers.


### Well-Known Binary (WKB) ###

The Well-Known Binary (WKB) representation for geometric values is defined by
the OpenGIS specification.


### Well-Known Text (WKT) ###

Well-known text (WKT) is a text markup language for representing vector geometry
objects on a map, spatial reference systems of spatial objects and
transformations between spatial reference systems.


Formatters
----------

A common use case is to print geocoded data. Thanks to the `StringFormatter`
class, it's simple to format an `Address` object as a string:

``` php
<?php

// $address is an instance of Address
$formatter = new \Geocoder\Formatter\StringFormatter();

$formatter->format($address, '%S %n, %z %L');
// 'Badenerstrasse 120, 8001 Zuerich'

$formatter->format($address, '<p>%S %n, %z %L</p>');
// '<p>Badenerstrasse 120, 8001 Zuerich</p>'
```

Here is the mapping:

* Street Number: `%n`

* Street Name: `%S`

* City: `%L`

* City District: `%D`

* Zipcode: `%z`

* County: `%P`

* County Code: `%p`

* Region: `%R`

* Region Code: `%r`

* Country: `%C`

* Country Code: `%c`

* Timezone: `%T`


Extending Things
----------------

You can write your own `provider` by implementing the `Provider` interface.

You can provide your own `dumper` by implementing the `Dumper` interface.


Contributing
------------

See
[`CONTRIBUTING`](https://github.com/geocoder-php/Geocoder/blob/master/CONTRIBUTING.md#contributing)
file.


Unit Tests
----------

To run unit tests, you'll need `cURL` and a set of dependencies you can install
using Composer:

```
composer install --dev
```

Once installed, run the following command:

```
phpunit
```

You'll obtain some _skipped_ unit tests due to the need of API keys.

Rename the `phpunit.xml.dist` file to `phpunit.xml`, then uncomment the
following lines and add your own API keys:

``` xml
<php>
    <!-- <server name="IPINFODB_API_KEY" value="YOUR_API_KEY" /> -->
    <!-- <server name="BINGMAPS_API_KEY" value="YOUR_API_KEY" /> -->
    <!-- <server name="GEOIPS_API_KEY" value="YOUR_API_KEY" /> -->
    <!-- <server name="MAXMIND_API_KEY" value="YOUR_API_KEY" /> -->
    <!-- <server name="GEONAMES_USERNAME" value="YOUR_USERNAME" /> -->
    <!-- <server name="TOMTOM_GEOCODING_KEY" value="YOUR_GEOCODING_KEY" /> -->
    <!-- <server name="TOMTOM_MAP_KEY" value="YOUR_MAP_KEY" /> -->
    <!-- <server name="GOOGLE_GEOCODING_KEY" value="YOUR_GEOCODING_KEY" /> -->
    <!-- <server name="OPENCAGE_API_KEY" value="YOUR_API_KEY" /> -->
</php>
```

You're done.


Credits
-------

* William Durand <william.durand1@gmail.com>
* [All contributors](https://github.com/geocoder-php/Geocoder/contributors)


License
-------

Geocoder is released under the MIT License. See the bundled LICENSE file for details.
