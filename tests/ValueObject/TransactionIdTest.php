<?php

declare(strict_types=1);

namespace SofortPay\Tests\ValueObject;

use PHPUnit\Framework\TestCase;
use SofortPay\ValueObject\TransactionID;
use SofortPay\Exception\InvalidTransactionIDException;

/**
 * Class TransactionIdTest.
 */
class TransactionIdTest extends TestCase
{
    /**
     * @throws InvalidTransactionIDException
     */
    public function testSuccess()
    {
        $value = 'test123';
        $transactionId = new TransactionID($value);

        $this->assertSame($value, (string) $transactionId);
    }

    /**
     * @throws InvalidTransactionIDException
     */
    public function testEmptyValue()
    {
        $this->expectException(InvalidTransactionIDException::class);
        $this->expectExceptionMessage('Transaction ID should not be blank.');

        new TransactionID(' ');
    }
}
