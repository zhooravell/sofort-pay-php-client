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
 * Class DeletePaymentTest.
 */
class DeletePaymentTest extends TestCase
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
     * @throws \Exception
     */
    public function testSuccess()
    {
        $client = new Client(['handler' => $this->successSidMockHandler]);
        $client = new SofortPayClient($client, new APIKey('943f288f-1c48-43b2-a082-efd1ec8bdc9e'));

        $this->assertNull($client->deletePayment(Uuid::uuid4()));
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \SofortPay\Exception\InvalidAPIKeyException
     * @throws \Exception
     */
    public function testFail()
    {
        $this->expectException(ClientException::class);

        $client = new Client(['handler' => $this->failSidMockHandler]);
        $client = new SofortPayClient($client, new APIKey('943f288f-1c48-43b2-a082-efd1ec8bdc9e'));

        $client->deletePayment(Uuid::uuid4());
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->successSidMockHandler = HandlerStack::create(new MockHandler([
            new Response(204),
        ]));

        $this->failSidMockHandler = HandlerStack::create(new MockHandler([
            new Response(
                404,
                [],
                file_get_contents(__DIR__.'/DataFixtures/delete-payment-response-fail.json')
            ),
        ]));
    }
}
