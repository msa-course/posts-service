<?php

use App\Services\KafkaProducerService;
use Tests\TestCase;

use function Pest\Laravel\postJson;

uses(TestCase::class);
uses()->group('component', 'kafka');

test('POST send-kafka-message 200', function () {
    $producerMock = mock(KafkaProducerService::class)
        ->shouldReceive('produce')
        ->once()
        ->with('Test Kafka Message')
        ->andReturnNull()
        ->getMock();

    $this->app->instance(KafkaProducerService::class, $producerMock);

    postJson('/api/send-kafka-message', ['message' => 'Test Kafka Message'])
        ->assertStatus(200)
        ->assertJson([
            'status' => 'success',
            'message' => 'Message sent to Kafka',
        ]);
});

test('POST /api/send-kafka-message 500 on exception', function () {
    $producerMock = mock(KafkaProducerService::class)
        ->shouldReceive('produce')
        ->once()
        ->andThrow(new Exception('Kafka error'))
        ->getMock();

    $this->app->instance(KafkaProducerService::class, $producerMock);

    postJson('/api/send-kafka-message', ['message' => 'Test Kafka Message'])
        ->assertStatus(500)
        ->assertJson([
            'status' => 'error',
            'message' => 'Kafka error',
        ]);
});
