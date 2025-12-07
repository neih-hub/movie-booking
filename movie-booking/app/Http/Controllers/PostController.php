<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::published()->orderBy('published_at','desc');
        
        // Filter by category if provided
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }
        
        $posts = $query->paginate(9);
        $currentCategory = $request->get('category');

        return view('posts.index', compact('posts', 'currentCategory'));
    }

    public function show($id)
    {
        $post = Post::published()->findOrFail($id);

        // increase views (atomic)
        $post->increment('views');

        // Lấy các bài viết liên quan (cùng category)
        $relatedPosts = Post::published()
            ->where('category', $post->category)
            ->where('id', '!=', $post->id)
            ->orderBy('published_at', 'desc')
            ->take(4)
            ->get();

        return view('posts.show', compact('post', 'relatedPosts'));
    }
}
