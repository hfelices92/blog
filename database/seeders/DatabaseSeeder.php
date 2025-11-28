<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Storage::deleteDirectory('posts');
        Storage::makeDirectory('posts');
        User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('12341234'),
            'two_factor_secret' => null,
            
        ]);

        Category::factory()->count(10)->create();
        Post::factory(50)->create();

        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
    }
}
