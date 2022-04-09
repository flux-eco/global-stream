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