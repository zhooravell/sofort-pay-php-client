<?php

declare(strict_types=1);

namespace SofortPay\Exception;

/**
 * Class InvalidTransactionIdException.
 */
final class InvalidTransactionIdException extends \Exception implements SofortPayException
{
    /**
     * @return InvalidTransactionIdException
     */
    public static function emptyTransactionID()
    {
        return new self('Transaction ID should not be blank.');
    }
}
