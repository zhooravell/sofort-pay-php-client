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
        $this->expectException(InvalidUrlException::class);
        $this->expectExceptionMessage('"" is not a valid url.');

        new Url('');
    }

    /**
     * @throws InvalidUrlException
     */
    public function testInvalidMaxLength()
    {
        $this->expectException(InvalidUrlException::class);
        $this->expectExceptionMessage('URL is too long. It should have 2048 characters or less.');

        new Url(str_repeat('a', 2500));
    }

    /**
     * @throws InvalidUrlException
     */
    public function testSuccess()
    {
        $url = 'https://api.com';
        $baseApiUrl = new Url($url);

        $this->assertEquals($url, (string) $baseApiUrl);
    }

    /**
     * @throws InvalidUrlException
     */
    public function testInvalidUrl()
    {
        $this->expectException(InvalidUrlException::class);
        $this->expectExceptionMessage('"localhost" is not a valid url.');

        new Url('localhost');
    }
}
