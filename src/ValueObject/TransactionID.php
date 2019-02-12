<?php declare(strict_types=1);

namespace SofortPay\ValueObject;

use SofortPay\ValueObject\Traits\ValueToStringTrait;
use SofortPay\Exception\InvalidTransactionIDException;

/**
 * Value object for transaction id.
 * A unique reference or identification number.
 *
 * @see https://martinfowler.com/bliki/ValueObject.html
 * @see https://manage.sofort-pay.com/merchant/documentation/payments/api/payment/integration
 */
class TransactionID
{
    use ValueToStringTrait;

    /**
     * @param string $value
     *
     * @throws InvalidTransactionIDException
     */
    public function __construct($value)
    {
        $value = trim($value);

        if (empty($value)) {
            throw InvalidTransactionIDException::emptyTransactionID();
        }

        $this->value = $value;
    }
}
