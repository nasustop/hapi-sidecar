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
namespace Nasustop\HapiSidecar\Relay;

use Exception;
use Spiral\Goridge\Exception\HeaderException;
use Spiral\Goridge\Exception\RelayException;
use Spiral\Goridge\Frame;
use Spiral\Goridge\SocketRelay;
use Swoole\Coroutine\Socket;

class CoroutineSocketRelay extends SocketRelay
{
    protected Socket $socket;

    public function waitFrame(): Frame
    {
        $this->connect();

        $header = $this->socket->recv(12);

        if (strlen($header) !== 12) {
            throw new HeaderException('Unable to read frame header');
        }

        $parts = Frame::readHeader($header);

        // total payload length
        $payload = '';
        $length = $parts[1] * 4 + $parts[2];

        while ($length > 0) {
            $buffer = $this->socket->recv($length);

            if ($buffer === false) {
                throw new HeaderException('Unable to read payload from socket');
            }

            $payload .= $buffer;
            $length -= strlen($buffer);
        }

        return Frame::initFrame($parts, $payload);
    }

    public function send(Frame $frame): void
    {
        $this->connect();

        $body = Frame::packFrame($frame);

        $this->socket->send($body);
    }

    public function isConnected(): bool
    {
        return ! empty($this->socket);
    }

    public function connect(int $retries = self::RECONNECT_RETRIES, int $timeout = self::RECONNECT_TIMEOUT): bool
    {
        if ($this->isConnected()) {
            return true;
        }

        $this->socket = $this->createSocket();

        if (empty($this->socket)) {
            throw new RelayException("Unable to create socket {$this}");
        }
        try {
            // Port type needs to be int, so we convert null to 0
            if ($this->socket->connect($this->getAddress()) === false) {
                throw new RelayException(sprintf('%s (%s)', $this->socket->errMsg, $this->socket->errCode));
            }
        } catch (Exception $e) {
            throw new RelayException("unable to establish connection {$this}: {$e->getMessage()}");
        }

        return true;
    }

    public function close(): void
    {
        if (! $this->isConnected()) {
            throw new RelayException("Unable to close socket '{$this}', socket already closed");
        }

        $this->socket->close();
        unset($this->socket);
    }

    private function createSocket(): Socket
    {
        return new Socket(AF_UNIX, SOCK_STREAM, 0);
    }
}
