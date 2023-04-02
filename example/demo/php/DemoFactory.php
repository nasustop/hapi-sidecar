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

use Nasustop\HapiSidecar\Exception\InvalidSidecarConnectionException;
use function make;

class DemoFactory
{
    protected string $name = 'demo';

    protected DemoProxy $proxies;

    public function __construct()
    {
        $this->proxies = make(DemoProxy::class, ['pool' => $this->name]);
    }

    public function get()
    {
        if (empty($this->proxies)) {
            throw new InvalidSidecarConnectionException('Invalid Mongo Sidecar proxy.');
        }

        return $this->proxies;
    }
}
