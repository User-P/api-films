<?php

namespace Tests\Unit;

use App\DTOs\DirectorDTO;
use PHPUnit\Framework\TestCase;

class DirectorTest extends TestCase
{
    public function testDirectorDTOCanBeCreated()
    {
        $data = [
            'name' => 'Test Director',
            'image' => 'test.jpg',
            'description' => 'Test Description',
            'birth' => '2000-01-01',
            'death' => '2000-01-01', 
        ];

        $directorDTO = new DirectorDTO(...$data);

        $this->assertEquals('Test Director', $directorDTO->name);
        $this->assertEquals('test.jpg', $directorDTO->image);
        $this->assertEquals('Test Description', $directorDTO->description);
        $this->assertEquals('2000-01-01', $directorDTO->birth);
        $this->assertEquals('2000-01-01', $directorDTO->death);
    }

        // Añade más métodos de prueba según sea necesario

        public function testDirectorDTOCanBeUpdated(){
            $directorDTO = new DirectorDTO('Test Director', 'test.jpg', 'Test Description', '2000-01-01', '2000-01-01');
            $directorDTO->name = 'Updated Director';
            $directorDTO->image = 'updated.jpg';
            $directorDTO->description = 'Updated Description';
            $directorDTO->birth = '2001-01-01';
            $directorDTO->death = '2001-01-01';

            $this->assertEquals('Updated Director', $directorDTO->name);
            $this->assertEquals('updated.jpg', $directorDTO->image);
            $this->assertEquals('Updated Description', $directorDTO->description);
            $this->assertEquals('2001-01-01', $directorDTO->birth);
            $this->assertEquals('2001-01-01', $directorDTO->death);
        }

}
