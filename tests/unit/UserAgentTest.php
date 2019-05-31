<?php

use PHPUnit\Framework\TestCase;
use Kuza\UserDataCapture\UserAgent;

class UserAgentTest extends TestCase {

    /**
     * @var UserAgent
     */
    protected $userAgent;

    /**
     * Set up the test case.
     */
    public function setUp() {
        $this->userAgent = new UserAgent();
    }

    /**
     * Test for android browser
     */
    public function testAndroidBrowser() {

        $userAgentString = 'Mozilla/5.0 (Linux; U; Android 2.2) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1';

        $this->userAgent->setUserAgent($userAgentString);

        $this->assertEquals('Android', $this->userAgent->platform);
        $this->assertEquals('Android Browser', $this->userAgent->browser);
        $this->assertEquals('4.0', $this->userAgent->version);
        $this->assertFalse($this->userAgent->is_app);
        $this->assertFalse($this->userAgent->is_bot);
        $this->assertTrue($this->userAgent->is_mobile);
    }

    /**
     * Test for chrome browser
     */
    public function testChromeBrowser() {

        $userAgentString = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36';

        $this->userAgent->setUserAgent($userAgentString);

        $this->assertEquals('Windows', $this->userAgent->platform);
        $this->assertEquals('Chrome', $this->userAgent->browser);
        $this->assertEquals('74.0.3729.169', $this->userAgent->version);
        $this->assertFalse($this->userAgent->is_app);
        $this->assertFalse($this->userAgent->is_bot);
        $this->assertFalse($this->userAgent->is_mobile);
    }

    /**
     * Test for chrome browser
     */
    public function testBot() {

        $userAgentString = 'PostmanRuntime/7.11.0';

        $this->userAgent->setUserAgent($userAgentString);

        $this->assertEquals('', $this->userAgent->platform);
        $this->assertEquals('PostmanRuntime', $this->userAgent->browser);
        $this->assertEquals('7.11.0', $this->userAgent->version);
        $this->assertFalse($this->userAgent->is_app);
        $this->assertTrue($this->userAgent->is_bot);
        $this->assertFalse($this->userAgent->is_mobile);
    }
}