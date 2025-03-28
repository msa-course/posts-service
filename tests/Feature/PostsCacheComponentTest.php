<?php

use App\Models\Post;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

use function Pest\Laravel\getJson;

uses(TestCase::class);
uses()->group('component', 'posts', 'cache');

beforeEach(function () {
    Cache::clear(); // Очищаем кэш перед каждым тестом
});

test('GET /api/posts/{id}:with-cache', function () {
    $post = Post::factory()->create();

    $this->getJson("/api/posts/{$post->id}/cache")
        ->assertStatus(200)
        ->assertJson($post->toArray());

    // Проверяем, что данные появились в кэше
    $cacheKey = 'posts' . $post->id;
    expect(Cache::has($cacheKey))->toBeTrue();

    // Проверяем, что данные из кэша совпадают
    $cachedPost = Cache::get($cacheKey);
    expect($cachedPost->id)->toBe($post->id);
});

test('GET /api/posts/{id}:with-tag-cache', function () {
    $post = Post::factory()->create();

    getJson("/api/posts/{$post->id}/with-tag-cache")
        ->assertStatus(200)
        ->assertJson($post->toArray());

    // Проверяем, что данные появились в кэше с тегами
    $cachedPost = Cache::tags('posts')->get($post->id);
    expect($cachedPost)->not()->toBeNull()
        ->and($cachedPost->id)->toBe($post->id);
});
