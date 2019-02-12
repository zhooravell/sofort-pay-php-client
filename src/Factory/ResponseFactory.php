<?php

declare(strict_types=1);

namespace SofortPay\Factory;

use SofortPay\Response\Response;
use function GuzzleHttp\json_decode;
use Psr\Http\Message\ResponseInterface;
use SofortPay\Exception\InvalidResponseBodyException;

/**
 * Class ResponseFactory.
 */
class ResponseFactory
{
    /**
     * @param ResponseInterface $response
     *
     * @return Response
     *
     * @throws InvalidResponseBodyException
     */
    public static function fromPsrResponse(ResponseInterface $response): Response
    {
        $decodedContents = json_decode($response->getBody()->getContents(), true);

        if (!is_array($decodedContents)) {
            throw InvalidResponseBodyException::wrongFormat();
        }

        self::addPaymentForm($response, $decodedContents);
        self::addRateLimitLimit($response, $decodedContents);
        self::addLocation($response, $decodedContents);
        self::addPayCodeResource($response, $decodedContents);
        self::addRateLimitRemaining($response, $decodedContents);

        return new Response($decodedContents);
    }

    /**
     * The entrypoint of the payment form. Use this URL to redirect your customer to the location of the payment form.
     *
     * @param ResponseInterface $response
     * @param $decodedContents
     */
    private static function addPaymentForm(ResponseInterface $response, &$decodedContents)
    {
        if ($response->hasHeader('X-Payment-Form') && count($response->getHeader('X-Payment-Form')) > 0) {
            $decodedContents['Payment-Form'] = $response->getHeader('X-Payment-Form')[0];
        }
    }

    /**
     * Request limit per minute.
     *
     * @param ResponseInterface $response
     * @param $decodedContents
     */
    private static function addRateLimitLimit(ResponseInterface $response, &$decodedContents)
    {
        if ($response->hasHeader('X-RateLimit-Limit') && count($response->getHeader('X-RateLimit-Limit')) > 0) {
            $decodedContents['RateLimit-Limit'] = intval($response->getHeader('X-RateLimit-Limit')[0]);
        }
    }

    /**
     * The number of requests left for the time window.
     *
     * @param ResponseInterface $response
     * @param $decodedContents
     */
    private static function addRateLimitRemaining(ResponseInterface $response, &$decodedContents)
    {
        if ($response->hasHeader('X-RateLimit-Remaining') && count($response->getHeader('X-RateLimit-Remaining')) > 0) {
            $decodedContents['RateLimit-Remaining'] = intval($response->getHeader('X-RateLimit-Remaining')[0]);
        }
    }

    /**
     * The location of the newly accepted resource.
     *
     * @param ResponseInterface $response
     * @param $decodedContents
     */
    private static function addLocation(ResponseInterface $response, &$decodedContents)
    {
        if ($response->hasHeader('Location') && count($response->getHeader('Location')) > 0) {
            $decodedContents['Location'] = $response->getHeader('Location')[0];
        }
    }

    /**
     * Resource location of the paycode object if exists. Header will only be available if a paycode is associated with the payment.
     *
     * @param ResponseInterface $response
     * @param $decodedContents
     */
    private static function addPayCodeResource(ResponseInterface $response, &$decodedContents)
    {
        if ($response->hasHeader('X-Paycode-Resource') && count($response->getHeader('X-Paycode-Resource')) > 0) {
            $decodedContents['Paycode-Resource'] = $response->getHeader('X-Paycode-Resource')[0];
        }
    }
}
