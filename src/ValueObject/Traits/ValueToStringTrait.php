<?php declare(strict_types=1);

namespace SofortPay\ValueObject\Traits;

/**
 * Class ValueToStringTrait.
 */
trait ValueToStringTrait
{
    /**
     * @var string
     */
    private $value;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }
}
