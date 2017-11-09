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

    /**
     * @var string Library version
     */
    const VERSION = '1.0.0';

    /**
     * @var string The production URL for the API
     */
    const API_ENDPOINT = 'https://w3p.rentprep.com';

    /**
     * @var string The sandbox URL for the API
     */
    const SANDBOX_API_ENDPOINT = 'https://sandbox.rentprep.com';

    /**
     * @var bool Sandbox mode flag
     */
    private static $sandboxMode = false;

    /**
     * @var string The API key to be used for requests.
     */
    private static $apiKey;

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
     * @var bool $isSandbox
     */
    public static function setSandboxMode(bool $isSandbox = true) {
        self::$sandboxMode = (bool) $isSandbox ?: false;
    }

    /**
     * @return string The base API Url
     */
    public static function getApiBase() {
        return self::$sandboxMode ? self::SANDBOX_API_ENDPOINT : self::API_ENDPOINT;
    }

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
     * @param array $data Data to prefill in the application form
     * @param mixed $referenceId The unique ID, in your system, of the person who created the application
     *
     * @return string Url of new application
     */
    public static function createApplication($data = [], $referenceId = null) {
        $defaults = [
            'Customer' => [
                'referenceId' => $referenceId ?: self::getReferenceId()
            ],
        ];
        $data += $defaults;

        return self::http()->post('/api/smartmove/application/create', $data);
    }

    /**
     * GET: List SmartMove Orders
     *
     * @param mixed $referenceId The unique ID, in your system, of the person who created the application
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
     * @param int $applicationId The ID of an application
     * @param mixed $referenceId The unique ID, in your system, of the person who created the application
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
     * @param int $applicationId The ID of an application
     * @param mixed $referenceId The unique ID, in your system, of the person who created the application
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
     * @param int $applicationId The ID of an application
     * @param mixed $email A comma seperated string or an array of applicant email addresses
     * @param mixed $referenceId The unique ID, in your system, of the person who created the application
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
     * @param int $applicationId The ID of an application
     * @param mixed $email A comma seperated string or an array of applicant email addresses
     * @param mixed $referenceId The unique ID, in your system, of the person who created the application
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
     * @param int $applicationId The ID of an application
     * @param mixed $referenceId The unique ID, in your system, of the person who created the application
     *
     * @return string Url of application report
     */
    public static function getReportUrl($applicationId, $referenceId = null) {
        $referenceId = $referenceId ?: self::getReferenceId();
        return self::http()->post('/api/smartmove/application/reportUrl', compact('applicationId', 'referenceId'));
    }
}
