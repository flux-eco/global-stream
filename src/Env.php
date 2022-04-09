<?php

namespace FluxEco\GlobalStream;

class Env
{
    const STREAM_STORAGE_CONFIG_ENV_PREFIX = 'STREAM_STORAGE_CONFIG_ENV_PREFIX';
    const STREAM_STORAGE_NAME = 'STREAM_STORAGE_NAME';
    const STREAM_TABLE_NAME = 'STREAM_TABLE_NAME';
    const STREAM_STATE_SCHEMA_FILE = 'STREAM_STATE_SCHEMA_FILE';

    private function __construct()
    {

    }

    public static function new() : self
    {
        return new self();
    }

    public function getStreamStorageConfigEnvPrefix() : string
    {
        return getEnv(self::STREAM_STORAGE_CONFIG_ENV_PREFIX);
    }

    public function getStreamStorageName() : string
    {
        return getEnv(self::STREAM_STORAGE_NAME);
    }

    public function getStreamTableName() : string
    {
        return getEnv(self::STREAM_TABLE_NAME);
    }

    public function getStreamStateSchemaFile() : string
    {
        return getEnv(self::STREAM_STATE_SCHEMA_FILE);
    }
}