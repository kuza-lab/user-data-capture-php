User Data Capture for PHP
=========================

PHP Library for User Data Capture.

This library allows you to capture the following user details:
 * location details of the user including continent, country, city, latitude, longitude, timezone among others
 * the details of the requests they have sent to the server. Includes their request URIs, query parameters and request bodies.
 * The device used by the user. This includes details of the operating system used, the browser and whether they are using a mobile device or not.


Requirements
============

* PHP >= 5.3.7
* cURL Extension
* Maxmind GeoIP2-City database
   

## To install the maxmind geoip databases (for Linux/Mac OS users)

    mkdir /usr/local/share/GeoIP/

    wget https://geolite.maxmind.com/download/geoip/database/GeoLite2-City.tar.gz
    tar xvzf  GeoLite2-City.tar.gz
    mv GeoLite2-City_20190528/GeoLite2-City.mmdb /usr/local/share/GeoIP/GeoLite2-City.mmdb
    
    wget https://geolite.maxmind.com/download/geoip/database/GeoLite2-Country.tar.gz
    tar xvzf GeoLite2-Country.tar.gz
    mv GeoLite2-Country_20190528/GeoLite2-Country.mmdb /usr/local/share/GeoIP/GeoLite2-Country.mmdb
    
    wget https://geolite.maxmind.com/download/geoip/database/GeoLite2-ASN.tar.gz
    tar xvzf GeoLite2-ASN.tar.gz
    mv GeoLite2-ASN_20190528/GeoLite2-ASN.mmdb /usr/local/share/GeoIP/GeoLite2-ASN.mmdb

Installation
============

    composer require kuza/user-data-capture

Usage
=====


```php
<?php

    require_once 'vendor/autoload.php';
    
    use Kuza\UserDataCapture\Location;
    use Kuza\UserDataCapture\UserAgent;
    use Kuza\UserDataCapture\Request;
    
    # Get the user's location details
    
   
    try {
        $location = new Location("path_to_maxmind_db");

        $continent_name = $location->continent_name;
        $country_name = $location->country_name;
        $city_name = $location->city_name;
        $latitude = $location->latitude;
        $longitude  = $location->longitude;
        $timezone = $location->timezone;

    } catch (\MaxMind\Db\Reader\InvalidDatabaseException $ex) {
        echo $ex->getMessage();
    } catch (\Exception $ex) {
        echo $ex->getMessage();
    }
    
    # Get the user's device details
    
    $device = new UserAgent();
    
    $os = $device->platform;
    $browser = $device->browser;
    $browser_version = $device->version;
    $is_mobile = $device->is_mobile;
    $is_app = $device->is_app;
    $is_bot = $device->is_bot;
    
    # Get request details
    
    $request = new Request();
    
    $request_method = $request->method;
    $query_params = $request->query_params;
    $body = $request->body;
    $headers = $request->headers;
    $full_uri = $request->full_uri;
    $uri_path = $request->uri_path;
    
    
?>
```
    

Credits
=======

* Phelix Juma from Kuza Lab Ltd
