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

use Nasustop\HapiSidecar\IPC\SocketIPCSender;

class Manager extends SocketIPCSender
{
    protected string $database;

    protected string $collection;

    public function Database(string $database): static
    {
        $this->database = $database;
        return $this;
    }

    public function Collection(string $collection): static
    {
        $this->collection = $collection;
        return $this;
    }

    public function encodeInsertData(array $document): bool|string
    {
        return json_encode([
            'database' => $this->database,
            'collection' => $this->collection,
            'document' => $document,
        ]);
    }

    public function encodeFilterData(array $filter): bool|string
    {
        return json_encode([
            'database' => $this->database,
            'collection' => $this->collection,
            'filter' => $filter,
        ]);
    }

    public function encodeUpdateData(array $filter, array $update): bool|string
    {
        return json_encode([
            'database' => $this->database,
            'collection' => $this->collection,
            'filter' => $filter,
            'update' => $update,
        ]);
    }

    public function InsertOne(array $data)
    {
        $payload = $this->encodeInsertData($data);
        return $this->call('MongoDB.InsertOne', $payload);
    }

    public function InsertMany(array $data)
    {
        $payload = $this->encodeInsertData($data);
        return $this->call('MongoDB.InsertMany', $payload);
    }

    public function DeleteOne(array $filter)
    {
        $payload = $this->encodeFilterData($filter);
        return $this->call('MongoDB.DeleteOne', $payload);
    }

    public function DeleteMany(array $filter)
    {
        $payload = $this->encodeFilterData($filter);
        return $this->call('MongoDB.DeleteMany', $payload);
    }

    public function Find(array $filter)
    {
        $payload = $this->encodeFilterData($filter);
        return $this->call('MongoDB.Find', $payload);
    }

    public function FindOne(array $filter)
    {
        $payload = $this->encodeFilterData($filter);
        return $this->call('MongoDB.FindOne', $payload);
    }

    public function UpdateOne(array $filter, array $update)
    {
        $payload = $this->encodeUpdateData($filter, $update);
        return $this->call('MongoDB.UpdateOne', $payload);
    }

    public function UpdateMany(array $filter, array $update)
    {
        $payload = $this->encodeUpdateData($filter, $update);
        return $this->call('MongoDB.UpdateMany', $payload);
    }
}
