<?php

declare(strict_types=1);
/**
 * This file is part of Hapi.
 *
 * @link     https://www.nasus.top
 * @document https://wiki.nasus.top
 * @contact  xupengfei@xupengfei.net
 * @license  https://github.com/nasustop/hapi-sidecar/blob/master/LICENSE
 */
namespace Nasustop\HapiSidecar\Example\mongo\php;

use Hyperf\Context\Context;
use Nasustop\HapiSidecar\Example\mongo\php\Pool\PoolFactory;
use Nasustop\HapiSidecar\Exception\InvalidSidecarConnectionException;

/**
 * @mixin Manager
 */
class MongoProxy
{
    public function __construct(protected PoolFactory $factory, protected string $pool = 'default')
    {
    }

    public function __call($name, $arguments)
    {
        // Get a connection from coroutine context or connection pool.
        $hasContextConnection = Context::has($this->getContextKey());
        $connection = $this->getConnection($hasContextConnection);

        try {
            $connection = $connection->getConnection();
            // Execute the command with the arguments.
            $result = $connection->{$name}(...$arguments);
        } finally {
            // Release connection.
            if (! $hasContextConnection) {
                // Should storage the connection to coroutine context, then use defer() to release the connection.
                Context::set($this->getContextKey(), $connection);
                defer(function () use ($connection) {
                    Context::set($this->getContextKey(), null);
                    $connection->release();
                });
            }
        }

        return $result;
    }

    /**
     * Get a connection from coroutine context, or from redis connection pool.
     * @param mixed $hasContextConnection
     */
    private function getConnection($hasContextConnection): MongoConnection
    {
        $connection = null;
        if ($hasContextConnection) {
            $connection = Context::get($this->getContextKey());
        }
        if (! $connection instanceof MongoConnection) {
            $pool = $this->factory->getPool($this->pool);
            $connection = $pool->get();
        }
        if (! $connection instanceof MongoConnection) {
            throw new InvalidSidecarConnectionException('The connection is not a valid MongoConnection.');
        }
        return $connection;
    }

    /**
     * The key to identify the connection object in coroutine context.
     */
    private function getContextKey(): string
    {
        return sprintf('sidecar.%s.connection', $this->pool);
    }
}
