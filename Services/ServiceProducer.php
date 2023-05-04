<?php

namespace Services;

class ServiceProducer
{
    /**
     * Create service
     * @param string $url
     * @param bool $toJson
     * @return Service
     */
    public static function create(string $url, bool $toJson): Service
    {
        return new DefaultService($url, $toJson);
    }
}
