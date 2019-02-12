<?php

declare(strict_types=1);

namespace SofortPay\ValueObject;

use SofortPay\Exception\InvalidUrlException;
use SofortPay\ValueObject\Traits\ValueToStringTrait;

/**
 * Value object for url.
 * Used for:
 * * Return URL if a transcation was successful
 * * Return URL if a transcation was aborted
 * * URL to notify an endpoint about transaction events.
 *
 * @see https://martinfowler.com/bliki/ValueObject.html
 * @see https://en.wikipedia.org/wiki/Uniform_Resource_Identifier
 * @see https://manage.sofort-pay.com/merchant/documentation/payments/api/payment/integration
 */
class Url
{
    private const MAX_LENGTH = 2048;

    use ValueToStringTrait;

    /**
     * @param $value
     *
     * @throws InvalidUrlException
     */
    public function __construct($value)
    {
        $value = trim($value);

        if (mb_strlen($value) > self::MAX_LENGTH) {
            throw InvalidUrlException::invalidMaxLength(self::MAX_LENGTH);
        }

        $value = filter_var($value, FILTER_SANITIZE_URL);

        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            throw InvalidUrlException::invalidURL($value);
        }

        $this->value = $value;
    }
}
