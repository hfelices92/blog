<?php

namespace App\Http\Controllers\Admin;

use App\Events\UploadImage;
use App\Http\Controllers\Controller;
use App\Jobs\UploadPostImage;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Cloudinary\Cloudinary;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;


class PostController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('can:manage_posts'),
        ];
    }
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
        Gate::authorize('author', $post);
        $tagIds = $post->tags->pluck('id')->toArray();
        $response = in_array(1, $tagIds);

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
            'slug' => [
                Rule::requiredIf(fn() => !$post->is_published),
                'string',
                'max:255',
                Rule::unique('posts', 'slug')->ignore($post->id),
            ],
            'category_id' => 'required|exists:categories,id',
            'is_published' => 'required|in:0,1',

            'excerpt' => 'required_if:is_published,1|string|max:255',
            'content' => 'required_if:is_published,1|string',

            'tags' => 'array',
            'image' => 'nullable|image|max:4096',
        ]);


        /*
    |--------------------------------------------------------------------------
    | CLOUDINARY — redimensionar sin Intervention
    |--------------------------------------------------------------------------
    */
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // Guardar copia temporal en storage/app/tmp
            $tempName = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $tempPath = storage_path('app/tmp/' . $tempName);

            // Crear carpeta si no existe
            if (!file_exists(storage_path('app/tmp'))) {
                mkdir(storage_path('app/tmp'), 0775, true);
            }

            // Copiar archivo TEMPORAL a un archivo PERSISTENTE
            copy($file->getRealPath(), $tempPath);

            // Enviar al Job la ruta del archivo copiado
            //UploadPostImage::dispatch($post, $tempPath);

            // Disparar evento
            UploadImage::dispatch($post, $tempPath);
        }


        /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR POST
    |--------------------------------------------------------------------------
    */
        $post->update($data);


        /*
    |--------------------------------------------------------------------------
    | TAGS
    |--------------------------------------------------------------------------
    */
        $tags = [];
        foreach (($request->tags ?? []) as $tagName) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $tags[] = $tag->id;
        }
        $post->tags()->sync($tags);

        $post->refresh();
        /*
    |--------------------------------------------------------------------------
    | REDIRECCIÓN
    |--------------------------------------------------------------------------
    */
        return redirect()->route('admin.posts.edit', $post)
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
    Gate::authorize('author', $post);
    // 1️⃣ Inicializar Cloudinary
    $cloudinary = new Cloudinary([
        'cloud' => [
            'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
            'api_key'    => env('CLOUDINARY_API_KEY'),
            'api_secret' => env('CLOUDINARY_API_SECRET'),
        ]
    ]);

    // 2️⃣ Si el post tiene imagen → eliminar en Cloudinary
    if ($post->image_path) {

        // extraer public_id de una URL como:
        // https://res.cloudinary.com/xxx/image/upload/v123/posts/slug.jpg

        $parsed = parse_url($post->image_path, PHP_URL_PATH);
        $parts = explode('/', trim($parsed, '/'));

        $file   = end($parts);      // slug.jpg
        $folder = prev($parts);     // posts

        $publicId = $folder . '/' . pathinfo($file, PATHINFO_FILENAME);

        // eliminar en Cloudinary
        $cloudinary->uploadApi()->destroy($publicId);
    }

    // 3️⃣ Eliminar Post de la base de datos
    $post->delete();

    // 4️⃣ Redirección
    return redirect()->route('admin.posts.index')
        ->with('swal', [
            'icon' => 'success',
            'title' => 'Post eliminado',
            'text' => 'El post ha sido eliminado exitosamente.',
        ]);
}
}
