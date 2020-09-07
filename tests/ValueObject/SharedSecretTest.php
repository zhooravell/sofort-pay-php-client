<?php

declare(strict_types=1);

namespace SofortPay\Tests\ValueObject;

use PHPUnit\Framework\TestCase;
use SofortPay\ValueObject\SharedSecret;
use SofortPay\Exception\InvalidSharedSecretException;

/**
 * Class SharedSecretTest.
 */
class SharedSecretTest extends TestCase
{
    /**
     * @throws InvalidSharedSecretException
     */
    public function testSuccess()
    {
        $value = 'test123';
        $sharedSecret = new SharedSecret($value);

        $this->assertSame($value, (string) $sharedSecret);
    }

    /**
     * @throws InvalidSharedSecretException
     */
    public function testEmptyValue()
    {
        $this->expectException(InvalidSharedSecretException::class);
        $this->expectExceptionMessage('WebHook shared secret should not be blank.');

        new SharedSecret(' ');
    }
}
