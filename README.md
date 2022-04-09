# flux-eco/global-stream

Stores cross domain events and publishes them to subscribed consumers. It is a very 
early stage of development and is primarily intended to illustrate the use of global streams. 
For large systems, apache kafka or similar is more suitable. 

The following example application demonstrates the usage:
https://github.com/flux-caps/todo-app

## Usage
.env
``` 
STREAM_STORAGE_CONFIG_ENV_PREFIX=STREAM_
STREAM_STORAGE_NAME=stream
STREAM_STORAGE_HOST=localhost
STREAM_STORAGE_DRIVER=Pdo_Mysql
STREAM_STORAGE_USER=user
STREAM_STORAGE_PASSWORD=password
STREAM_TABLE_NAME=stream
STREAM_STATE_SCHEMA_FILE=../schemas/State.yaml
```

schemas\domain\account.yaml
```
title: account
type: object
properties:
    aggregateId:
        type: string
        readOnly: true
    correlationId:
        type: string
        readOnly: true
    aggregateName:
        type: string
        const: todo
        readOnly: true
    sequence:
        type: integer
        readOnly: true
    createdDateTime:
        type: string
        format: date-time
        readOnly: true
    createdBy:
        type: string
        format: email
        readOnly: true
    changedDateTime:
        type: string
        format: date-time
        readOnly: true
    changedBy:
        type: string
        format: email
        readOnly: true
    rootObjectSchema:
        type: string
        const: v1
    rootObject:
        type: object
        properties:
            firstname:
                type: string
            lastname:
                type: string
```

example.php
```
<?php

require_once __DIR__ . '/../vendor/autoload.php';

FluxEco\DotEnv\Api::new()->load(__DIR__);

//initialize
fluxGlobalStream\initialize();


//publish state change
$correlationId = fluxValueObject\getNewUuid();
$createdBy = 'example@fluxlabs.ch';
$subject = 'AggregateRoot';
$subjectId = fluxValueObject\getNewUuid();
$subjectSequence = 1;
$subjectName = 'account';
$jsonObjectSchema = json_encode(yaml_parse(file_get_contents('schemas/domain/account.yaml')));
$eventName = 'aggregateRootCreated';
$currentState = json_encode([
    "firstname" => [
        "value" => "Emmett",
        "isEntityId" => false
    ],
    "lastname" => [
        "value" => "Brown",
        "isEntityId" => false
    ]
]);
fluxGlobalStream\publishStateChange(
    $correlationId,
    $createdBy,
    $subject,
    $subjectId,
    $subjectSequence,
    $subjectName,
    $jsonObjectSchema,
    $eventName,
    $currentState
);


//republish all states
fluxGlobalStream\republishAllStates(
    [$subjectName]
);
```

## Contributing :purple_heart:
Please ...
1. ... register an account at https://git.fluxlabs.ch
2. ... create pull requests :fire:


## Adjustment suggestions / bug reporting :feet:
Please ...
1. ... register an account at https://git.fluxlabs.ch
2. ... ask us for a Service Level Agreement: support@fluxlabs.ch :kissing_heart:
3. ... read and create issues