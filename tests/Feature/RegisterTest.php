<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class RegisterTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testShould_make_new_user()
    {
        Storage::fake('s3');

        $data = [
          'name' => 'test user',
          'email' => 'test@test.com',
          'password' => 'test1234',
          'password_confirmation' => 'test1234',
          'avatar' => UploadedFile::fake()->image('avatar.jpg'),
        ];

        $response = $this->json('POST', route('register'), $data);

        $user = User::first();

        $this->assertEquals($data['name'], $user->name);

        Storage::cloud()->assertExists($user->avatar);

    }

    public function testShould_not_save_file_to_s3_if_db_users_error()
    {
        Schema::drop('users');

        Storage::fake('s3');

        $data = [
          'name' => 'test user',
          'email' => 'test@test.com',
          'password' => 'test1234',
          'password_confirmation' => 'test1234',
          'avatar' => UploadedFile::fake()->image('avatar.jpg'),
        ];

        $response = $this->json('POST', route('register'), $data);

        $response->assertStatus(500);

        $this->assertEquals(0, count(Storage::cloud()->files()));
    }

    public function testShould_not_insert_to_db_if_save_avatar_file_error()
    {
        Storage::shouldReceive('cloud')
            ->once()
            ->andReturnNull();

        $data = [
          'name' => 'test user',
          'email' => 'test@test.com',
          'password' => 'test1234',
          'password_confirmation' => 'test1234',
          'avatar' => UploadedFile::fake()->image('avatar.jpg'),
        ];

        $response = $this->json('POST', route('register'), $data);

        $response->assertStatus(500);

        $this->assertEmpty(User::all());
    }
}
