<?php

namespace App\Observers;

use App\Models\Post;

class PostObserver
{
    /**
     * Handle the Post "creating" event.
     */
    public function updating(Post $post): void
    {
        if ($post->is_published == 1 && is_null($post->published_at)) {
            $post->published_at = now();
        }
    }
}
