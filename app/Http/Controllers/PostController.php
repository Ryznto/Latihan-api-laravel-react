<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    /**
     * Constructor untuk apply middleware ke controller
     */
    public function __construct()
    {
        // Apply middleware 'auth:sanctum' ke semua method kecuali index dan show
        $this->middleware('auth:sanctum')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Post::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required'
        ]);

        // Menggunakan user yang terautentikasi untuk membuat post
        $post = $request->user()->posts()->create($validate);

        return $post;
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return $post;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {

        Gate::authorize('modify', $post);    

        $fields = $request->validate([  /////////////////////
            'title' => 'required|max:255',
            'body' => 'required'
        ]);

        $post->update($fields);          //////////////////

        return $post;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        Gate::authorize('modify', $post);    
        
        $post->delete();

        return ['message' => 'Post was deleted!'];
    }
}
