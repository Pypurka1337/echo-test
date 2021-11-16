<?php

namespace Tests\Feature;

use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiAuthorsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_index()
    {
        $response = $this->get('/api/authors/');

        $response->assertStatus(200);
    }

    public function test_show()
    {
        $author_slug = Author::all()->random()->slug;
        $response = $this->get('/api/authors/' . $author_slug);

        $response->assertStatus(200);
    }

    public function test_update()
    {
        $author_slug = Author::all()->random()->slug;
        $uri = '/api/authors/' . $author_slug;
        $data = [
            'first_name' => 'Имя',
            'middle_name' => 'Отчество',
            'last_name' => 'Фамилия',
            'avatar' => '/images/image-1.jpg',
            'birth_year' => 1994,
            'biography' => 'Биография автора',
        ];
        $headers = [
            'Accept' => 'application/json'
        ];

        $response = $this->putJson($uri, $data, $headers);

        $response->assertStatus(200);
    }

//    public function test_store()
//    {
//        $response = $this->get('/api/authors/');
//
//        $response->assertStatus(200);
//    }

//    public function test_destroy()
//    {
//        $response = $this->get('/api/authors/');
//
//        $response->assertStatus(204);
//    }
}
