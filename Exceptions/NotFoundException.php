<?php

/**
 * Class NotFoundException
 * 
 * Thrown when requesting data or ressource that cannot be found
 */
class NotFoundException extends BaseException
{
    /**
    * Internal application error code
    */
    private const CODE = 0;
    /**
     * HTTP Status Code for API-Responses
     */
    private const HTTP_ERROR_CODE = 404;
    /**
     * Unique error code identifier
     */
    private const ERROR_CODE = "NOT_FOUND";
        
    /**
     * NotFoundException Constructor
     *
     * @param string $message Optional custom message
     *
     */
    public function __construct(string $message = "Resource not found")
    {
        parent::__construct($message,self::CODE,self::HTTP_ERROR_CODE,self::ERROR_CODE);
    }
}