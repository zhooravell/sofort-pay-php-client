<?php

declare(strict_types=1);

namespace SofortPay\Exception;

/**
 * Class InvalidAPIKeyException.
 */
final class InvalidAPIKeyException extends \Exception implements SofortPayException
{
    /**
     * @return InvalidAPIKeyException
     */
    public static function invalidValue()
    {
        return new self('API key should be valid UUID.');
    }
}
