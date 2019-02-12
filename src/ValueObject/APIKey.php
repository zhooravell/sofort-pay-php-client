<?php

declare(strict_types=1);

namespace SofortPay\ValueObject;

use Ramsey\Uuid\Uuid;
use SofortPay\Exception\InvalidAPIKeyException;
use SofortPay\ValueObject\Traits\ValueToStringTrait;

/**
 * Value object for APIKey.
 *
 * @see https://manage.sofort-pay.com/merchant/documentation/payments/api/payment/integration
 */
class APIKey
{
    use ValueToStringTrait;

    /**
     * @param string $value
     *
     * @throws InvalidAPIKeyException
     */
    public function __construct($value)
    {
        $value = trim($value);

        if (!Uuid::isValid($value)) {
            throw InvalidAPIKeyException::invalidValue();
        }

        $this->value = $value;
    }
}
