<?php

namespace Base;

class Requester
{
    /** @var int DEFAULT_CONNECT_TIMEOUT */
    private const DEFAULT_CONNECT_TIMEOUT = 120;

    /** @var int DEFAULT_TIMEOUT */
    private const DEFAULT_TIMEOUT = 120;

    /**
     * @param string $url
     * @return bool|string
     */
    public function getData(string $url) {
        // Initialize curl session
        $ch = curl_init($url);

        // Failed curl initialization
        if (!is_resource($ch)) {
            return false;
        }

        // Set curl options
        $curlOptionsSet = curl_setopt_array($ch, [
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => self::DEFAULT_CONNECT_TIMEOUT,
            CURLOPT_TIMEOUT => self::DEFAULT_TIMEOUT,
        ]);

        // Failed options set
        if (!$curlOptionsSet) {
            return false;
        }

        // Get the Data
        $data = curl_exec($ch);

        // Got no result
        if ($data === false) {
            return false;
        }

        // Close handler
        curl_close($ch);

        // Return result
        return $data;
    }
}
