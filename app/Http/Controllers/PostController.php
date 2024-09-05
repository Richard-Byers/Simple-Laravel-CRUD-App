<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function createPost(Request $request) {
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);

        Arr::set($incomingFields, 'title', strip_tags($incomingFields['title']));
        Arr::set($incomingFields, 'body', strip_tags($incomingFields['body']));
        Arr::set($incomingFields, 'user_id', auth()->id());
        Post::create($incomingFields);
        return redirect('/');
    }

    public function showEditScreen(Post $post) {
        if (auth()->user()->id !== $post['user_id']) {
            return redirect('/');
        }
            return view('edit-post', ['post' => $post]);
    }

    public function updatePost(Post $post, Request $request) {
        if (auth()->user()->id !== $post['user_id']) {
            return redirect('/');
            }

        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);

        Arr::set($incomingFields, 'title', strip_tags($incomingFields['title']));
        Arr::set($incomingFields, 'body', strip_tags($incomingFields['body']));

        $post->update($incomingFields);
        return redirect('/');
    }

    public function deletePost(Post $post) {
        if (auth()->user()->id === $post['user_id']) {
            $post->delete();
            }
            return redirect('/');
    }
}
