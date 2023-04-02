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
namespace Nasustop\HapiSidecar\Example\demo\php;

use Nasustop\HapiSidecar\SidecarProcess;

class DemoProcess extends SidecarProcess
{
    public string $name = 'DemoSidecar';

    protected string $pool = 'demo';

    public function handle(): void
    {
        $this->process->exec($this->config['executable'], ['-a', $this->config['socket_address']]);
    }
}
