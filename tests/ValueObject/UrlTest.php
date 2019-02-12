<?php

declare(strict_types=1);

namespace SofortPay\Tests\ValueObject;

use SofortPay\ValueObject\Url;
use PHPUnit\Framework\TestCase;
use SofortPay\Exception\InvalidUrlException;

/**
 * Class UrlTest.
 */
class UrlTest extends TestCase
{
    /**
     * @throws InvalidUrlException
     */
    public function testEmpty()
    {
        self::expectException(InvalidUrlException::class);
        self::expectExceptionMessage('"" is not a valid url.');

        new Url('');
    }

    /**
     * @throws InvalidUrlException
     */
    public function testInvalidMaxLength()
    {
        self::expectException(InvalidUrlException::class);
        self::expectExceptionMessage('URL is too long. It should have 2048 characters or less.');

        new Url(str_repeat('a', 2500));
    }

    /**
     * @throws InvalidUrlException
     */
    public function testSuccess()
    {
        $url = 'https://api.com';
        $baseApiUrl = new Url($url);

        self::assertEquals($url, (string) $baseApiUrl);
    }

    /**
     * @throws InvalidUrlException
     */
    public function testInvalidUrl()
    {
        self::expectException(InvalidUrlException::class);
        self::expectExceptionMessage('"localhost" is not a valid url.');

        new Url('localhost');
    }
}
