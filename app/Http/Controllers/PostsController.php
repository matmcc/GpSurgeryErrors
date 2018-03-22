<?php

namespace App\Http\Controllers;

use App\Post;
use App\Task;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->get();
        return view('posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
        //return view('posts.show');
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store()
    {
        $this->validate(request(), [
            'title' => 'required',
            'body' => 'required'
        ]);
        // alternate to below:
         Post::create(request(['title', 'body']));
        // then...
        // requires that fillable() or guarded() be set in Post model, otherwise MassAssignementError

        // Create a new post using the request data
        /*$post = new Post;

        $post->title = request('title');
        $post->body = request('body');

        // save to DB
        $post->save();
        */

        // redirect
        return redirect('/');

    }
}
