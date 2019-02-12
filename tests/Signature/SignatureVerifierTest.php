<?php

declare(strict_types=1);

namespace SofortPay\Tests\Signature;

use PHPUnit\Framework\TestCase;
use SofortPay\ValueObject\SharedSecret;
use SofortPay\Signature\SignatureVerifier;

/**
 * Class SignatureVerifierTest.
 */
class SignatureVerifierTest extends TestCase
{
    /**
     * @throws \SofortPay\Exception\InvalidSharedSecretException
     */
    public function testSuccess()
    {
        $sharedSecret = new SharedSecret('V;*s5ii@316w=HmuW1fPC:35?Js$5UH$');
        $verifier = new SignatureVerifier($sharedSecret);

        $xPayloadSignature = 'v1=b95ac0a6fbb0f868eb2e66fafbc79bc1e31995b0773b78de515c9c1dc5d7038a';
        $json = file_get_contents(__DIR__ . '/DataFixtures/success-signature-verify.json');

        self::assertTrue($verifier->verify($xPayloadSignature, $json));
    }

    /**
     * @throws \SofortPay\Exception\InvalidSharedSecretException
     */
    public function testFail()
    {
        $sharedSecret = new SharedSecret('test');
        $verifier = new SignatureVerifier($sharedSecret);

        $xPayloadSignature = 'v1=b95ac0a6fbb0f868eb2e66fafbc79bc1e31995b0773b78de515c9c1dc5d7038a';
        $json = file_get_contents(__DIR__ . '/DataFixtures/fail-signature-verify.json');

        self::assertFalse($verifier->verify($xPayloadSignature, $json));
    }
}
