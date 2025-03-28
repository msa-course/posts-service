<?php

use App\Http\Controllers\KafkaController;
use App\Services\KafkaProducerService;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

use function Pest\Laravel\postJson;

uses(TestCase::class);
uses()->group('component', 'kafka');

beforeEach(function () {
    Route::post('/api/send-kafka-message', [KafkaController::class, 'sendMessage']);
});

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
