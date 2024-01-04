<?php

namespace Tests\Feature;

use App\Models\Director;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DirectorTest extends TestCase
{
    use RefreshDatabase;

    public function testDirectorCanBeRetrieved()
    {
        $directors = Director::factory(5)->create();

        $response = $this->get('/api/directors');

        $response->assertStatus(200);

        $response->assertJsonCount(5, 'directors');
    }
}
