<?php

declare(strict_types=1);

namespace SofortPay\Tests\ValueObject;

use PHPUnit\Framework\TestCase;
use SofortPay\ValueObject\TransactionID;
use SofortPay\Exception\InvalidTransactionIdException;

/**
 * Class TransactionIdTest.
 */
class TransactionIdTest extends TestCase
{
    /**
     * @throws InvalidTransactionIdException
     */
    public function testSuccess()
    {
        $value = 'test123';
        $transactionId = new TransactionID($value);

        self::assertEquals($value, (string) $transactionId);
    }

    /**
     * @throws InvalidTransactionIdException
     */
    public function testEmptyValue()
    {
        self::expectException(InvalidTransactionIdException::class);
        self::expectExceptionMessage('Transaction ID should not be blank.');

        new TransactionID(' ');
    }
}
