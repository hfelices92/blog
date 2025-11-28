<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $posts = Post::orderBy('published_at', 'desc')
            ->where('is_published', true)
            ->paginate(8);
        return view('welcome', compact('posts'));
    }
}
