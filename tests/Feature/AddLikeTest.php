<?php

namespace Tests\Feature;

use App\User;
use App\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddLikeTest extends TestCase
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
    public function testShould_able_to_add_like()
    {
        factory(Article::class)->create();
        $article = Article::first();

        $response = $this->actingAs($this->user)
            ->json('PUT', route('articles.like', ['article' => $article]));

        $response->assertStatus(200)
            ->assertJson([
              'id' => $article->id,
              'countLikes' => $article->count_likes,
            ]);

        $this->assertEquals(1, $article->likes()->count());
    }

    public function testShould_able_to_only_like_one_if_like_the_same_photo_twice()
    {
      factory(Article::class)->create();
      $article = Article::first();

      $response = $this->actingAs($this->user)
          ->json('PUT', route('articles.like', ['article' => $article]));
      $response = $this->actingAs($this->user)
          ->json('PUT', route('articles.like', ['article' => $article]));

      $response->assertStatus(200)
          ->assertJson([
            'id' => $article->id,
            'countLikes' => $article->count_likes,
          ]);

      $this->assertEquals(1, $article->likes()->count());
    }

    public function testShould_able_to_delete_like()
    {
        factory(Article::class)->create();
        $article = Article::first();

        $response = $this->actingAs($this->user)
            ->json('DELETE', route('articles.unlike', ['article' => $article]));

        $response->assertStatus(200)
            ->assertJson([
              'id' => $article->id,
              'countLikes' => $article->count_likes,
            ]);

        $this->assertEquals(0, $article->likes()->count());
    }
}
