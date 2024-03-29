<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMovieRequest extends FormRequest
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
            'title' => 'required|string|unique:movies,title',
            'description' => 'required|string',
            'image' => 'required|string',
            'trailer' => 'required|string',
            'year' => 'required|integer|min:1900|max:2024',
            'genre' => 'required|string',
            'duration' => 'required|string',
            'director_id' => 'required|integer|exists:directors,id',
        ];
    }
}
