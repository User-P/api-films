<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Models\Movie;

class MovieController extends ApiController
{
    public function index()
    {
        return $this->showCollection(Movie::all(), 'movies');
    }

    public function store(StoreMovieRequest $request)
    {
        return $this->showInstance(Movie::create($request->validated()), 'movie');
    }

    public function show(Movie $movie)
    {
        return $this->showInstance($movie, 'movie');
    }

    public function update(UpdateMovieRequest $request, Movie $movie)
    {
        $movie->update($request->validated());
        return $this->showInstance($movie, 'movie');
    }

    public function destroy(Movie $movie)
    {
        $movie->delete();
        return $this->successResponse('Movie deleted successfully');
    }
}
