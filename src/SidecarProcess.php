<?php

namespace Nasustop\HapiSidecar;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Process\AbstractProcess;
use Psr\Container\ContainerInterface;
use RuntimeException;

abstract class SidecarProcess extends AbstractProcess
{
    public string $name;

    protected string $pool;

    protected array $config;

    public function __construct(ContainerInterface $container)
    {
        if (empty($this->name) || empty($this->pool)) {
            throw new RuntimeException('边车服务进程的name或者pool不能为空');
        }
        parent::__construct($container);
        $this->config = $container->get(ConfigInterface::class)->get(sprintf('sidecar.%s', $this->pool));
        if (empty($this->config)) {
            throw new RuntimeException(sprintf('sidecar.%s 配置不能为空', $this->pool));
        }
    }

    public function isEnable($server): bool
    {
        return $this->config['enable'] ?? false;
    }

    public function bind($server): void
    {
        if (($this->config['build'] ?? false) && ! is_file($this->config['executable'])) {
            if (! is_dir($this->config['build_work_dir'])) {
                mkdir($this->config['build_work_dir']);
            }
            chdir($this->config['build_work_dir']);
            $command = 'go build -o ' . $this->config['executable'] . ' ' . $this->config['build_command'];
            exec($command, $output, $rev);
            if ($rev !== 0) {
                throw new RuntimeException(sprintf(
                    'Cannot build go files with command %s: %s',
                    $command,
                    implode(PHP_EOL, $output)
                ));
            }
        }
        parent::bind($server);
    }

}