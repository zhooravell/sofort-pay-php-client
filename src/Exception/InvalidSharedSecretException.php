<?php

declare(strict_types=1);

namespace SofortPay\Exception;

/**
 * Class InvalidSharedSecretException.
 */
final class InvalidSharedSecretException extends \Exception implements SofortPayException
{
    /**
     * @return InvalidSharedSecretException
     */
    public static function emptySharedSecret()
    {
        return new self('WebHook shared secret should not be blank.');
    }
}
