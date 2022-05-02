<?php

namespace FluxEco\GlobalStream;

class Env
{
    const STREAM_STORAGE_CONFIG_ENV_PREFIX = 'STREAM_STORAGE_CONFIG_ENV_PREFIX';
    const STREAM_STORAGE_NAME = 'STREAM_STORAGE_NAME';
    const STREAM_TABLE_NAME = 'STREAM_TABLE_NAME';
    const STREAM_STATE_SCHEMA_FILE = 'STREAM_STATE_SCHEMA_FILE';
    const STREAM_PUBLISHED_MESSAGES_TABLE_NAME = 'STREAM_PUBLISHED_MESSAGES_TABLE_NAME';
    const STREAM_PUBLISHED_MESSAGES_SCHEMA_FILE = 'STREAM_PUBLISHED_MESSAGES_SCHEMA_FILE';
    const CHANNEL_CONFIG_FILE = 'CHANNEL_CONFIG_FILE';

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

    public function getStreamPublishedMessagesTableName() : string
    {
        return getEnv(self::STREAM_PUBLISHED_MESSAGES_TABLE_NAME);
    }

    public function getStreamPublishedMessagesSchemaFile() : string
    {
        return getEnv(self::STREAM_PUBLISHED_MESSAGES_SCHEMA_FILE);
    }

    public function getChannels() : array
    {
        return  yaml_parse(file_get_contents(getEnv(self::CHANNEL_CONFIG_FILE)));
    }

}