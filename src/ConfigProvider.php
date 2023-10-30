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
namespace Nasustop\HapiSidecar;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
            ],
            'commands' => [
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'publish' => [
                [
                    'id' => 'mongo',
                    'description' => 'The config for sidecar.',
                    'source' => __DIR__ . '/../publish/sidecar.php',
                    'destination' => BASE_PATH . '/config/autoload/sidecar.php',
                ],
            ],
        ];
    }

    public static function sidecar_address(string $path, bool $unique = true): string
    {
        $root = BASE_PATH . '/runtime/sidecar/' . $path;
        if (! is_dir($root)) {
            mkdir($root, 0755, true);
        }

        $executable = $appName = env('APP_NAME');
        if ($unique) {
            $executable = $appName . '_' . uniqid();
        }
        return $root . '/' . $executable;
    }
}
