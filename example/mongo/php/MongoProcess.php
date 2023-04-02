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

use Nasustop\HapiSidecar\SidecarProcess;

class MongoProcess extends SidecarProcess
{
    public string $name = 'MongoSidecar';

    protected string $pool = 'mongo';

    public function handle(): void
    {
        $uri = 'mongodb://';
        if (! empty($this->config['username']) && ! empty($this->config['password'])) {
            $uri .= $this->config['username'] . ':' . $this->config['password'] . '@';
        }
        $uri .= $this->config['host'] . ':' . $this->config['port'];
        $this->process->exec($this->config['executable'], [
            '-a', $this->config['socket_address'],
            '-h', $uri,
        ]);
    }
}
