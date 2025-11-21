<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $posts = Post::latest('id')->paginate(10);
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.posts.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:posts,slug',
            'category_id' => 'required|exists:categories,id',
        ]);

        $data['user_id'] = auth('web')->id();

        $post = Post::create($data);

        return redirect()->route('admin.posts.edit', compact('post'))
            ->with('swal', [
                'icon' => 'success',
                'title' => 'Post creado',
                'text' => 'El post ha sido creado exitosamente.',
            ])
        ;
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {   
        $tagIds = $post->tags->pluck('id')->toArray();
        $response = in_array(1,$tagIds);
       
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:posts,slug,' . $post->id,
            'category_id' => 'required|exists:categories,id',
            'is_published' => 'required|in:0,1',
            'excerpt' => 'required_if:is_published,1|string|max:255',
            'content' => 'required_if:is_published,1|string',
            'tags' => 'array',
            

        ]);

        
        $post->update($data);

        $tags = [];

        
        foreach($request->tags ?? [] as $tagName){
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $tags[] = $tag->id;
        }
        $post->tags()->sync($tags);

        return redirect()->route('admin.posts.edit', compact('post'))
            ->with('swal', [
                'icon' => 'success',
                'title' => 'Post actualizado',
                'text' => 'El post ha sido actualizado exitosamente.',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
