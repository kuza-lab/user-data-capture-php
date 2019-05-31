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

        $this->assertEquals('Android', $this->userAgent->getPlatform());
        $this->assertEquals('Android Browser', $this->userAgent->getBrowser());
        $this->assertEquals('4.0', $this->userAgent->getVersion());
        $this->assertFalse($this->userAgent->isApp());
        $this->assertFalse($this->userAgent->isBot());
        $this->assertTrue($this->userAgent->isMobile());
    }

    /**
     * Test for chrome browser
     */
    public function testChromeBrowser() {

        $userAgentString = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36';

        $this->userAgent->setUserAgent($userAgentString);

        $this->assertEquals('Windows', $this->userAgent->getPlatform());
        $this->assertEquals('Chrome', $this->userAgent->getBrowser());
        $this->assertEquals('74.0.3729.169', $this->userAgent->getVersion());
        $this->assertFalse($this->userAgent->isApp());
        $this->assertFalse($this->userAgent->isBot());
        $this->assertFalse($this->userAgent->isMobile());
    }
}