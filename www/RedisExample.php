<?php

namespace App;

use App\Helpers\ClientFactory;

class RedisExample
{
    private $client;

    public function __construct()
    {
        $this->client = ClientFactory::make('http://redis:6379/');
    }

    public function setValue($key, $value)
    {
        throw new \RuntimeException('RedisExample: требуется HTTP-прокси или phpredis.');
    }

    public function getValue($key)
    {
        throw new \RuntimeException('RedisExample: требуется HTTP-прокси или phpredis.');
    }
}
