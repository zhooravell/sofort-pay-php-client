<?php

declare(strict_types=1);

namespace SofortPay\Tests\Client;

use Ramsey\Uuid\Uuid;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use SofortPay\SofortPayClient;
use PHPUnit\Framework\TestCase;
use SofortPay\ValueObject\APIKey;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Exception\ClientException;

/**
 * Class GetPaymentTest.
 */
class GetPaymentTest extends TestCase
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
     * @throws \Exception
     */
    public function testSuccess()
    {
        $client = new Client(['handler' => $this->successSidMockHandler]);
        $client = new SofortPayClient($client, new APIKey('943f288f-1c48-43b2-a082-efd1ec8bdc9e'));

        $response = $client->getPayment(Uuid::uuid4());

        self::assertEquals('6d4cb746-ca71-442f-802a-0bdb7c0b2be1', $response->uuid);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \SofortPay\Exception\InvalidAPIKeyException
     * @throws \SofortPay\Exception\InvalidResponseBodyException
     * @throws \Exception
     */
    public function testFail()
    {
        self::expectException(ClientException::class);

        $client = new Client(['handler' => $this->failSidMockHandler]);
        $client = new SofortPayClient($client, new APIKey('943f288f-1c48-43b2-a082-efd1ec8bdc9e'));

        $client->getPayment(Uuid::uuid4());
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
                file_get_contents(__DIR__.'/DataFixtures/get-payment-response-success.json')
            ),
        ]));

        $this->failSidMockHandler = HandlerStack::create(new MockHandler([
            new Response(
                404,
                [],
                file_get_contents(__DIR__.'/DataFixtures/get-payment-response-fail.json')
            ),
        ]));
    }
}
