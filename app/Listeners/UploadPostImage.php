<?php

namespace App\Listeners;

use App\Events\UploadImage;
use Cloudinary\Cloudinary;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UploadPostImage
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UploadImage $event): void
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
        if ($event->post->image_path) {

            $parsed = parse_url($event->post->image_path, PHP_URL_PATH);
            $parts = explode('/', trim($parsed, '/'));

            $file = end($parts);
            $folder = prev($parts);

            $publicId = $folder . '/' . pathinfo($file, PATHINFO_FILENAME);

            $cloudinary->uploadApi()->destroy($publicId);
        }

        // 2️⃣ Subir la nueva imagen con transformaciones
        $result = $cloudinary->uploadApi()->upload(
            $event->imagePath,
            [
                'folder' => 'posts',
                'public_id' => $event->post->slug,
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
        $event->post->update([
            'image_path' => $result['secure_url']
        ]);

        // 4️⃣ Eliminar el archivo temporal
        if (file_exists($event->imagePath)) {
            unlink($event->imagePath);
        }
    }
}
