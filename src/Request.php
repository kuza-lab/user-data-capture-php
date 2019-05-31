<?php

namespace Kuza\UserDataCapture;


/**
 * Request Class
 *
 * This class gets the details of the request.
 *
 * @author Phelix Juma
 *
 * @package Kuza\UserDataCapture
 */
class Request {

    /**
     * @var string the full URI
     */
    public $full_uri = '';

    /**
     * @var string the specific URI path
     */
    public $uri_path = '';

    /**
     * @var string the request method
     */
    public $method = "";

    /**
     * @var array the request headers
     */
    public $headers = [];

    /**
     * @var array the query parameters
     */
    public $query_params = [];

    /**
     * @var array the request body
     */
    public $body = [];

    /**
     * Requests constructor.
     * @param string $uriString
     */
    public function __construct($uriString = '') {
        $this->setURI($uriString);
    }

    /**
     * Set the URI to use.
     * Defaults to the server URI
     * @param string $uriString
     */
    public function setURI($uriString = '') {

        $uriString = empty($uri) && isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $uriString;

        $this->full_uri = $uriString;

        $this->process();
    }

    /**
     * Process the URI and get the details.
     */
    private function process() {

        $this->setUriPath();
        $this->setHeaders();
        $this->setQueryParameters();
        $this->setMethod();
        $this->setBody();
    }

    /**
     * Set the URI Path
     */
    private function setUriPath() {
        $this->uri_path = parse_url($this->full_uri, PHP_URL_PATH);
    }

    /**
     * Set the query parameters
     */
    private function setQueryParameters() {

        //we get the url query parameters
        $urlQueryParams =  explode("&", parse_url($this->full_uri, PHP_URL_QUERY));

        if (sizeof($urlQueryParams) > 0) {
            foreach ($urlQueryParams as $param) {
                if (!empty($param)) {
                    $paramKeyValuePair = explode("=", $param);
                    if (isset($paramKeyValuePair[0]) && isset($paramKeyValuePair[1])) {
                        $key = $paramKeyValuePair[0];
                        $this->query_params[$key] = $paramKeyValuePair[1];
                    }
                }
            }
        }
    }

    /**
     * Set the request body
     */
    public function setBody() {
        $this->body = json_decode(file_get_contents("php://input"), JSON_FORCE_OBJECT);
    }

    /**
     * Set the headers
     */
    private function setHeaders(){

        $headers = [];

        foreach($_SERVER as $key=>$value) {
            $headers[strtolower(str_ireplace("HTTP_","", $key))] =$value;
        }
        $this->headers = $headers;
    }

    /**
     * Set the method
     */
    private function setMethod() {
        $this->method = isset($this->headers['REQUEST_METHOD']) ? $this->headers['REQUEST_METHOD'] : "";
    }

    /**
     * check if is get request
     * @return bool
     */
    public function isGet() {
        return $this->method == 'GET';
    }

    /**
     * check if is post request
     * @return bool
     */
    public function isPost() {
        return $this->method == 'POST';
    }

    /**
     * check if is patch request
     * @return bool
     */
    public function isPatch() {
        return $this->method == 'PATCH';
    }

    /**
     * check if is put request
     * @return bool
     */
    public function isPut() {
        return $this->method == 'PUT';
    }

    /**
     * check if is delete request
     * @return bool
     */
    public function isDelete() {
        return $this->method == 'DELETE';
    }

    /**
     * check if is head request
     * @return bool
     */
    public function isHead() {
        return $this->method == 'HEAD';
    }

    /**
     * check if is options request
     * @return bool
     */
    public function isOptions() {
        return $this->method == 'OPTIONS';
    }

    /**
     * check if is trace request
     * @return bool
     */
    public function isTrace() {
        return $this->method == 'TRACE';
    }

    /**
     * check if is connect request
     * @return bool
     */
    public function isConnect() {
        return $this->method == 'CONNECT';
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