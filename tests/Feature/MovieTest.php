<?php

// tests/Feature/MovieTest.php

namespace Tests\Feature;

use App\Models\Director;
use Tests\TestCase;
use App\Models\Movie;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MovieTest extends TestCase
{
    use RefreshDatabase;

    public function testMoviesCanBeRetrieved()
    {
        Director::factory()->create();
        $movies = Movie::factory()->count(3)->create();

        $response = $this->get('/api/movies');

        $response->assertStatus(200);

        $response->assertJsonCount(3, 'movies');
    }
}
