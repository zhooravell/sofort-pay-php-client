<?php

declare(strict_types=1);

namespace SofortPay\Exception;

/**
 * Class InvalidResponseBodyException.
 */
final class InvalidResponseBodyException extends \Exception implements SofortPayException
{
    /**
     * @return InvalidResponseBodyException
     */
    public static function wrongFormat()
    {
        return new self('Wrong response body format.');
    }
}
