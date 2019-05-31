<?php

use PHPUnit\Framework\TestCase;
use Kuza\UserDataCapture\Location;

class LocationTest extends TestCase {

    /**
     * @var Location
     */
    protected $location;

    /**
     * Set up the test case.
     *
     * @throws \MaxMind\Db\Reader\InvalidDatabaseException
     */
    public function setUp() {
        $this->location = new Location();
    }

    /**
     * test an IP address in the US
     *
     * @throws Exception
     */
    public function testUnitedStatesIpAddress() {

        $ipAddress = '128.101.101.101';

        $this->location->setIpAddress($ipAddress);

        $this->assertEquals('US', $this->location->country_code);
    }

    /**
     * Test an IP address in kenya
     *
     * @throws Exception
     */
    public function testKenyanIpAddress() {

        $ipAddress = '197.237.102.175';

        $this->location->setIpAddress($ipAddress);

        $this->assertEquals('KE', $this->location->country_code);
    }

    /**
     * Test a European IP Address (French Address)
     *
     * @throws Exception
     */
    public function testServerIpAddress() {

        $ipAddress = '163.172.172.63';

        $this->location->setIpAddress($ipAddress);

        $this->assertEquals('FR', $this->location->country_code);
    }
}