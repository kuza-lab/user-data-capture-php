<?php

namespace Kuza\UserDataCapture;

/**
 * User agent Class
 *
 * This class gets the details of the device used based on the user agent sent to the server.
 *
 * @author Phelix Juma
 *
 * @package Kuza\UserDataCapture
 */
class UserAgent {

    /**
     * @var string $user_agent the raw user agent
     */
    private $user_agent;

    /**
     * @var string $platform the operating system used by the user
     */
    private $platform = '';

    /**
     * @var string the user's browser
     */
    private $browser = '';

    /**
     * @var string the browser version
     */
    private $version = '';

    /**
     * @var bool wether this is a mobile user or not
     */
    private $is_mobile;

    /**
     * @var bool whether this is an app access or not
     */
    private $is_app;

    /**
     * @var bool whether this is a bot or not
     */
    private $is_bot;

    /**
     * @var array list of mobile platforms
     */
    protected $mobile_platforms = ['android','ios','iphone','ipad','ipod','windows phone','blackberry'];

    /**
     * @var array list of bots
     */
    protected $bot_browsers = ['Baiduspider','Googlebot','YandexBot','bingbot','Lynx','Version','Wget','curl'];

    /**
     * UserAgent constructor.
     * @param $userAgent
     */
    public function __construct($userAgent = '') {
        $this->setUserAgent($userAgent);
    }

    /**
     * Set the user agent
     * @param string $userAgent
     */
    public function setUserAgent($userAgent = '') {

        $userAgent = empty($userAgent) && isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : $userAgent;
        $this->user_agent = $userAgent;

        $this->process();
    }

    /**
     * Process the analysis of the user agent to get the details.
     */
    private function process() {

        $userAgentComponents = $this->parseUserAgent($this->user_agent);

        $this->platform = $userAgentComponents['platform'];
        $this->browser = $userAgentComponents['browser'];
        $this->version = $userAgentComponents['version'];

        $this->is_app = strpos(strtolower($this->user_agent), 'dalvick');
        $this->is_bot = in_array(strtolower($this->browser), $this->bot_browsers);
        $this->is_mobile = in_array(strtolower($this->platform), $this->mobile_platforms);
    }

    /**
     * Get the raw user agent
     * @return string
     */
    public function getUserAgent() {
        return $this->user_agent;
    }

    /**
     * Get platform
     * @return mixed|string
     */
    public function getPlatform() {
        return $this->platform;
    }

    /**
     * Get the browser
     * @return mixed|string
     */
    public function getBrowser() {
        return $this->browser;
    }

    /**
     * Get the version
     * @return mixed|string
     */
    public function getVersion() {
        return $this->version;
    }

    /**
     * Check if is app
     * @return bool|int
     */
    public function isApp() {
        return $this->is_app;
    }

    /**
     * Check if is bot
     * @return bool|int
     */
    public function isBot() {
        return $this->is_app;
    }

    /**
     * Check if is mobile
     * @return bool
     */
    public function isMobile() {
        return $this->is_mobile;
    }

