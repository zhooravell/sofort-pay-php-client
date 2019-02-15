<?php

declare(strict_types=1);

namespace SofortPay\Exception;

/**
 * Class ResponseDataException.
 */
final class ResponseDataException extends \Exception implements SofortPayException
{
    /**
     * @return ResponseDataException
     */
    public static function reedOnlyMode()
    {
        return new self('Can\'t modify response data');
    }
}
