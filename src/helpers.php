<?php declare(strict_types=1);

if (!function_exists('getSofortPaySupportsLanguages')) {
    /**
     * @return array
     */
    function getSofortPaySupportsLanguages()
    {
        return include __DIR__.'/../resources/iso-6391-languages-sofort-supports.php';
    }
}