    /**
     * Parse the user agent for the details.
     * @param null $userAgent
     * @return array
     */
    private function parseUserAgent($userAgent = null) {

        if (is_null($userAgent)) {
            if (isset($_SERVER['HTTP_USER_AGENT'])) {
                $userAgent = $_SERVER['HTTP_USER_AGENT'];
            } else {
                throw new \InvalidArgumentException('parse_user_agent requires a user agent');
            }
        }

        $platform = null;
        $browser = null;
        $version = null;

        $empty = array('platform' => $platform, 'browser' => $browser, 'version' => $version);

        if (!$userAgent)
            return $empty;

        if (preg_match('/\((.*?)\)/im', $userAgent, $parent_matches)) {

            preg_match_all('/(?P<platform>BB\d+;|Android|CrOS|Tizen|iPhone|iPad|iPod|Linux|Macintosh|Windows(\ Phone)?|Silk|linux-gnu|BlackBerry|PlayBook|(New\ )?Nintendo\ (WiiU?|3?DS)|Xbox(\ One)?)
				(?:\ [^;]*)?
				(?:;|$)/imx', $parent_matches[1], $result, PREG_PATTERN_ORDER);

            $priority = array('Xbox One', 'Xbox', 'Windows Phone', 'Tizen', 'Android');
            $result['platform'] = array_unique($result['platform']);
            if (count($result['platform']) > 1) {
                if ($keys = array_intersect($priority, $result['platform'])) {
                    $platform = reset($keys);
                } else {
                    $platform = $result['platform'][0];
                }
            } elseif (isset($result['platform'][0])) {
                $platform = $result['platform'][0];
            }
        }

        if ($platform == 'linux-gnu') {
            $platform = 'Linux';
        } elseif ($platform == 'CrOS') {
            $platform = 'Chrome OS';
        }

        preg_match_all('%(?P<browser>Camino|Kindle(\ Fire)?|Firefox|Iceweasel|Safari|MSIE|Trident|AppleWebKit|TizenBrowser|Chrome|
			Vivaldi|IEMobile|Opera|OPR|Silk|Midori|Edge|CriOS|
			Baiduspider|Googlebot|YandexBot|bingbot|Lynx|Version|Wget|curl|
			NintendoBrowser|PLAYSTATION\ (\d|Vita)+)
			(?:\)?;?)
			(?:(?:[:/ ])(?P<version>[0-9A-Z.]+)|/(?:[A-Z]*))%ix', $userAgent, $result, PREG_PATTERN_ORDER);

        // If nothing matched, return null (to avoid undefined index errors)
        if (!isset($result['browser'][0]) || !isset($result['version'][0])) {
            if (preg_match('%^(?!Mozilla)(?P<browser>[A-Z0-9\-]+)(/(?P<version>[0-9A-Z.]+))?%ix', $userAgent, $result)) {
                return array('platform' => $platform ? : null, 'browser' => $result['browser'], 'version' => isset($result['version']) ? $result['version'] ? : null : null);
            }

            return $empty;
        }

        if (preg_match('/rv:(?P<version>[0-9A-Z.]+)/si', $userAgent, $rv_result)) {
            $rv_result = $rv_result['version'];
        }

        $browser = $result['browser'][0];
        $version = $result['version'][0];

        $lowerBrowser = array_map('strtolower', $result['browser']);

        $find = function ( $search, &$key ) use ( $lowerBrowser ) {
            $xkey = array_search(strtolower($search), $lowerBrowser);
            if ($xkey !== false) {
                $key = $xkey;

                return true;
            }

            return false;
        };

        $key = 0;
        $ekey = 0;
        if ($browser == 'Iceweasel') {
            $browser = 'Firefox';
        } elseif ($find('Playstation Vita', $key)) {
            $platform = 'PlayStation Vita';
            $browser = 'Browser';
        } elseif ($find('Kindle Fire', $key) || $find('Silk', $key)) {
            $browser = $result['browser'][$key] == 'Silk' ? 'Silk' : 'Kindle';
            $platform = 'Kindle Fire';
            if (!($version = $result['version'][$key]) || !is_numeric($version[0])) {
                $version = $result['version'][array_search('Version', $result['browser'])];
            }
        } elseif ($find('NintendoBrowser', $key) || $platform == 'Nintendo 3DS') {
            $browser = 'NintendoBrowser';
            $version = $result['version'][$key];
        } elseif ($find('Kindle', $key)) {
            $browser = $result['browser'][$key];
            $platform = 'Kindle';
            $version = $result['version'][$key];
        } elseif ($find('OPR', $key)) {
            $browser = 'Opera Next';
            $version = $result['version'][$key];
        } elseif ($find('Opera', $key)) {
            $browser = 'Opera';
            $find('Version', $key);
            $version = $result['version'][$key];
        } elseif ($find('Midori', $key)) {
            $browser = 'Midori';
            $version = $result['version'][$key];
        } elseif ($browser == 'MSIE' || ($rv_result && $find('Trident', $key)) || $find('Edge', $ekey)) {
            $browser = 'MSIE';
            if ($find('IEMobile', $key)) {
                $browser = 'IEMobile';
                $version = $result['version'][$key];
            } elseif ($ekey) {
                $version = $result['version'][$ekey];
            } else {
                $version = $rv_result ? : $result['version'][$key];
            }

            if (version_compare($version, '12', '>=')) {
                $browser = 'Edge';
            }
        } elseif ($find('Vivaldi', $key)) {
            $browser = 'Vivaldi';
            $version = $result['version'][$key];
        } elseif ($find('Chrome', $key) || $find('CriOS', $key)) {
            $browser = 'Chrome';
            $version = $result['version'][$key];
        } elseif ($browser == 'AppleWebKit') {
            if (($platform == 'Android' && !($key = 0))) {
                $browser = 'Android Browser';
            } elseif (strpos($platform, 'BB') === 0) {
                $browser = 'BlackBerry Browser';
                $platform = 'BlackBerry';
            } elseif ($platform == 'BlackBerry' || $platform == 'PlayBook') {
                $browser = 'BlackBerry Browser';
            } elseif ($find('Safari', $key)) {
                $browser = 'Safari';
            } elseif ($find('TizenBrowser', $key)) {
                $browser = 'TizenBrowser';
            }

            $find('Version', $key);

            $version = $result['version'][$key];
        } elseif ($key = preg_grep('/playstation \d/i', array_map('strtolower', $result['browser']))) {
            $key = reset($key);

            $platform = 'PlayStation ' . preg_replace('/[^\d]/i', '', $key);
            $browser = 'NetFront';
        }

        return array('platform' => $platform ? : null, 'browser' => $browser ? : null, 'version' => $version ? : null);
    }

    /**
     * Get all the data as an array
     *
     * @return mixed
     */
    public function toArray() {
        return  json_decode(json_encode($this), true);
    }
}