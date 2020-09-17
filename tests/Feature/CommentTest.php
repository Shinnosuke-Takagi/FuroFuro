<?php

namespace Tests\Feature;

use App\User;
use App\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testShould_able_to_add_comment()
    {
        factory(Article::class)->create();
        $article = Article::first();

        $content = 'sample content';

        $response = $this->actingAs($this->user)
            ->json('POST', route('comment.store', ['article' => $article]), [
              'article_id' => $article->id,
              'user_id' => $this->user->id,
              'content' => $content,
            ]);

        $comments = $article->comments()->get();

        $response->assertStatus(302);

        $this->assertEquals(1, $comments->count());

        $this->assertEquals($content, $comments[0]->content);
    }
}
