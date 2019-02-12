<?php

declare(strict_types=1);

namespace SofortPay\Signature;

use SofortPay\ValueObject\SharedSecret;

/**
 * The signature is transmitted in a header with our request and is named X-Payload-Signature.
 * The header has the following structure: X-Payload-Signature: v1=<v1_signature_hash>;
 * The hashes are a keyed hash value using the HMAC method.
 */
class SignatureVerifier
{
    /**
     * @var SharedSecret
     */
    private $sharedSecret;

    /**
     * @param SharedSecret $sharedSecret
     */
    public function __construct(SharedSecret $sharedSecret)
    {
        $this->sharedSecret = $sharedSecret;
    }

    /**
     * @param string $headerContent  data from X-Payload-Signature header
     * @param string $webHookPayload data from request body (json)
     *
     * @return bool
     */
    public function verify($headerContent, $webHookPayload)
    {
        $signatureMatches = false;
        $signatures = [];

        foreach (explode('; ', $headerContent) as $versionedSignature) {
            $firstEqualPosition = strpos($versionedSignature, '=');
            $version = substr($versionedSignature, 0, $firstEqualPosition);
            $signature = substr($versionedSignature, $firstEqualPosition + 1);

            $signatures[$version] = $signature;
        }

        foreach ($signatures as $version => $signature) {
            if ($this->verifySignature($version, $signature, $webHookPayload)) {
                $signatureMatches = true;
                break;
            }
        }

        return $signatureMatches;
    }

    /**
     * @param string $version
     * @param string $signature
     * @param string $webHookPayload
     *
     * @return bool
     */
    private function verifySignature($version, $signature, $webHookPayload)
    {
        $algorithms = [
            'v1' => 'sha256',
        ];

        // get the algorithm for hash_hmac based on version and create hash over the payload with your shared secret
        return hash_hmac($algorithms[$version], $webHookPayload, strval($this->sharedSecret)) === $signature;
    }
}
