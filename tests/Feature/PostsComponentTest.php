<?php

use App\Models\Post;
use Tests\TestCase;
use App\Jobs\SendEmails;
use App\Mail\PostCreated;
use App\Events\PostCreatedForListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;

use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\patchJson;
use function Pest\Laravel\postJson;

uses(TestCase::class);
uses()->group('component', 'posts');

test('GET /api/posts/{id} 200', function () {
    $post = Post::factory()->create();

    getJson("/api/posts/{$post->id}")
        ->assertStatus(200)
        ->assertJsonPath('data.id', $post->id);
});

test('GET /api/posts/{id} 404', function () {
    getJson("/api/posts/1")
        ->assertStatus(404);
});

test('GET /api/posts 200', function () {
    $count = 3;
    Post::factory()->count($count)->create();

    getJson("/posts?count=$count")
        ->assertStatus(200)
        ->assertJsonCount($count, 'data');
});

test('POST /api/posts 201', function ($writerId, $title, $text) {
    $request = [
        'writer_id' => $writerId,
        'title' => $title,
        'text' => $text,
    ];

    postJson("/api/posts", $request)
        ->assertStatus(201)
        ->assertJsonPath('data.writer_id', $writerId)
        ->assertJsonPath('data.title', $title)
        ->assertJsonPath('data.text', $text);
})->with([
    [1, 'Title1', 'Text1'],
    [2, 'Title2', 'Text2'],
]);

test('POST /api/posts 422', function () {
    postJson("/api/posts", [])
        ->assertStatus(422);
});

test('PATCH /api/posts/{id} 200', function () {
    $post = Post::factory()->create();

    $request = [
        'text' => 'Test',
    ];

    patchJson("/api/posts/{$post->id}", $request)
        ->assertStatus(200)
        ->assertJsonPath('data.text', $request['text']);
});

test('DELETE /api/posts/{id} 204', function () {
    $post = Post::factory()->create();

    deleteJson("/api/posts/{$post->id}")
        ->assertStatus(204);

    assertDatabaseMissing('posts', ['id' => $post->id]);
});

test('POST /api/posts:with-email 201', function () {
    Mail::fake();

    $request = [
        'writer_id' => 1,
        'title' => 'Test Title',
        'text' => 'Test text',
    ];

    postJson('/api/posts:with-email', $request)
        ->assertStatus(201)
        ->assertJsonPath('title', $request['title']);

    Mail::assertSent(PostCreated::class);
});

test('POST /api/posts:with-mail 422', function () {
    postJson('/api/posts:with-email', [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['writer_id', 'title', 'text']);
});

test('POST /api/posts:with-email-async 201', function () {
    Queue::fake();

    $request = [
        'writer_id' => 1,
        'title' => 'Test Title',
        'text' => 'Test text',
    ];

    postJson('/api/posts:with-email-async', $request)
        ->assertStatus(201)
        ->assertJsonPath('title', $request['title']);

    Queue::assertPushed(SendEmails::class);
});

test('POST /api/posts:with-email-async 422', function () {
    postJson('/api/posts:with-email-async', [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['writer_id', 'title', 'text']);
});

test('POST /api/posts:with-email-e-l 201', function () {
    Event::fake();

    $request = [
        'writer_id' => 1,
        'title' => 'Test Title',
        'text' => 'Test text',
    ];

    postJson('/api/posts:with-email-e-l', $request)
        ->assertStatus(201)
        ->assertJsonPath('title', $request['title']);

    Event::assertDispatched(PostCreated::class);
});

test('POST /api/posts:with-email-e-l 422', function () {
    postJson('/api/posts:with-email-e-l', [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['writer_id', 'title', 'text']);
});

test('POST /api/posts:with-email-s 201', function () {
    Event::fake();

    $request = [
        'writer_id' => 1,
        'title' => 'Test Title',
        'text' => 'Test text',
    ];

    postJson('/api/posts:with-email-s', $request)
        ->assertStatus(201)
        ->assertJsonPath('title', $request['title']);

    Event::assertDispatched(PostCreatedForListener::class);
});

test('POST /api/posts:with-email-s 422', function () {
    Event::fake();

    $request = [
        'writer_id' => 1,
        'title' => 'Test Title',
        'text' => 'Test text',
    ];

    postJson('/api/posts:with-email-s', $request)
        ->assertStatus(422)
        ->assertJsonPath('title', $request['title']);

    Event::assertDispatched(PostCreatedForListener::class);
});
