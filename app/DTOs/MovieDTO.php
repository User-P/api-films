<?php

namespace App\DTOs;

class MovieDTO
{
    public function __construct(
        public string $title,
        public string $description,
        public string $image,
        public string $trailer,
        public int $year,
        public string $genre,
        public string $duration,
        public int $director_id
    ) {
    }

    public function toArray()
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->image,
            'trailer' => $this->trailer,
            'year' => $this->year,
            'genre' => $this->genre,
            'duration' => $this->duration,
            'director_id' => $this->director_id,
        ];
    }
}
