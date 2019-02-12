<?php

declare(strict_types=1);

namespace SofortPay\Request;

use Money\Money;
use SofortPay\ValueObject\Url;
use SofortPay\ValueObject\Language;
use SofortPay\ValueObject\TransactionID;
use SofortPay\Request\Traits\GetPayloadTrait;
use SofortPay\Request\Traits\AmountFormatterTrait;

/**
 * Initialize a payment.
 *
 * @see https://manage.sofort-pay.com/merchant/documentation/payments/api/payment/integration
 */
class InitializePaymentRequest
{
    use GetPayloadTrait;
    use AmountFormatterTrait;

    /**
     * @param TransactionID $transactionId
     * @param Money         $amount
     */
    public function __construct(TransactionID $transactionId, Money $amount)
    {
        $currency = strval($amount->getCurrency());

        $this->payload = [
            'purpose' => sprintf('Order ID: %s', $transactionId),
            'currency_id' => mb_strtoupper($currency), // currency id of eur will fail and only upper case EUR is valid
            'amount' => $this->formatToFloat($amount),
            'metadata' => [
                'transaction_id' => strval($transactionId),
            ],
        ];
    }

    /**
     * @param Language $lang
     *
     * @return $this
     */
    public function setLanguage(Language $lang)
    {
        $this->payload['language'] = strval($lang);

        return $this;
    }

    /**
     * @param Url $url
     *
     * @return $this
     */
    public function setSuccessUrl(Url $url)
    {
        $this->payload['success_url'] = strval($url);

        return $this;
    }

    /**
     * @param Url $url
     *
     * @return $this
     */
    public function setAbortUrl(Url $url)
    {
        $this->payload['abort_url'] = strval($url);

        return $this;
    }

    /**
     * @param Url $url
     *
     * @return $this
     */
    public function setWebHookUrl(Url $url)
    {
        $this->payload['webhook_url'] = strval($url);

        return $this;
    }
}
