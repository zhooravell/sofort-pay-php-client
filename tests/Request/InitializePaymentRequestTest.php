<?php

declare(strict_types=1);

namespace SofortPay\Tests\Request;

use SofortPay\ValueObject\Url;
use PHPUnit\Framework\TestCase;
use SofortPay\ValueObject\Language;
use Money\Currencies\ISOCurrencies;
use Money\Parser\DecimalMoneyParser;
use SofortPay\ValueObject\TransactionID;
use SofortPay\Request\InitializePaymentRequest;

/**
 * Class InitializePaymentRequestTest.
 */
class InitializePaymentRequestTest extends TestCase
{
    /**
     * @throws \SofortPay\Exception\InvalidLanguageException
     * @throws \SofortPay\Exception\InvalidTransactionIdException
     * @throws \SofortPay\Exception\InvalidUrlException
     */
    public function test()
    {
        $parser = new DecimalMoneyParser(new ISOCurrencies());

        $request = new InitializePaymentRequest(
            new TransactionID('e45dcd16-030e-4e33-94e0-e34a097a7428'),
            $parser->parse('1000000.51', 'EUR')
        );

        self::assertEquals(
            [
                'purpose' => 'Order ID: e45dcd16-030e-4e33-94e0-e34a097a7428',
                'currency_id' => 'EUR',
                'amount' => 1000000.51,
                'metadata' => [
                    'transaction_id' => 'e45dcd16-030e-4e33-94e0-e34a097a7428',
                ],
            ],
            $request->getPayload()
        );

        $res = $request
            ->setAbortUrl(new Url('https://google.com/1'))
            ->setSuccessUrl(new Url('https://google.com/2'))
            ->setWebHookUrl(new Url('https://google.com/3'))
            ->setLanguage(new Language('en'))
        ;

        self::assertEquals(
            [
                'purpose' => 'Order ID: e45dcd16-030e-4e33-94e0-e34a097a7428',
                'currency_id' => 'EUR',
                'amount' => 1000000.51,
                'metadata' => [
                    'transaction_id' => 'e45dcd16-030e-4e33-94e0-e34a097a7428',
                ],
                'abort_url' => 'https://google.com/1',
                'success_url' => 'https://google.com/2',
                'webhook_url' => 'https://google.com/3',
                'language' => 'en',
            ],
            $request->getPayload()
        );

        self::assertInstanceOf(InitializePaymentRequest::class, $res);
    }
}
