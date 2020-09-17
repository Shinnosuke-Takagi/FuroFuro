<?php

namespace Tests\Feature;

use App\User;
use App\Article;
use App\Photo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class UploadFileTest extends TestCase
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
    public function testShoud_able_to_upload_file_to_s3_and_db_()
    {
        Storage::fake('s3');

        $response = $this->actingAs($this->user)
            ->json('POST', route('articles.store'), [
              'title' => 'testtitle',
              'body' => 'testbody',
              'map_query' => 'teststation',
              'main_filename' => UploadedFile::fake()->image('mainfile.jpg'),
              'files' => [
                UploadedFile::fake()->image('mainfile_1.jpg'),
                UploadedFile::fake()->image('mainfile_2.jpg'),
                UploadedFile::fake()->image('mainfile_3.jpg'),
              ],
            ]);

        $response->assertStatus(302);

        $article = Article::first();
        $photo = Photo::first();

        Storage::cloud()->assertExists($article->main_filename);
        Storage::cloud()->assertExists($photo->filename);
    }

    public function testShould_not_save_file_to_s3_if_db_articles_error()
    {
        Schema::drop('articles');

        Storage::fake('s3');

        $response = $this->actingAs($this->user)
            ->json('POST', route('articles.store'), [
              'title' => 'testtitle',
              'body' => 'testbody',
              'map_query' => 'teststation',
              'main_filename' => UploadedFile::fake()->image('mainfile.jpg'),
              'files' => [
                UploadedFile::fake()->image('mainfile_1.jpg'),
                UploadedFile::fake()->image('mainfile_2.jpg'),
                UploadedFile::fake()->image('mainfile_3.jpg'),
              ],
            ]);

        $response->assertStatus(500);

        $this->assertEquals(0, count(Storage::cloud()->files()));
    }

    public function testShould_not_save_file_to_s3_if_db_photos_error()
    {
        Schema::drop('photos');

        Storage::fake('s3');

        $response = $this->actingAs($this->user)
            ->json('POST', route('articles.store'), [
              'title' => 'testtitle',
              'body' => 'testbody',
              'map_query' => 'teststation',
              'main_filename' => UploadedFile::fake()->image('mainfile.jpg'),
              'files' => [
                UploadedFile::fake()->image('mainfile_1.jpg'),
                UploadedFile::fake()->image('mainfile_2.jpg'),
                UploadedFile::fake()->image('mainfile_3.jpg'),
              ],
            ]);

        $response->assertStatus(500);

        $this->assertEquals(0, count(Storage::cloud()->files()));
    }

    public function testShould_not_insert_to_db_if_save_file_error()
    {
        Storage::shouldReceive('cloud')
            ->once()
            ->andReturnNull();

        $response = $this->actingAs($this->user)
            ->json('POST', route('articles.store'), [
              'title' => 'testtitle',
              'body' => 'testbody',
              'map_query' => 'teststation',
              'main_filename' => UploadedFile::fake()->image('mainfile.jpg'),
              'files' => [
                UploadedFile::fake()->image('mainfile_1.jpg'),
                UploadedFile::fake()->image('mainfile_2.jpg'),
                UploadedFile::fake()->image('mainfile_3.jpg'),
              ],
            ]);

        $response->assertStatus(500);

        $this->assertEmpty(Article::all());
        $this->assertEmpty(Photo::all());
    }
}
