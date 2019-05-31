<?php

use PHPUnit\Framework\TestCase;
use Kuza\UserDataCapture\Request;

class RequestTest extends TestCase {

    /**
     * @var \Kuza\UserDataCapture\Request
     */
    protected $request;

    /**
     * Set up the test case.
     */
    public function setUp() {
        $this->request = new Request();
    }

    /**
     * Test for a Get request URI.
     */
    public function testGetRequest() {

        $uri_string = '/users/?name=phelix';

        $this->request->setURI($uri_string);

        $this->assertEquals('/users/', $this->request->uri_path);
        $this->assertEquals(['name' => 'phelix'], $this->request->query_params);
    }
}