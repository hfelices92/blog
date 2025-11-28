<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function show(Post $post)
    {
        $relatedPosts = Post::where('id', '!=', $post->id)
            ->where('is_published', true)
            ->whereHas('tags', function ($query) use ($post) {
                $query->whereIn('tags.id', $post->tags->pluck('id'));
            })
            ->withCount([
                'tags as shared_tags_count' => function ($query) use ($post) {
                    $query->whereIn('tags.id', $post->tags->pluck('id'));
                }
            ])
            ->orderByDesc('shared_tags_count')
            ->take(4)
            ->get();

        if ($relatedPosts->count() < 4) {
            $additionalPosts = Post::where('id', '!=', $post->id)
                ->where('is_published', true)
                ->whereNotIn('id', $relatedPosts->pluck('id'))
                ->take(4 - $relatedPosts->count())
                ->get();

            $relatedPosts = $relatedPosts->concat($additionalPosts);
        }

        return view('posts.show', compact('post', 'relatedPosts'));
    }
}
