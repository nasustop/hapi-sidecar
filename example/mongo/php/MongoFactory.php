<?php

namespace Nasustop\HapiSidecar\Example\mongo\php;

use Hyperf\Contract\ConfigInterface;
use Nasustop\HapiSidecar\Exception\InvalidSidecarConnectionException;
use function make;

class MongoFactory
{
    protected string $name = 'mongo';

    protected MongoProxy $proxies;

    public function __construct()
    {
        $this->proxies = make(MongoProxy::class, ['pool' => $this->name]);
    }

    public function get(): MongoProxy
    {
        if (empty($this->proxies)) {
            throw new InvalidSidecarConnectionException('Invalid Mongo Sidecar proxy.');
        }

        return $this->proxies;
    }
}