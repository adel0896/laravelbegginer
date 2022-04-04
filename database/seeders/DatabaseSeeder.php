<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('users')->insert([
        //     'name' => 'John Doe',
        //     'email' => 'john@laravel.test',
        //     'email_verified_at' => now(),
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //     'remember_token' => Str::random(10),
        // ]);
        $doe = User::factory()->addstate()->create();
        $else = User::factory(20)->create();
        $users = $else->concat([$doe]);

        $posts = BlogPost::factory(50)->make()->each(function ($post) use ($users) {
            $post->user_id = $users->random()->id;
            $post->save();
        });
        $userscomments = User::all();
        $allposts = BlogPost::all();
        $comments = Comment::factory(150)->make()->each(function ($comment) use ($posts, $userscomments) {
            $comment->commentable_id = $posts->random()->id;
            $comment->commentable_type = 'App\Modles\BlogPost';
            $comment->user_id = $userscomments->random()->id;
            $comment->save();
        });
        $comments = Comment::factory(150)->make()->each(function ($comment) use ($userscomments) {
            $comment->commentable_id = $userscomments->random()->id;
            $comment->commentable_type = 'App\Modles\User';
            $comment->user_id = $userscomments->random()->id;
            $comment->save();
        });
        $this->call([TagsTableSeeder::class, BlogPostTagTableSeeder::class]);
    }
}
