<?php

declare(strict_types=1);

namespace SofortPay;

use Ramsey\Uuid\UuidInterface;
use SofortPay\Response\Response;
use SofortPay\Request\InitializePaymentRequest;

/**
 * Please note that sofortpay is a Payment Initiation Service,
 * which means that your client can initiate a payment with their bank account via this payment method.
 *
 * @see https://manage.sofort-pay.com/merchant/documentation/payments/api/payment
 */
interface SofortPayClientInterface
{
    /**
     * @param InitializePaymentRequest $request
     *
     * @return Response
     */
    public function initializePayment(InitializePaymentRequest $request): Response;

    /**
     * @param UuidInterface $uuid
     *
     * @return Response
     */
    public function getPayment(UuidInterface $uuid): Response;

    /**
     * @param UuidInterface $uuid
     */
    public function deletePayment(UuidInterface $uuid): void;
}
