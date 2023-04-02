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

use Nasustop\HapiSidecar\SidecarConnection;

class MongoConnection extends SidecarConnection
{
    public function reconnect(): bool
    {
        $this->connection = new Manager($this->config['socket_address']);
        $this->lastUseTime = microtime(true);
        return true;
    }
}
