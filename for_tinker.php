<?php

use App\Models\Post;
use App\Models\Writer;
use Illuminate\Support\Facades\DB;


DB::table('writers')->insert([
    ['name' => 'Иван', 'email' => 'ivan@test.com', 'active' => true],
    ['name' => 'Петр', 'email' => 'petr@test.com', 'active' => false],
]);

DB::table('posts')->insert([
    ['writer_id' => 1, 'title' => 'Умный пост', 'text' => 'Умный текст умного поста'],
    ['writer_id' => 1, 'title' => 'Ещё умный пост', 'text' => 'Умный текст ещё одного умного поста'],
    ['writer_id' => 2, 'title' => 'Весёлый пост', 'text' => 'Весёлый текст весёлого поста'],
]);

DB::table('writers')->truncate();
DB::table('posts')->truncate();

// 29
$writers = DB::select('select * from writers where active = ?', [true]);

$writers[0]->active;

// 32
DB::transaction(function () {
    $writerId = DB::table('writers')->insertGetId(['name' => 'Иван', 'email' => 'ivan@test.com']);
    DB::table('posts')->insert([
        ['writer_id' => $writerId, 'title' => 'Умный пост', 'text' => 'Умный текст умного поста']
    ]);
});

// 40
DB::table('writers')->insert([
    ['name' => 'Иван', 'email' => 'ivan@test.com', 'active' => true],
    ['name' => 'Петр', 'email' => 'petr@test.com', 'active' => false],
]);

DB::table('posts')->insert([
    ['writer_id' => 1, 'title' => 'Умный пост', 'text' => 'Умный текст умного поста'],
    ['writer_id' => 1, 'title' => 'Ещё умный пост', 'text' => 'Умный текст ещё одного умного поста'],
    ['writer_id' => 2, 'title' => 'Весёлый пост', 'text' => 'Весёлый текст весёлого поста'],
]);

$posts = DB::table('posts') \
    ->select(DB::raw('count(*) as count, writer_id')) \
    ->groupBy('writer_id') \
    ->get();

// 41
DB::table('writers')->insert([
    ['name' => 'Иван', 'email' => 'ivan@test.com', 'active' => true],
    ['name' => 'Петр', 'email' => 'petr@test.com', 'active' => false],
]);

DB::table('posts')->insert([
    ['writer_id' => 1, 'title' => 'Умный пост', 'text' => 'Умный текст умного поста'],
    ['writer_id' => 1, 'title' => 'Ещё умный пост', 'text' => 'Умный текст ещё одного умного поста'],
    ['writer_id' => 2, 'title' => 'Весёлый пост', 'text' => 'Весёлый текст весёлого поста'],
]);

DB::table('posts') \
    ->join('writers', 'writers.id', '=', 'posts.writer_id') \
    ->select(DB::raw('count(*) as count, posts.writer_id, writers.name')) \
    ->where('writers.active', '=', true) \
    ->groupBy('posts.writer_id', 'writers.name') \
    ->get();

// 45
DB::table('writers')->insert([
    ['name' => 'Иван', 'email' => 'ivan@test.com', 'active' => true],
    ['name' => 'Василий', 'email' => 'vasyarnd@test.com', 'active' => true],
    ['name' => 'Петр', 'email' => 'petr@test.com', 'active' => false],
]);

DB::table('writers') \
    ->where('active', '=', false) \
    ->orWhere(function ($query) { \
        $query->where('name', '=', 'Василий') \
            ->where('email', '=', 'vasyarnd@test.com'); \
    }) \
    ->get();

// 47
DB::table('writers')->insert([
    ['name' => 'Иван', 'email' => 'ivan@test.com', 'active' => true],
    ['name' => 'Василий', 'email' => 'vasyarnd@test.com', 'active' => true],
    ['name' => 'Петр', 'email' => 'petr@test.com', 'active' => false],
]);

$writers = DB::table('writers') \
    ->whereIn('id', [1, 2, 3]) \
    ->get();

// 62
DB::table('writers')->insert([
    ['name' => 'Иван', 'email' => 'ivan@test.com', 'active' => true],
    ['name' => 'Петр', 'email' => 'petr@test.com', 'active' => false],
]);

DB::table('posts')->insert([
    ['writer_id' => 1, 'title' => 'Умный пост', 'text' => 'Умный текст умного поста'],
    ['writer_id' => 1, 'title' => 'Ещё умный пост', 'text' => 'Умный текст ещё одного умного поста'],
    ['writer_id' => 2, 'title' => 'Весёлый пост', 'text' => 'Весёлый текст весёлого поста'],
]);

Post::query() \
    ->join('writers', 'writers.id', '=', 'posts.writer_id') \
    ->select(DB::raw('count(*) as count, posts.writer_id, writers.name')) \
    ->where('writers.active', '=', true) \
    ->groupBy('posts.writer_id', 'writers.name') \
    ->get();

$post = new Post();
$postsTableName = $post->getTable();

// 63
$writers = Writer::query() \
    ->active() \
    ->orderBy('name') \
    ->get();

// 64
Writer::query()->get();

$writer = Writer::find(1);

$writer->name = 'Сергей';

$writer->save();

Writer::query()->get();

// 65
$writer->delete();

Writer::query()->get();

// 67
$writer->posts;
