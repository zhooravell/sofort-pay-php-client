<?php

declare(strict_types=1);

namespace SofortPay\Tests\Client;

use Money\Money;
use Money\Currency;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use SofortPay\SofortPayClient;
use PHPUnit\Framework\TestCase;
use SofortPay\ValueObject\APIKey;
use GuzzleHttp\Handler\MockHandler;
use SofortPay\ValueObject\TransactionID;
use GuzzleHttp\Exception\ClientException;
use SofortPay\Request\InitializePaymentRequest;
/**
 * Class InitializePaymentTest.
 */
class InitializePaymentTest extends TestCase
{
    /**
     * @var HandlerStack
     */
    private $successSidMockHandler;

    /**
     * @var HandlerStack
     */
    private $failSidMockHandler;

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \SofortPay\Exception\InvalidAPIKeyException
     * @throws \SofortPay\Exception\InvalidResponseBodyException
     * @throws \SofortPay\Exception\InvalidTransactionIDException
     */
    public function testSuccess()
    {
        $client = new Client(['handler' => $this->successSidMockHandler]);
        $client = new SofortPayClient($client, new APIKey('943f288f-1c48-43b2-a082-efd1ec8bdc9e'));

        $transactionId = '8fc55f31-2479-4b14-933c-0e7d62a84e37';
        $amount = new Money(105, new Currency('EUR'));

        $request = new InitializePaymentRequest(new TransactionID($transactionId), $amount);

        $response = $client->initializePayment($request);

        self::assertEquals('6d4cb746-ca71-442f-802a-0bdb7c0b2be1', $response->uuid);
        self::assertEquals('https://wizard.sofort-pay.com/wizard/c6e48a82-a524-403b-8760-b6d146519efa', $response->get('Payment-Form'));
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \SofortPay\Exception\InvalidAPIKeyException
     * @throws \SofortPay\Exception\InvalidResponseBodyException
     * @throws \SofortPay\Exception\InvalidTransactionIDException
     */
    public function testFail()
    {
        self::expectException(ClientException::class);

        $client = new Client(['handler' => $this->failSidMockHandler]);
        $client = new SofortPayClient($client, new APIKey('943f288f-1c48-43b2-a082-efd1ec8bdc9e'));

        $transactionId = '8fc55f31-2479-4b14-933c-0e7d62a84e37';
        $amount = new Money('105', new Currency('EUR'));

        $request = new InitializePaymentRequest(new TransactionID($transactionId), $amount);

        $client->initializePayment($request);
    }

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->successSidMockHandler = HandlerStack::create(new MockHandler([
            new Response(
                200,
                [
                    'X-Payment-Form' => 'https://wizard.sofort-pay.com/wizard/c6e48a82-a524-403b-8760-b6d146519efa',
                ],
                file_get_contents(__DIR__.'/DataFixtures/initialize-payment-response-success.json')
            ),
        ]));

        $this->failSidMockHandler = HandlerStack::create(new MockHandler([
            new Response(
                401,
                [],
                file_get_contents(__DIR__.'/DataFixtures/initialize-payment-response-success.json')
            ),
        ]));
    }
}
