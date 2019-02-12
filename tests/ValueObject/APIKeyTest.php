<?php

declare(strict_types=1);

namespace SofortPay\Tests\ValueObject;

use PHPUnit\Framework\TestCase;
use SofortPay\ValueObject\APIKey;
use SofortPay\Exception\InvalidAPIKeyException;

/**
 * Class APIKeyTest.
 */
class APIKeyTest extends TestCase
{
    /**
     * @throws InvalidAPIKeyException
     */
    public function testSuccess()
    {
        $value = '51930bcb-0028-4320-8932-13731f21fc28';
        $APIKey = new APIKey($value);

        self::assertEquals($value, (string) $APIKey);
    }

    /**
     * @throws InvalidAPIKeyException
     */
    public function testInvalidValue()
    {
        self::expectException(InvalidAPIKeyException::class);
        self::expectExceptionMessage('API key should be valid UUID.');

        new APIKey('test');
    }
}
