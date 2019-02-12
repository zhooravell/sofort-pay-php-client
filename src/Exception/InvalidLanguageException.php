<?php

declare(strict_types=1);

namespace SofortPay\Exception;

/**
 * Class InvalidLanguageException.
 */
final class InvalidLanguageException extends \Exception implements SofortPayException
{
    /**
     * @return InvalidLanguageException
     */
    public static function invalidValue()
    {
        return new self('Not accepted language by Sofort Pay.');
    }
}
