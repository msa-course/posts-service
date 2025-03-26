<?php

namespace App\Http\Controllers;

use App\Events\PostCreated as PostCreatedEvent;
use App\Events\PostCreatedForListener;
use App\Jobs\SendEmails;
use App\Mail\PostCreated;
use App\Models\Post;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PostsController extends Controller
{
    public function get($id)
    {
        $post = Post::findOrFail($id);

        return response()->json($post);
    }

    public function getWithCache($id)
    {
        $cacheKey = 'posts' . $id;
        $post = Cache::get($cacheKey, function () use ($id, $cacheKey) {
            $post = Post::findOrFail($id);

            Cache::put($cacheKey, $post, CarbonInterval::minute(15));

            return $post;
        });

        return response()->json($post);
    }

    public function getWithTagCache($id)
    {
        $post = Cache::tags('posts')
            ->get($id, function () use ($id){
                $post = Post::findOrFail($id);

                Cache::tags('posts')->put($id, $post, CarbonInterval::minute(15));

                return $post;
            });

        return response()->json($post);
    }

    public function list()
    {
        $posts = Post::all();

        return response()->json($posts);
    }

    public function create(Request $request)
    {
        $request->validate([
            'writer_id' => ['required'],
            'title' => ['required', 'string', 'max:255'],
            'text' => ['required', 'string'],
        ]);

        $post = Post::create($request->all());

        return response()->json($post, 201);
    }

    public function createAndSendEmail(Request $request)
    {
        $request->validate([
            'writer_id' => ['required'],
            'title' => ['required', 'string', 'max:255'],
            'text' => ['required', 'string'],
        ]);

        $post = Post::create($request->all());

        Mail::to('admin@example.com')->send(new PostCreated($post));

        return response()->json($post, 201);
    }

    public function createAndSendEmailAsync(Request $request)
    {
        $request->validate([
            'writer_id' => ['required'],
            'title' => ['required', 'string', 'max:255'],
            'text' => ['required', 'string'],
        ]);

        $post = Post::create($request->all());

        SendEmails::dispatch($post);

        return response()->json($post, 201);
    }

    public function createAndSendEmailEL(Request $request)
    {
        $request->validate([
            'writer_id' => ['required'],
            'title' => ['required', 'string', 'max:255'],
            'text' => ['required', 'string'],
        ]);

        $post = Post::create($request->all());

        PostCreatedEvent::dispatch($post);

        return response()->json($post, 201);
    }

    public function createAndSendEmailS(Request $request)
    {
        $request->validate([
            'writer_id' => ['required'],
            'title' => ['required', 'string', 'max:255'],
            'text' => ['required', 'string'],
        ]);

        $post = Post::create($request->all());

        PostCreatedForListener::dispatch($post);

        return response()->json($post, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'writer_id' => ['required'],
            'title' => ['required', 'string', 'max:255'],
            'text' => ['required', 'string'],
        ]);

        $post = Post::findOrFail($id);
        $post->update($request->all());

        return response()->json($post);
    }

    public function delete($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        Log::debug('Post deleted', ['post' => $id]);

        return response()->json(null, 204);
    }
}
