<?php declare(strict_types=1);

namespace SofortPay\Request\Traits;

/**
 * Trait GetPayloadTrait.
 */
trait GetPayloadTrait
{
    /**
     * @var array
     */
    private $payload = [];

    /**
     * @return array
     */
    public function getPayload()
    {
        return $this->payload;
    }
}
