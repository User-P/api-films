<?php

namespace App\DTOs;

class MovieDTO
{
    public function __construct(
        public ?string $title = null,
        public ?string $description= null,
        public ?string $image= null,
        public ?string $trailer= null,
        public ?int $year= null,
        public ?string $genre= null,
        public ?string $duration= null,
        public ?int $director_id = null
    ) {
    }

    public function toArray()
    {
        return array_filter([
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->image,
            'trailer' => $this->trailer,
            'year' => $this->year,
            'genre' => $this->genre,
            'duration' => $this->duration,
            'director_id' => $this->director_id,
        ]);
    }
}
