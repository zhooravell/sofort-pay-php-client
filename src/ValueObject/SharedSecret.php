<?php declare(strict_types=1);

namespace SofortPay\ValueObject;

use SofortPay\ValueObject\Traits\ValueToStringTrait;
use SofortPay\Exception\InvalidSharedSecretException;

/**
 * Value object for webhook shared secret.
 * Before sending the webhook to server a signature will be created and added as header (x-payload-signature) to the request.
 *
 * @see https://martinfowler.com/bliki/ValueObject.html
 * @see https://manage.sofort-pay.com/merchant/documentation/payments/api/payment/integration
 */
class SharedSecret
{
    use ValueToStringTrait;

    /**
     * @param string $value
     *
     * @throws InvalidSharedSecretException
     */
    public function __construct($value)
    {
        $value = trim($value);

        if (empty($value)) {
            throw InvalidSharedSecretException::emptySharedSecret();
        }

        $this->value = $value;
    }
}
