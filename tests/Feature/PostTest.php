<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function testNoBlogPOstsWhenNothingInDataBase()
    {
        $response = $this->get('/posts');
        $response->assertSeeText('No posts found!');
    }
    public function testSee1BlogPostWhenTherrIs1WithNoComments()
    {
        $post = $this->createDumyBlogPOst();

        $response = $this->get('/posts');
        $response->assertSeeText('New Title');
        $response->assertSeeText('No comments yet!');
        $this->assertDatabaseHas('blog_posts', [
            'title' => 'New Title',
        ]);
    }
    public function testSeeBlogPostWIthComments()
    {
        $post = $this->createDumyBlogPOst();
        Comment::factory()->count(2)->create([
            'blog_post_id' => $post->id,
        ]);
        $response = $this->get('/posts');
        $response->assertSeeText('2comments');

    }

    public function testStoreValid()
    {

        // auth()->login($user);
        $params = [
            'title' => 'Valid title',
            'content' => 'At least 10 characters',
        ];

        $this->ActingAs($this->user())
            ->post('/posts', $params)
            ->assertStatus(302)
            ->assertSessionHas('status');
        $this->assertEquals(session('status'), 'The blog post was created');

    }
    public function testStoreFail()
    {
        $params = [
            'title' => 'x',
            'content' => 'x',
        ];
        $this->ActingAs($this->user())
            ->post('/posts', $params)
            ->assertStatus(302)
            ->assertSessionHas('errors');

        $messages = session('errors')->getMessages();
        // dd($messages->getMessages());
        $this->assertEquals($messages['title'][0], 'The title must be at least 5 characters.');
        $this->assertEquals($messages['content'][0], 'The content must be at least 10 characters.');

    }
    public function testUpdateValid()
    {
        $user = $this->user();
        $post = $this->createDumyBlogPOst($user->id);
        $this->assertDatabaseHas('blog_posts', [
            'title' => 'New Title',
        ]);

        $params = [
            'title' => 'A new named title',
            'content' => 'Content was changed',
        ];
        $this->ActingAs($user)
            ->put("/posts/{$post->id}", $params)
            ->assertStatus(302)
            ->assertSessionHas('status');
        $this->assertEquals(session('status'), 'Blog post was updated successfully');

        $this->assertDatabaseMissing('blog_posts', [
            'title' => 'New Title',
        ]);
        $this->assertDatabaseHas('blog_posts', [
            'title' => 'A new named title',
        ]);

    }
    public function deleteBlogPost()
    {
        $post = $this->createDumyBlogPOst;

        $this->ActingAs($this->user())
            ->delete("/posts/{$post->id}")
            ->assertStatus(302)
            ->assertSessionHas('status');
        $this->assertDatabaseHas('blog_posts', [
            'title' => 'New Title',
        ]);
        $this->assertEquals(session('status'), 'Blog post was deleted');
        $this->assertDatabaseMissing('blog_posts', [
            'title' => 'New Title',
        ]);
    }
    private function createDumyBlogPOst($userId = null): BlogPost
    {
        // $post = new BlogPost();
        // $post->title = 'New Title';
        // $post->content = 'Content of the blog';
        // $post->save();

        return BlogPost::factory()->addstate()->create(
            ['user_id' => $userId ?? $this->user()->id]
        );

        // return $post;
    }

}
