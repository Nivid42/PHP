<?php

/**
 * Class ValidationException
 *
 * Thrown when provided data does not pass validation rules.
 */
class ValidationException extends BaseException
{
    /**
     * Internal application error code.
     */
    private const CODE = 0;

    /**
     * HTTP status code for API responses.
     */
    private const HTTP_ERROR_CODE = 400;

    /**
     * Unique error code identifier.
     */
    private const ERROR_CODE = 'NOT_VALID';

    /**
     * ValidationException constructor.
     *
     * @param string $message Optional custom message describing the validation error.
     */
    public function __construct(string $message = 'Data not valid')
    {
        parent::__construct(
            $message,
            self::CODE,
            self::HTTP_ERROR_CODE,
            self::ERROR_CODE
        );
    }
}
