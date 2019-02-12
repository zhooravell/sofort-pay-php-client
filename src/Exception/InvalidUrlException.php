<?php

declare(strict_types=1);

namespace SofortPay\Exception;

/**
 * Class InvalidUrlException.
 */
final class InvalidUrlException extends \Exception implements SofortPayException
{
    /**
     * @param string $url
     *
     * @return InvalidUrlException
     */
    public static function invalidURL(string $url): self
    {
        return new self(sprintf('"%s" is not a valid url.', $url));
    }

    /**
     * @param int $maxLength
     *
     * @return InvalidUrlException
     */
    public static function invalidMaxLength(int $maxLength): self
    {
        return new self(sprintf('URL is too long. It should have %d characters or less.', $maxLength));
    }
}
