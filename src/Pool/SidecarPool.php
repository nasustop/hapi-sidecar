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
namespace Nasustop\HapiSidecar\Pool;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Pool\Frequency;
use Hyperf\Pool\Pool;
use InvalidArgumentException;
use Psr\Container\ContainerInterface;

abstract class SidecarPool extends Pool
{
    protected array $config;

    public function __construct(protected ContainerInterface $container, protected string $pool)
    {
        $config = $container->get(ConfigInterface::class);
        $key = sprintf('sidecar.%s', $this->pool);
        if (! $config->has($key)) {
            throw new InvalidArgumentException(sprintf('config[%s] is not exist!', $key));
        }

        $this->config = $config->get($key);
        $options = $this->config['pool'] ?? [];

        $this->frequency = make(Frequency::class);

        parent::__construct($container, $options);
    }
}
