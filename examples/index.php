<?php

require_once  '../vendor/autoload.php';


try {

    $Location = new \Kuza\UserDataCapture\Location();

    print_r($Location->toArray());

} catch (\MaxMind\Db\Reader\InvalidDatabaseException $ex) {
    echo $ex->getMessage();
} catch (\Exception $ex) {
    echo $ex->getMessage();
}

# Get the user's device details

$Device = new \Kuza\UserDataCapture\UserAgent();

print_r($Device->toArray());

# Get request details

$Request = new \Kuza\UserDataCapture\Request();

print_r($Request->toArray());