<?php
// tests/Unit/MovieTest.php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\DTOs\MovieDTO;

class MovieTest extends TestCase
{
    public function testMovieDTOCanBeCreated()
    {
        $data = [
            'title' => 'Test Movie',
            
        ];

        $movieDTO = new MovieDTO(...$data);

        $this->assertEquals('Test Movie', $movieDTO->title);
        
    }

}

