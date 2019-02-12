<?php

declare(strict_types=1);

namespace SofortPay\ValueObject;

use SofortPay\Exception\InvalidLanguageException;
use SofortPay\ValueObject\Traits\ValueToStringTrait;

/**
 * Value object for language.
 *
 * @see https://en.wikipedia.org/wiki/ISO_639-1
 * @see https://manage.sofort-pay.com/merchant/documentation/payments/api/payment/integration
 */
class Language
{
    use ValueToStringTrait;

    /**
     * @param string $value
     *
     * @throws InvalidLanguageException
     */
    public function __construct($value)
    {
        $value = trim($value);
        $value = mb_strtolower($value);

        if (!array_key_exists($value, getSofortPaySupportsLanguages())) {
            throw InvalidLanguageException::invalidValue();
        }

        $this->value = $value;
    }
}
