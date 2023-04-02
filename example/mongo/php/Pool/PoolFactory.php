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
namespace Nasustop\HapiSidecar\Example\mongo\php\Pool;

use Hyperf\Di\Container;
use Psr\Container\ContainerInterface;

class PoolFactory
{
    /**
     * @var MongoPool[]
     */
    protected array $pools = [];

    public function __construct(protected ContainerInterface $container)
    {
    }

    public function getPool(string $pool): MongoPool
    {
        if (isset($this->pools[$pool])) {
            return $this->pools[$pool];
        }

        if ($this->container instanceof Container) {
            $poolObject = $this->container->make(MongoPool::class, ['pool' => $pool]);
        } else {
            $poolObject = new MongoPool($this->container, $pool);
        }
        return $this->pools[$pool] = $poolObject;
    }
}
