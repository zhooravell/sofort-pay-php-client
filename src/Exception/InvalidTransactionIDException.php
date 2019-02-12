<?php

declare(strict_types=1);

namespace SofortPay\Exception;

/**
 * Class InvalidTransactionIDException.
 */
final class InvalidTransactionIDException extends \Exception implements SofortPayException
{
    /**
     * @return InvalidTransactionIDException
     */
    public static function emptyTransactionID()
    {
        return new self('Transaction ID should not be blank.');
    }
}
