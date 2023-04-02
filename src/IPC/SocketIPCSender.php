<?php

namespace Nasustop\HapiSidecar\IPC;

use Nasustop\HapiSidecar\Relay\CoroutineSocketRelay;
use Spiral\Goridge\RPC\RPC;
use Spiral\Goridge\SocketRelay;

class SocketIPCSender
{
    protected RPC $handler;
    public function __construct(string $address = '/tmp/sidecar.sock')
    {
        $this->handler = new RPC(new CoroutineSocketRelay($address, 0, SocketRelay::SOCK_UNIX));
    }

    public function __call($name, $arguments)
    {
        $this->handler->{$name}(...$arguments);
    }

    public function call(string $method, $payload, int $flags = 0)
    {
        return $this->handler->call($method, $payload, $flags);
    }
}