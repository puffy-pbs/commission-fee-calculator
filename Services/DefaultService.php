<?php

namespace Services;

use Base\Requester;

class DefaultService implements Service
{
    /** @var string $url */
    private string $url;

    /** @var bool $toJson */
    private bool $toJson;

    /**
     * @param string $url
     * @param bool $toJson
     */
    public function __construct(string $url, bool $toJson)
    {
        $this->url = $url;
        $this->toJson = $toJson;
    }

    /**
     * Get data from service
     * @return bool|mixed|string
     */
    public function getData()
    {
        // Get data
        $data = (new Requester())->getData($this->url);
        if ($this->toJson) {
            return json_decode($data, true);
        }

        // Return
        return $data;
    }
}
