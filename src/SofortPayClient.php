<?php

declare(strict_types=1);

namespace SofortPay;

use GuzzleHttp\RequestOptions;
use Ramsey\Uuid\UuidInterface;
use GuzzleHttp\ClientInterface;
use SofortPay\Response\Response;
use SofortPay\ValueObject\APIKey;
use SofortPay\Factory\ResponseFactory;
use GuzzleHttp\Exception\GuzzleException;
use SofortPay\Request\InitializePaymentRequest;
use SofortPay\Exception\InvalidResponseBodyException;

/**
 * API consists of 6 entrypoints which consume and respond in the content type application/json.
 *
 * @see https://manage.sofort-pay.com/merchant/documentation/payments/api/payment
 */
class SofortPayClient implements SofortPayClientInterface
{
    const BASE_URL = 'https://api.sofort-pay.com';

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var APIKey
     */
    private $APIKey;

    /**
     * @param ClientInterface $client
     * @param APIKey          $APIKey
     */
    public function __construct(ClientInterface $client, APIKey $APIKey)
    {
        $this->client = $client;
        $this->APIKey = $APIKey;
    }

    /**
     * {@inheritdoc}
     *
     * @throws GuzzleException
     * @throws InvalidResponseBodyException
     */
    public function initializePayment(InitializePaymentRequest $request): Response
    {
        $response = $this->request('/api/v1/payments', 'POST', $request->getPayload());

        return ResponseFactory::fromPsrResponse($response);
    }

    /**
     * {@inheritdoc}
     *
     * @throws GuzzleException
     * @throws InvalidResponseBodyException
     */
    public function getPayment(UuidInterface $uuid): Response
    {
        $response = $this->request(sprintf('/api/v1/payments/%s', $uuid));

        return ResponseFactory::fromPsrResponse($response);
    }

    /**
     * {@inheritdoc}
     *
     * @throws GuzzleException
     */
    public function deletePayment(UuidInterface $uuid): void
    {
        $this->request(sprintf('/api/v1/payments/%s', $uuid), 'DELETE');
    }

    /**
     * @param string $entryPoint
     * @param string $method
     * @param array  $options
     *
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function request($entryPoint, $method = 'GET', array $options = [])
    {
        return $this->client->request($method, self::BASE_URL.'/'.ltrim($entryPoint, '/'), [
            RequestOptions::HEADERS => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => sprintf('Bearer %s', $this->APIKey),
            ],
            RequestOptions::JSON => $options,
        ]);
    }
}
