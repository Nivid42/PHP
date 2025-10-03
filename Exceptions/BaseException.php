<?php

/**
 * Class BaseException (inherits from Exception)
 * 
 * Base exception class to be extended by other application exceptions.
 */
class BaseException extends \Exception
{ 
    protected ?int $httpCode;
    protected ?string $errorCode;
    
    /**
     * Constructor to initialize values.
     *
     * @param string $message The error message
     * @param int $code Optional internal code
     * @param int|null $httpCode Optional HTTP code, default null
     * @param string|null $errorCode Optional custom error code, default null
     * @param Throwable|null $previous Optional previous exception, default null
     */

    public function __construct(
        string $message,
        int $code = 0,
        ?int $httpCode = null,
        ?string $errorCode = null,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->httpCode = $httpCode;
        $this->errorCode = $errorCode;
    }
        
    /**
     * Get the HTTP code, or null if not set
     *
     * @return int|null
     */
    public function getHttpCode(): ?int
    {
        return $this->httpCode; // returns NULL if not set
    }
    
    /**
     * Get the Error code, or null if not set
     *
     * @return string|null
     */
    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }
    
    /**
     * Converts Exception into a structured Array
     *
     * @return array
     */
    public function toArray():array
    {
        return [
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
            'errorCode' => $this->getErrorCode(),
            'httpCode' => $this->getHttpCode(),
        ];
    }
}