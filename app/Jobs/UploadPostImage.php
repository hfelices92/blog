<?php

namespace App\Jobs;

use App\Models\Post;
use Cloudinary\Cloudinary;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UploadPostImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $post;
    public $imagePath;

    public function __construct(Post $post, string $imagePath)
    {
        $this->post = $post;
        $this->imagePath = $imagePath;
    }

    public function handle()
    {
        // Inicializar Cloudinary
        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ]
        ]);

        // 1️⃣ Eliminar imagen anterior si existe
        if ($this->post->image_path) {

            $parsed = parse_url($this->post->image_path, PHP_URL_PATH);
            $parts = explode('/', trim($parsed, '/'));

            $file = end($parts);
            $folder = prev($parts);

            $publicId = $folder . '/' . pathinfo($file, PATHINFO_FILENAME);

            $cloudinary->uploadApi()->destroy($publicId);
        }

        // 2️⃣ Subir la nueva imagen con transformaciones
        $result = $cloudinary->uploadApi()->upload(
            $this->imagePath,
            [
                'folder' => 'posts',
                'public_id' => $this->post->slug,
                'overwrite' => true,
                'resource_type' => 'image',
                'transformation' => [
                    [
                        'height' => 800,
                        'crop' => 'scale',
                        'quality' => 'auto',
                        'fetch_format' => 'auto'
                    ]
                ]
            ]
        );

        // 3️⃣ Actualizar Post con la nueva URL
        $this->post->update([
            'image_path' => $result['secure_url']
        ]);

        // 4️⃣ Eliminar el archivo temporal
        if (file_exists($this->imagePath)) {
            unlink($this->imagePath);
        }
    }
}
