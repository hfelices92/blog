<x-layouts.app>

    <ul class="flex flex-col gap-4 mb-4">
        @foreach ($posts as $post)
            <li>
                <article class="bg-white shadow-md rounded-md p-6 mb-6 ">
                    <img class="h-72 w-full object-cover object-center" src="{{ $post->image }}" alt="">

                    <h2 class="text-2xl font-bold mb-2">
                        <a href="{{ route('posts.show', $post) }}">

                            {{ $post->title }}
                        </a>

                    </h2>
                    <div>

                        <p class="text-gray-700 mb-4">
                            {{ $post->excerpt }}
                        </p>
                        <p class="text-sm text-gray-500">
                            Published on {{ $post->published_at->format('F j, Y') }}
                        </p>
                        
                    </div>
                </article>
            </li>
        @endforeach
    </ul>
    <div class="mt-4  w-6/12" >
        {{ $posts->links() }}
    </div>
</x-layouts.app>
