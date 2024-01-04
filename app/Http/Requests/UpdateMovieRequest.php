<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMovieRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'string',
            'description' => 'string',
            'image' => 'string',
            'trailer' => 'string',
            'year' => 'integer|min:1900|max:2024',
            'genre' => 'string',
            'duration' => 'integer|min:1',
            'director_id' => 'integer|exists:directors,id',
        ];
    }
}
