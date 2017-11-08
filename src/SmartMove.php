<?php

namespace SmartMove;

use SmartMove\HttpClient;
use SmartMove\Resource\Application;
use Psr\Log\LoggerInterface;

/**
 * Class Client
 *
 * A client for interacting with the SmartMove API.
 *
 * @package SmartMove
 */
class SmartMove {

    const VERSION = '1.0.0';

    /**
     * @var string The API key to be used for requests.
     */
    private static $apiKey;

    /**
     * @var string The base URL for the API
     */
    private static $apiBase = 'https://w3p.rentprep.com';

    /**
     * @var string The application specific internal ID of the end user
     */
    private static $referenceId;

    /**
     * @var Psr\Log\LoggerInterface;
     */
    private static $logger;

    /**
     * @var SmartMove\HttpClient;
     */
    private static $http = null;

    /**
     * Sets the API key to be used for requests.
     *
     * @param string $apiKey
     */
    public static function setApiKey($apiKey) {
        self::$apiKey = $apiKey;
    }

    /**
     * @return string The API key used for requests.
     */
    public static function getApiKey() {
        return self::$apiKey;
    }

    /**
     * Sets the base API Url to be used for requests.
     *
     * @param string $apiUrl
     */
    public static function setApiBase($apiBase) {
        self::$apiBase = $apiBase;
    }

    /**
     * @return string The base API Url
     */
    public static function getApiBase() {
        return self::$apiBase;
    }

    /**
     * Sets the customer reference ID
     *
     * @param string $apiUrl
     */
    public static function setReferenceId($referenceId) {
        self::$referenceId = $referenceId;
    }

    /**
     * @return string The customer reference ID
     */
    public static function getReferenceId() {
        return self::$referenceId;
    }

    /**
     * @param Psr\Log\LoggerInterface $logger The logger to which the library
     *   will produce messages.
     */
    public static function setLogger(LoggerInterface $logger = null) {
        self::$logger = $logger;
    }

    /**
     * @return Psr\Log\LoggerInterface The logger to which the library will
     *   produce messages.
     */
    public static function getLogger() {
        return self::$logger;
    }

    /**
     * Get the singleton instance of the HttpClient
     *
     * @return SmartMove\HttpClient;
     */
    public static function http() {
         if(is_null(self::$http)) {
             self::$http = new HttpClient(
                 self::getApiKey(),
                 self::getApiBase(),
                 self::getLogger()
             );
         }
         return self::$http;
    }


    /**
     * POST: Create a new application
     *
     * @return string Url of new application
     */
    public static function createApplication($referenceId = null, $params = []) {
        $defaults = [
            'Customer' => [
                'referenceId' => $referenceId ?: self::getReferenceId()
            ],
        ];
        $params += $defaults;

        return self::http()->post('/api/smartmove/application/create', $params);
    }

    /**
     * GET: List SmartMove Orders
     *
     * @return array
     */
    public static function getApplications($referenceId = null) {
        $referenceId = $referenceId ?: self::getReferenceId();
        $response = self::http()->post('/api/smartmove/application/all', compact('referenceId'));
        return $response ? Util::convertToSmartMoveObject($response, Application::class) : [];
    }

    /**
     * GET: Get application details
     *
     * @return object
     */
    public static function getApplication($applicationId, $referenceId = null) {
        $referenceId = $referenceId ?: self::getReferenceId();
        $response = self::http()->post('/api/smartmove/application/details', compact('applicationId', 'referenceId'));
        return Util::convertToSmartMoveObject($response, Application::class);
    }

    /**
     * POST: Cancel application
     *
     * @return bool
     */
    public static function cancelApplication($applicationId, $referenceId = null) {
        $referenceId = $referenceId ?: self::getReferenceId();
        return (bool) self::http()->post('/api/smartmove/application/cancel', compact('applicationId', 'referenceId'));
    }

    /**
     * POST: Add applicate to application
     *
     * @return bool
     */
    public static function addApplicant($applicationId, $email, $referenceId = null) {
        $email = is_array($email) ? implode(',', $email) : $email;
        $referenceId = $referenceId ?: self::getReferenceId();
        return (bool) self::http()->post('/api/smartmove/applicant/add', compact('applicationId', 'email', 'referenceId'));
    }

    /**
     * POST: Remove an applicant from an application
     *
     * @return bool
     */
    public static function removeApplicant($applicationId, $email, $referenceId = null) {
        $email = is_array($email) ? implode(',', $email) : $email;
        $referenceId = $referenceId ?: self::getReferenceId();
        return (bool) self::http()->post('/api/smartmove/applicant/delete', compact('applicationId', 'email', 'referenceId'));
    }

    /**
     * GET: Get report URL
     *
     * @return string Url of application report
     */
    public static function getReportUrl($applicationId, $referenceId = null) {
        $referenceId = $referenceId ?: self::getReferenceId();
        return self::http()->post('/api/smartmove/application/reportUrl', compact('applicationId', 'referenceId'));
    }
}
