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
namespace Nasustop\HapiSidecar\Example\demo\php\Pool;

use Hyperf\Contract\ConnectionInterface;
use Nasustop\HapiSidecar\Example\demo\php\DemoConnection;
use Nasustop\HapiSidecar\Pool\SidecarPool;

class DemoPool extends SidecarPool
{
    protected function createConnection(): ConnectionInterface
    {
        return new DemoConnection($this->container, $this, $this->config);
    }
}
