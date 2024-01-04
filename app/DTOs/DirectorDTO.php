<?php

namespace App\DTOs;

class DirectorDTO
{
    public function __construct(
        public ?string $name = null,
        public ?string $image = null,
        public ?string $description = null,
        public ?string $nationality = null,
        public ?string $birth = null,
        public ?string $death = null
    ) {
    }
    
    public function toArray()
    {
        return array_filter([
            'name' => $this->name,
            'image' => $this->image,
            'description' => $this->description,
            'nationality' => $this->nationality,
            'birth' => $this->birth,
            'death' => $this->death,
        ]);
    }
}
