<?php

namespace SofortPay\Response;

use SofortPay\Exception\ResponseDataException;

/**
 * Class to represent response data.
 * Provides two approaches:
 * * "dot" notation - $res->get('sender.iban');
 * * property access - $res->currency_id;.
 *
 * @property float  $amount
 * @property string $uuid
 * @property string $currency_id
 * @property string $purpose
 * @property string $language
 * @property string $success_url
 * @property string $webhook_url
 * @property string $abort_url
 * @property string $payform_code
 * @property bool   $testmode
 * @property array  $metadata
 * @property array  $sender
 * @property array  $recipient
 */
final class Response
{
    /**
     * @var array
     */
    private $data;

    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * Get an item using "dot" notation.
     * Examples: $res->get("user.id");.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (0 === count($this->data)) {
            return $default;
        }

        if (null === $key || empty($key)) {
            return $this->data;
        }

        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        if (false === strpos($key, '.')) {
            return $default;
        }

        $array = $this->data;

        foreach (explode('.', $key) as $segment) {
            if (is_array($array) && array_key_exists($segment, $array)) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }

        return $array;
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        return null;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        return array_key_exists($name, $this->data);
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @throws ResponseDataException
     */
    public function __set($name, $value)
    {
        throw ResponseDataException::reedOnlyMode();
    }
}
