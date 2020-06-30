<?php

declare(strict_types=1);

namespace SofortPay\Tests\Factory;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;
use SofortPay\Factory\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use PHPUnit\Framework\MockObject\MockObject;
use SofortPay\Exception\InvalidResponseBodyException;

/**
 * Data fixtures from https://manage.sofort-pay.com/merchant/documentation/payments/api/payment.
 */
class ResponseFactoryTest extends TestCase
{
    /**
     * @throws InvalidResponseBodyException
     */
    public function testInitializePaymentResponse()
    {
        $body = file_get_contents(__DIR__ .'/DataFixtures/initialize-payment-response.json');

        $headers = [
            'X-Payment-Form' => 'X-Payment-Form',
            'Location' => 'Location',
            'X-RateLimit-Limit' => '100',
            'X-RateLimit-Remaining' => '99',
        ];

        $result = ResponseFactory::fromPsrResponse(new Response(200, $headers, $body));

        $this->assertEquals(10.5, $result->get('amount'));
        $this->assertEquals('EUR', $result->get('currency_id'));
        $this->assertEquals('Order ID: 1234', $result->get('purpose'));
        $this->assertEquals('de', $result->get('language'));
        $this->assertEquals('https://example.com/success', $result->get('success_url'));
        $this->assertEquals('https://example.com/webhook', $result->get('webhook_url'));
        $this->assertEquals('https://example.com/abort', $result->get('abort_url'));
        $this->assertEquals('default', $result->get('payform_code'));
        $this->assertEquals('6d4cb746-ca71-442f-802a-0bdb7c0b2be1', $result->get('uuid'));
        $this->assertEquals('X-Payment-Form', $result->get('Payment-Form'));
        $this->assertEquals('Location', $result->get('Location'));
        $this->assertEquals(100, $result->get('RateLimit-Limit'));
        $this->assertEquals(99, $result->get('RateLimit-Remaining'));
        $this->assertEquals(
            [
                'key1' => 'value1',
                'key2' => 'value2',
                'key3' => 'value3',
            ],
            $result->get('metadata')
        );
        $this->assertEquals(
            [
                'holder' => 'John Doe',
                'iban' => 'DE04888888880087654321',
                'bic' => 'TESTDE88XXX',
                'bank_name' => 'Test Bank',
                'country_id' => 'DE',
            ],
            $result->get('sender')
        );

        $this->assertEquals(
            [
                'holder' => 'John Doe',
                'iban' => 'DE04888888880087654321',
                'bic' => 'TESTDE88XXX',
                'bank_name' => 'Test Bank',
                'country_id' => 'DE',
                'street' => 'string',
                'city' => 'string',
                'zip' => 'string',
            ],
            $result->get('recipient')
        );
    }

    /**
     * @throws InvalidResponseBodyException
     */
    public function testGetPaymentResponse()
    {
        $body = file_get_contents(__DIR__ . '/DataFixtures/get-payment-response.json');

        $headers = [
            'X-Paycode-Resource' => 'X-Paycode-Resource',
            'X-RateLimit-Limit' => '100',
            'X-RateLimit-Remaining' => '99',
        ];

        $result = ResponseFactory::fromPsrResponse(new Response(200, $headers, $body));

        $this->assertEquals(10.5, $result->get('amount'));
        $this->assertEquals('EUR', $result->get('currency_id'));
        $this->assertEquals('Order ID: 1234', $result->get('purpose'));
        $this->assertEquals('de', $result->get('language'));
        $this->assertEquals('https://example.com/success', $result->get('success_url'));
        $this->assertEquals('https://example.com/webhook', $result->get('webhook_url'));
        $this->assertEquals('https://example.com/abort', $result->get('abort_url'));
        $this->assertEquals('default', $result->get('payform_code'));
        $this->assertEquals('6d4cb746-ca71-442f-802a-0bdb7c0b2be1', $result->get('uuid'));
        $this->assertFalse($result->get('testmode'));
        $this->assertEquals('X-Paycode-Resource', $result->get('Paycode-Resource'));
        $this->assertEquals(100, $result->get('RateLimit-Limit'));
        $this->assertEquals(99, $result->get('RateLimit-Remaining'));
        $this->assertEquals(
            [
                'key1' => 'value1',
                'key2' => 'value2',
                'key3' => 'value3',
            ],
            $result->get('metadata')
        );
        $this->assertEquals(
            [
                'holder' => 'John Doe',
                'iban' => 'DE04888888880087654321',
                'bic' => 'TESTDE88XXX',
                'bank_name' => 'Test Bank',
                'country_id' => 'DE',
            ],
            $result->get('sender')
        );

        $this->assertEquals(
            [
                'holder' => 'John Doe',
                'iban' => 'DE04888888880087654321',
                'bic' => 'TESTDE88XXX',
                'bank_name' => 'Test Bank',
                'country_id' => 'DE',
                'street' => 'string',
                'city' => 'string',
                'zip' => 'string',
            ],
            $result->get('recipient')
        );
    }

    /**
     * @throws InvalidResponseBodyException
     */
    public function testNotJSON()
    {
        $this->expectException(\InvalidArgumentException::class);

        /** @var ResponseInterface|MockObject $response */
        $response = $this->createMock(ResponseInterface::class);
        /** @var StreamInterface|MockObject $body */
        $body = $this->createMock(StreamInterface::class);

        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($body)
        ;

        $body
            ->expects($this->once())
            ->method('getContents')
            ->willReturn('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><page>123</page>')
        ;

        ResponseFactory::fromPsrResponse($response);
    }

    /**
     * @throws InvalidResponseBodyException
     */
    public function testInvalidData()
    {
        $this->expectException(InvalidResponseBodyException::class);
        $this->expectExceptionMessage('Wrong response body format.');

        /** @var ResponseInterface|MockObject $response */
        $response = $this->createMock(ResponseInterface::class);
        /** @var StreamInterface|MockObject $body */
        $body = $this->createMock(StreamInterface::class);

        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($body)
        ;

        $body
            ->expects($this->once())
            ->method('getContents')
            ->willReturn('true')
        ;

        ResponseFactory::fromPsrResponse($response);
    }
}
