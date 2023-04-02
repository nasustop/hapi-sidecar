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

use Nasustop\HapiSidecar\IPC\SocketIPCSender;

class Manager extends SocketIPCSender
{
    public function Hello(string $msg)
    {
        return $this->call('DemoService.Hello', $msg);
    }

    public function Test(string $msg)
    {
        return $this->call('DemoService.Test', $msg);
    }
}
